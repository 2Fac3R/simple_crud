<?php

if($_POST){
    require_once '../../myApp/User.inc.php';

    $myUser = new User();
    $request = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];
    echo $myUser->store($request);
}

?>

<form action="" method="post">
    Username:   <input type="text" name="username"> <br>
    Email:      <input type="email" name="email"> <br>
    Password:   <input type="password" name="password"> <br>
    <input type="submit" name="submit" value="Create">
</form>