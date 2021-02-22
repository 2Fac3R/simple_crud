<?php

require_once 'config/Connection.php';
require_once 'config/config.php';
require_once 'Role.inc.php';

class User {
    /* 
        TABLE -> users
        +----------+--------------+------+-----+---------+----------------+
        | Field    | Type         | Null | Key | Default | Extra          |
        +----------+--------------+------+-----+---------+----------------+
        | user_id  | int(11)      | NO   | PRI | NULL    | auto_increment |
        | username | varchar(255) | NO   |     | NULL    |                |
        | email    | varchar(255) | NO   |     | NULL    |                |
        | password | varchar(255) | NO   |     | NULL    |                |
        +----------+--------------+------+-----+---------+----------------+
    */
    function __construct() {

        // Connection to the database
        $this->db_myTable = 'users';
        $this->db_myId = 'user_id';

        // Creates dbh object (database handler)
        $this->dbh = new Connection(DB_HOST, DB_USER, DB_PASSWORD);
        $this->dbh = $this->dbh->Connect(DB_DATABASE_NAME, $this->db_myTable);

        $this->role = new Role();

        // Forms
        $this->form_create = 'create.php';
        $this->form_edit = 'edit.php'; 

        // URL's
        $this->myPath = '/forms/users/';
        $this->url_index = 'index.php';
        $this->url_show = 'show.php';

    }
    
    /* Display a listing of the resource */
    function index() {      
        try {
            $query = 
            "
                SELECT *
                FROM $this->db_myTable
            ";
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
        header("Location: /".APP_NAME."$this->myPath$this->form_create");
    }

    /* Store a newly created resource in storage. */
    function store($request) {
        try {
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

            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->execute();

            $req = [
                'role_id' => $request['role_id'], 
                'user_id' => $this->dbh->lastInsertId()
            ];

            $this->role->store($req);
            
            return json_encode($sth);

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /* Show the specified resource. */
    function show($id) {
        try {
            $query = 
            "
                SELECT *
                FROM $this->db_myTable
                WHERE $this->db_myId = :user_id
            ";
            
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
        header("Location: /".APP_NAME."$this->myPath$this->form_edit");
    }

    /* Updates the specified resource */
    function update($request, $id) {
        try {
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

            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $sth->execute();
            
            header("Location: /".APP_NAME."$this->myPath$this->url_show?id=$id");

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /* Destroys the specified resource. */
    function destroy($id) {
        try {
            $query = 
            "
                DELETE FROM $this->db_myTable 
                WHERE user_id = :user_id
            ";
            
            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $sth->execute();
            
            header("Location: /".APP_NAME."$this->myPath$this->url_index");

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    function hasRole($id) {
        $this->role->setRole($this->role->show($id));

        return json_encode($this->role);
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