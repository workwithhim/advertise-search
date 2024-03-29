<?php
require 'db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $mysqli->escape_string($_POST['email']);
  $result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

  if ($result->num_rows == 0) // User doesn't exist
  {
    $_SESSION['message'] = "User with that email doesn't exist!";
    header("location: /login.php");
  } else {
    $user = $result->fetch_assoc();

    $email = $user['email'];
    $hash = $user['hash'];
    $firstname = $user['firstname'];
    $_SESSION['message'] = "<p>Please check your email <span>$email</span>"
      . " for a confirmation link to complete your password reset!</p>";
    $to = $email;
    $subject = 'Password Reset Link ( localhost:8080 )';
    $message_body = '
        Hello ' . $firstname . ',

        You have requested password reset!

        Please click this link to reset your password:

        https://localhost/CTC/Applet/reset.php?email=' . $email . '&hash=' . $hash;

    mail($to, $subject, $message_body);

    header("location: success.php");
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Reset Your Password</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <div class="form">
    <h1>Reset Your Password</h1>

    <form action="forgot.php" method="post">
      <div class="field-wrap">
        <label>
          Email Address<span class="req">*</span>
        </label>
        <input type="email" required autocomplete="off" name="email" />
      </div>
      <button class="button button-block gradient-custom">Reset</button>
    </form>
  </div>

  <!--Load Cloudflare jquery.min.js online-->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <!--Load index.js from the resource folder-->
  <script src="js/index.js"></script>
</body>

</html>