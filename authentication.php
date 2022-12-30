<?php
session_start();

//making sure only a user who logged in can access this page
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}

$privateKey = '-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAJi9Ln9dMDOijem8QBKoJ+oHomQeZGGK/hqY9ZiHqaWx42Rv4QGbazpEBs9LcVtqcR+/lt0pYL1aYlXvOY8gEQ6H6z55Axx8KExLbiwtZ1dfbgCyVJL8guEoFGliSc6LFR9xRJ4M307UFqvmdDZWQLhkeJ5ZJSNGUkIZUlRu/ZiZAgMBAAECgYBsZvBGQqxAKWGQ/yN2WlQhvBNb/Vn/BLe9xsuf2sDjsXISyXinPFgI/Vjb7M5iNBRflQmMo55eJXTfSq7OTtLrHgWkWMYdtk1pTAck7SPmb7JappGN9s6TcOOR++HiLs7XBKWEGOSCr32hjpCBxUfz8b75D3mZn+rt+IK+4jQfJQJBAPkd9bk5RJ8xplZqo96gAm55JkDwwvIfdapiDRsptsYbsMKxC4ZUDWIsMjyybFibBVC6gNB2XoZC0h4pvbUGViMCQQCc9Ya1htkLThtAoOuiSKhl7NfiUm8h7OB6X1G64JbWliTiQSoxTBRUfDM5UwGFK3arLPmH5cMpiza+h6NMJzwTAkBGqO3S3OCO+wlAR701X1NxPGHSV7gj7zJz4p3vD3TtWltXzdoD/wFQ0FJrjvxWYZovXMc+2eRT1s48igBqQWLbAkAtjCpdPxZ461+JL6lxXHhRq5syOA274It7t3F2M3pSJxvo9FQUHinAIpKSzf2g8W3sWo7G2uv5gEaQn7fJf5E1AkABH+A7FEyPPEI6I8/P1chsVVtJueoP680TDRRrq9n8c5H29NmAcAMm8KyF4u0cjbbL2WsFn5vKnu1uV19Zi9/o
-----END PRIVATE KEY-----';

$encrypted = $_SESSION['code'];
openssl_private_decrypt($encrypted, $code, $privateKey);

if(isset($_POST['Verify'])){
  $input = $_POST['code'];

  if($input == $code){
    $_SESSION['ID'] = $code;
    header('Location: index.php');
  }else{
    header('Location: logout.php');
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
        <li><a href="login.php">LogIn</a></li>
        </div>
    </nav>
    <div class="popup">
      <div class="popup-div">
        <h1>Verification Code</h1>
        <p><?php echo $code; ?></p>
        <button type="button" name="close" id="close" style="margin-top:50px;">Close</button>
      </div>
    </div>
    <div class="form">
      <h2>Enter verification code to proceed</h2>
      <form class="" action="#" method="POST">
        <input type="text" name="code" value="" placeholder="Verification Code" required><br>
        <button type="submit" name="Verify" class="btn">Verify</button><br>
      </form>
    </div>
    <script type="text/javascript">
      document.querySelector("#close").addEventListener("click", function(){
           document.querySelector(".popup").style.display = "none";
       });
    </script>
  </body>
</html>
