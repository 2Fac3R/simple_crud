<?php

if ($_POST) {
    require_once '../../myApp/User.inc.php';

    $myUser = new User();
    $request = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'role_id' => $_POST['roles'],
    ];
    echo $myUser->store($request);
} ?>

<form action="" method="post">
    Username: <input type="text" name="username">     <br>
    Email:    <input type="email" name="email">       <br>
    Password: <input type="password" name="password"> <br>
    Role: 
    <select name="roles">
        <option value="1">Admin</option>
        <option value="2">Normal User</option>
    </select> <br>
    <input type="submit" name="submit" value="Create">
</form>