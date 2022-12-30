<?php
session_start();

//variable to store and print error message inside the form
$error_message = false;

//connection
include_once('database.php');

if(isset($_POST['login'])){
  //getting data
  $username = stripcslashes(mysqli_real_escape_string($conn,$_POST['username']));
  $password = stripcslashes(mysqli_real_escape_string($conn,$_POST['password']));
  $username = htmlspecialchars($username);

  //checking the table for the username inserted by the user
  $sql = "SELECT * from users where username='$username'";
  $result = mysqli_query($conn, $sql);
  $num = mysqli_num_rows($result);

  if ($num == 1){
    $row = mysqli_fetch_assoc($result);
    if ($row['username'] === $username && password_verify($password, $row['password'])){

        $_SESSION['username'] = $username;
        include_once('auth_server.php');
        header("Location: authentication.php");
    }else{
      $error_message = "Incorrect Username or Password";
    }
  }else{
    $error_message = "Incorrect Username or Password";
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Chatting App</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav>
      <div class="nav-items">
        <li><a href="index.php">Online Chat</a></li>
        <?php
          if (isset($_SESSION['username']))
          {
            echo "<li><a href='logout.php'>Logout</a></li>";
          }
          else {
            echo "<li><a href='login.php'>LogIn</a></li>";
          }
          ?>
        </div>
    </nav>
    <div class="form">
      <h1>Login Form</h1>
      <form class="" action="#" method="POST">
        <input type="text" name="username" value="" placeholder="Username" required><br>
        <input type="password" name="password" value="" placeholder="Password" required><br>
        <p style="color:red"><?php echo $error_message?></p><br>
        <button type="submit" name="login" class="btn">LogIn</button><br>
      </form>
    </div>
  </body>
</html>
