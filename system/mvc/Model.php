<?php
namespace System\mvc;

use \PDO as PDO;

class Model 
{
    protected $db;
    public $model;
    private $where;
    private $limit;
    private $join;
    private $order;
    private $set;
    private $utf;
    private $select = "SELECT *";
    private $custom_query = null;
    private $update;
    protected $table;

    public function __construct()
    {
        $this->db = \System\configs\DB::getInstance()->getConnection();
    }

    public function select($select)
    {
        if (isset($select) && is_string($select)) {
            $this->select = "SELECT ".$select;
        }

        if (is_array($select) && !empty($select)) {
            $this->select = "SELECT ".implode(",", $select);
        }

    }

    public function where($where=array())
    {
        if (empty($where)) {
            throw new \Exception("Where clause is empty");
        }

        $i = 1;
        $z = 0;

        //total where clause amount
        $where_count = count($where);

        foreach ($where as $whereas => $value) {
            if ($i == $where_count) {
                $this->where .= $whereas." =? ";
            } else {
                $this->where .= $whereas." =? AND ";
            }

            $this->bind_param[$i] = $value;
            $i++;
            $z++;
        // endwhile;
        }

    }

    public function from($table)
    {
        if (is_string($table)) {
            $this->table = $table;
        }
    }

    public function limit($limit)
    {
        $this->limit = " Limit ".$limit;
    }

    public function join($table, $join_on, $position="left")
    {
        $this->join = strtoupper($position)." JOIN ".$table." ON ".$join_on;
    }

    public function orderby($order)
    {
        $this->order = " ORDER BY ".$order;
    }

    public function execute()
    {
        //custom query
        if (!is_null($this->custom_query)) {
            $this->pdo = $this->db->query($this->query);
            return;
        }

        $this->query = $this->select;

        if (!$this->table) {
            throw new \Exception("DB: Have not selected a table");
        }

        $this->query .= " FROM ".$this->table;

        if ($this->join) {
            $this->query .= $this->join;
        }

        if ($this->where) {
            $this->query .= " WHERE ".$this->where;
        }

        if ($this->order) {
            $this->query .= $this->order;
        }

        if ($this->limit) {
            $this->query .= $this->limit;
        }

        // $db = $this->pdo->prepare($this->query);
        error_log("query: ".var_export($this->query, true));
        $this->pdo = $this->db->prepare($this->query);

        //bind the values from where clause
        $this->bindValues();

        if ($this->utf) {
            $this->db->exec("SET NAMES 'utf8';");
        }

        try {
            $this->pdo->execute();
        } catch(\Exception $e){
            throw new \Exception("Error: ".$e->getMessage());
        }

    }

    public function query($query)
    {
        if (!is_string($query)) {
            throw new \Exception("query is not valid: ".$query);
        }

        $this->custom_query = true;
        $this->query = $query;

    }

    public function set($data=array())
    {
        if (empty($data)) {
            throw new \Exception("data needs to be an array");
        }

        $this->set = $data;
    }

    public function update($table, $where_clause, $where_value)
    {
        if (!$this->set) {
            throw new \Exception("have not set any data to update");
        }

        $this->update = "UPDATE $table SET ";

        $countUpdate = count($this->set);
        $i = 1;
        $values = array();

        foreach ($this->set as $field => $value) {
            if ($i == $countUpdate) {
                $this->update .= $field."=? ";
            } else {
                $this->update .= $field."=?, ";
            }

            array_push($values, $value);
            $i++;
        }

        array_push($values, $where_value);

        $this->update .= "WHERE ".$where_clause; 
        // error_log("update: ".$this->update);
        $this->pdo = $this->db->pdo->prepare($this->update);
        $this->db->pdo->exec("SET NAMES 'utf8';");
        // error_log("val: ".var_export($values, true));

        try {
            return $this->pdo->execute($values);
            // return $rows;
        } catch(\PDOException $e) {
            throw new \PDOException("MySql error: ".$e->getMessage());
        }

    }

    public function insertData()
    {

        if (!$this->set) {
            throw new \Exception("have not set any data to insert");
        }

        if (!is_string($this->table)) {
            throw new \Exception("the table name is required to be string");
        }

        $columns = array();
        $values = array();
        $params = array();

        foreach ($this->set as $item => $value) {
            $columns[] = $item;
            $values[":".$item] = $value;
            $params[] = ":".$item;

        }

        $query = "INSERT INTO ".$this->table." (".implode(", ", $columns).") VALUES(".implode(", ", $params).")";

        $this->pdo = $this->db->prepare($query);
        $this->db->exec("SET NAMES 'utf8';");

        try {
            // $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->execute($values);
            $rows = $this->db->lastInsertId();
            error_log("rows: ".var_export($rows, true));
            return $rows;
        } catch(\PDOException $e) {
            throw new \Exception("MySql error: ".$e->getMessage());
        }

        // $rows = $this->pdo->rowCount();
    }

    public function remove()
    {
        if (!$this->table) {
            throw new \Exception("have not set a table");
        }

        if (!$this->where) {
            throw new \Exception("have not set a where clause");
        }

        $query = "DELETE FROM ".$this->table." WHERE ".$this->where;
        // error_log("query: ".var_export($query, true));
        try {
            $this->pdo = $this->db->pdo->prepare($query);
            $this->bindValues();
            $this->pdo->execute();

            return $this->pdo->rowCount();
        } catch (\PDOException $e) {
            throw new \PDOException("MySql error: ".$e->getMessage());
        }

    }

    private function bindValues()
    {
        //bind the values from where clause
        if (!empty($this->bind_param)){
            foreach ($this->bind_param as $key => $params) {
                if (is_numeric($params)) {
                    $this->pdo->bindValue($key, $params, PDO::PARAM_INT);
                } else {
                    $this->pdo->bindValue($key, $params, PDO::PARAM_STR);
                }

            }
        }
    }

    public function fetchAll()
    {
        return $this->pdo->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne()
    {
        return $this->pdo->fetch(PDO::FETCH_ASSOC);
    }

    public function setName($utf=false)
    {
        if ($utf) {
            $this->utf = true;
        }
    }


}
