<?php

class Connection {
    function __construct($servername, $username, $password) {
        
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
    }

    // Connects to the database
    function Connect($database, $tableName) {
        $this->database = $database;
        $this->tableName = $tableName;

        // Creates dbh object (database handler)
        $this->dbh = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->dbh;
    }

    function __destruct() {
        $this->dbh = null;
    }
}