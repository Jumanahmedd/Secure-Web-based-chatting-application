<?php
include_once('session.php');

$error_message = false;
$message = false;
error_reporting(0);

$conn = mysqli_connect('localhost', 'root', '','cryptography');
if(isset($_POST['register'])){

  $username = mysqli_real_escape_string($conn,$_POST['username']);
  $password = mysqli_real_escape_string($conn,$_POST['password']);
  $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);
  $username = stripcslashes ($username);
  $password = stripcslashes ($password);
  $confirm_password= stripcslashes($confirm_password);
  $username = htmlspecialchars($username);

  //connection
  include_once('database.php');

  //checking the table for the email inserted by the user
  $sql = "SELECT * from users where username=? ";

  //prepared statments
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt,"s",$username);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  $num = mysqli_stmt_num_rows($stmt);//the number of rows containing the inserted email

  if ($num == 0){

    if(isset($_POST['password'])) {
      $password = $_POST['password'];
      $number = preg_match('@[0-9]@', $password);
      $uppercase = preg_match('@[A-Z]@', $password);
      $lowercase = preg_match('@[a-z]@', $password);
      $specialChars = preg_match('@[^\w]@', $password);

      if(strlen($password) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
        $error_message = "Password must contain: <br>  *a minimum of 8 characters in length<br>  *at least one number<br>  *at least one upper case letter<br>  *at least one lower case letter<br>  *at least special character.";
      } else {
        if ($password === $confirm_password){

          //hashing the password
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);

          //SQL statement
          $sql = "INSERT INTO users(username, password) VALUES ('$username' , '$hashed_password')";

          //query
          $stmt = mysqli_prepare($conn , $sql);
          mysqli_stmt_bind_param($stmt,"ss", $username,$hashed_password);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);

          $message = 'User was added successfully!';

        } else{
            $error_message = "Passwords do not match";
        }
      }
    }
  }else{
    $error_message = "This username already exists";
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Chatting App</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
  </head>
  <body>
    <nav>
      <div class="nav-items">
        <li><a href="index.php">Online Chat</a></li>
        <li><a href="#">Add a user</a></li>
        <li><a href='logout.php'>Logout</a></li>
      </div>
    </nav>
    <div class="form">
      <h1>Add a user</h1>
      <form class="" action="" method="POST" onsubmit="process(event)">
        <input type="text" name="username" value="" placeholder="Username" required><br>
        <input type="password" name="password" value="" placeholder="Password" required><br>
        <input type="password" name="confirm_password" value="" placeholder="Confirm Password" required><br><br>
        <p style="color:red"><?php echo $error_message ?></p>
        <p style="color:green"><?php echo $message ?></p>
        <button type="submit" name="register" class="btn">Add user</button>
      </form>
    </div>
  </body>
</html>
