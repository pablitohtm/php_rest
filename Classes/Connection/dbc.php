<?php

namespace Classes\Connection;
use \PDO;

Class Dbc
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "teste";

    private $conn;

    public function __construct(){
        try
        {
            $query = "mysql:host=" . $this->servername . ";dbname=" . $this->dbname;
            $this->conn = new PDO($query, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        }
        catch( Exception $e )
        {
            echo $e;
        }
    }

    public function getConnection(){
        if($this->conn){
            return $this->conn;
        }
    }
}