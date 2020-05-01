<?php

require_once '../../myApp/User.inc.php';

$id = $_GET['id'];
$myUser = new User();

$user = json_decode($myUser->show($id));
$role = json_decode($myUser->hasRole($id));

printf(
    "
    <b> User_ID </b>: %s <br>
    <b> Username </b>: %s <br>
    <b> Email </b>: %s <br>
    <b> Password </b>: %s <br>
    ",  
        $user[0]->user_id, 
        $user[0]->username, 
        $user[0]->email, 
        $user[0]->password
        //$role[0]->role  // It doesn't work
    );

var_dump($role);