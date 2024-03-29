<?php

define('ADMIN', 1);
define('NORMAL_USER', 2);

class Role
{
    /* 
        TABLE -> roles
            +-------------+--------------+------+-----+---------+----------------+
        | Field       | Type         | Null | Key | Default | Extra          |
        +-------------+--------------+------+-----+---------+----------------+
        | id          | int(11)      | NO   | PRI | NULL    | auto_increment |
        | role        | varchar(255) | NO   |     | NULL    |                |
        +-------------+--------------+------+-----+---------+----------------+
        
        TABLE -> user_role
        +---------+---------+------+-----+---------+----------------+
        | Field   | Type    | Null | Key | Default | Extra          |
        +---------+---------+------+-----+---------+----------------+
        | id      | int(11) | NO   | PRI | NULL    | auto_increment |
        | role_id | int(11) | NO   |     | NULL    |                |
        | user_id | int(11) | NO   |     | NULL    |                |
        +---------+---------+------+-----+---------+----------------+
    */
    function __construct($role = NORMAL_USER)
    {
        // Connection to the database
        $this->db_myTable = 'roles';
        $this->db_myId = 'id';

        // Relation 1-1: One User can only have one role
        $this->db_user_role = 'user_role';
        $this->db_role_id = 'role_id';
        $this->db_user_id = 'user_id';

        // Creates dbh object (database handler)
        $this->dbh = new Connection(DB_HOST, DB_USER, DB_PASSWORD);
        $this->dbh = $this->dbh->Connect(DB_DATABASE_NAME, $this->db_myTable);

        // Role Attributes
        $this->role = $role;
    }

    // Get :role_id from :user_id
    function show($id)
    {
        try {
            $query = "
                SELECT role_id
                FROM $this->db_user_role
                WHERE $this->db_user_id = :user_id
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

    function store($request)
    {
        try {
            $roleID = $request['role_id'];
            $userID = $request['user_id'];

            $query = "
                INSERT INTO $this->db_user_role
                    ($this->db_role_id, $this->db_user_id)
                VALUES 
                    (:role_id, :user_id)
            ";

            $sth = $this->dbh->prepare($query);
            $sth->bindParam(':role_id', $roleID, PDO::PARAM_INT);
            $sth->bindParam(':user_id', $userID, PDO::PARAM_INT);
            $sth->execute();

            return json_encode($sth);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function setRole($id)
    {
        switch ($id) {
            case ADMIN:
                $myRole = 'ADMINISTRATOR';
                break;

            case NORMAL_USER:
                $myRole = 'NORMAL_USER';
                break;

            default:
                $myRole = 'NORMAL_USER';
                break;
        }

        $this->role = $myRole;
    }

    function __destruct()
    {
        //
    }
}
