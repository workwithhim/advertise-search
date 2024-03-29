<?php
/* User login process, checks if user exists and password is correct */

// Escape email to protect against SQL injections
require_once('./helpers/index.php');
// $email = $mysqli->escape_string($_POST['email']);

$email = test_input($_POST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format";
    $_SESSION['message'] = $emailErr;
    return;
}
if (strlen($_POST['password']) < 1) {
    $_SESSION['message'] = "Password is not empty";
    return;
}
$result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

if ($result->num_rows == 0) {
    $_SESSION['message'] = "User with that email doesn't exist!";
    return;
} else {
    $user = $result->fetch_assoc();

    if (password_verify($_POST['password'], $user['password'])) {

        $_SESSION['email'] = $user['email'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['active'] = $user['active'];
        $_SESSION['logged_in'] = 1;
        $_SESSION['msg']['msg_s'] = '';
        $_SESSION['msg']['msg_c'] = '';
        header("location: /");
    } else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
        return;
    }
}