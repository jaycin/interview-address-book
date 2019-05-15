<?php
require_once "constants.php";
class DataBase
{
    private $connection;
    public function __construct()
    {
        $this->connection = mysqli_connect(HOST,USERNAME,PASSWORD,"");
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->connection->select_db(DB) or $this->createDb();
        $this->dbUp();

    }

    private function createDb()
    {
        $sql = "CREATE DATABASE ".DB;
        $this->query($sql);
        $this->connection->select_db(DB) or die("Unable to select database");
    }


    private function dbUp()
    {
        $sql = "SHOW TABLES";
        $result = $this->query($sql);
        $tableExists = false;
        foreach ($result as $k=>$v)
        {
            if($v["Tables_in_".DB] == "contact") {
                $tableExists = true;
                break;
            }
        }
        if(!$tableExists)
        {
            $sql = "CREATE TABLE contact (ContactId int NOT NULL AUTO_INCREMENT
                    ,Name VARCHAR(255)
                    ,Surname VARCHAR(255)
                    ,IsDeleted TINYINT(2)
                    ,PRIMARY KEY (ContactId) )";
            $result = $this->query($sql);

            $sql = "CREATE TABLE contactnumber (ContactNumberId int NOT NULL AUTO_INCREMENT
                    ,ContactNumber VARCHAR(255)
                    ,ContactId int
                    ,PRIMARY KEY (ContactNumberId)
                    ,INDEX (ContactId) )";
            $result = $this->query($sql);

            $sql = "CREATE TABLE contactemail (ContactEmailId int NOT NULL AUTO_INCREMENT
                    ,ContactEmail VARCHAR(255)
                    ,ContactId int
                    ,PRIMARY KEY (ContactEmailId)
                    ,INDEX (ContactId) )";
            $result = $this->query($sql);
        }

    }

    public function query($sql)
    {

        $query = $this->connection->query($sql) or trigger_error($this->connection->error . "[$sql]");
        /*
        echo $sql;
        echo("<pre>");
        print_r($query);
        echo("</pre>");
        */
        if ($query === TRUE && strpos($sql,"INSERT") !== FALSE) {
            $last_id = $this->connection->insert_id;
            return $last_id;
        }
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $return[] = $row;
            }
        }
        if (!empty($return)) {
            return $return;
        }
        else if ($query === TRUE)
        {
            return $query;
        }
        else
        {
            return "";
        }
    }

    public function multiQuery($sql)
    {
        if($this->connection->multi_query() === true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public function escape($string)
    {
        return mysqli_escape_string($this->connection,$string);
    }

}