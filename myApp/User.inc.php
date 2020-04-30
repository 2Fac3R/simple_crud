<?php

require_once 'config/Connection.php';
require_once 'config/config.php';

class User extends Connection {

    function __construct() {

        // Connection to the database
        $this->db_myTable = 'users';
        $this->db_myId = 'user_id';

        // Creates dbh object (database handler)
        $this->dbh = new Connection(DB_HOST, DB_USER, DB_PASSWORD);
        $this->dbh = $this->dbh->Connect(DB_DATABASE_NAME, $this->db_myTable);
        
        // User Attributes
        $this->username = "";
        $this->email = "";
        $this->password = "";

        // Forms
        $this->myPath = '/forms/users/';
        $this->form_create = 'create.php';
        $this->form_edit = 'edit.php';

        // URL's
        $this->url_index = 'index.php';
        $this->url_show = 'show.php?id=';

    }
    
    /* Display a listing of the resource */
    function index() {
        $query = 
        "
            SELECT *
            FROM $this->db_myTable
        ";
        
        try {
            $sth = $this->dbh->prepare($query);
            $sth->execute();
            $data = $sth->fetchAll();
            
            return json_encode($data);

        } catch (Exception $e) {

            return $e->getMessage();
        }
        
    }
    
    /* Show the form for creating a new resource. */
    function create() {
        header("Location: /simple_crud$this->myPath$this->form_create");
    }

    function store($request) {
        $username = $request['username'];
        $email = $request['email'];
        $password = hash('sha1', $request['password']);

        $query = 
        "
            INSERT INTO $this->db_myTable
                (username, email, password)
            VALUES 
                (:username, :email, :password)
        ";
        
        try {
            $sth = $this->dbh->prepare($query);
            
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->execute();
            
            return json_encode($sth);

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /* Store a newly created resource in storage. */
    function show($id) {
        $query = 
        "
            SELECT *
            FROM $this->db_myTable
            WHERE $this->db_myId = :user_id

        ";
        
        try {
            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            
            return json_encode($data);

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /* Show the form for editing the specified resource. */
    function edit($id) {
        header("Location: /simple_crud$this->myPath$this->form_edit");
    }

    function update($request, $id) {
        $username = $request['username'];
        $email = $request['email'];
        $password = hash('sha1', $request['password']);

        $query = 
        "
            UPDATE $this->db_myTable
            SET 
                username = :username, 
                email = :email,
                password = :password
            WHERE $this->db_myId = :user_id
        ";
        
        try {
            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $sth->execute();
            
            header("Location: /simple_crud$this->myPath$this->url_show$id");

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    function destroy($id) {
        $query = 
        "
            DELETE FROM $this->db_myTable 
            WHERE user_id = :user_id
        ";
        
        try {
            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $sth->execute();
            
            header("Location: /simple_crud$this->myPath$this->url_index");

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    // Method for testing...
    function test() {
        $query = 
        "
            SELECT *
            FROM $this->db_myTable
        ";
        
        $sth = $this->dbh->prepare($query);
        $sth->execute();
        $data = $sth->fetchAll();
        
        print_r($data);
    }
}