<?php

if(!empty($_GET['id'])) {
    require_once '../../myApp/User.inc.php';

    $id = $_GET['id'];
    $myUser = new User();

    $old = json_decode($myUser->show($id));

    if($_POST) {
        $request = [
            'username' => $_POST['username'],
            'email'    => $_POST['email'],
            'password' => $_POST['password']
        ];
        echo $myUser->update($request, $id);
    }

    echo "
    
    <form action='' method='post'>
        Username: <input type='text' name='username' value='".$old[0]->username."'> <br>
        Email:    <input type='email' name='email' value='".$old[0]->email."'>      <br>
        Password: <input type='password' name='password' value=''>                  <br>
        <input type='submit' name'submit' value'Edit'>
    </form>

    ";
} else {
    echo "No ID provided";
}
?>

