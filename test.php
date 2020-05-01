<?php

// require_once 'db/connection.inc.php';
require_once 'myApp/User.inc.php';

// $conn = new PDO("mysql:host=$servername", $username, $password);

// if ($conn) 
// {
    // die('Connected');
    $myUser = new User();
    //echo $myUser->index();
    //$myUser->create();
    //echo $myUser->show(2);
    $request = [
        'username' => 'myUserName',
        'email' => 'myEmail',
        'password' => 'myPassword'
    ];
    echo $myUser->create();
// }
// else 
// {
//     die('Not Connected');
// }