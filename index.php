<?php
include_once('session.php');
include_once('server.php');

function get_key(){
    //public keys
    $n = 738721;
    $g = 424811;
    //private key
    $b = 52667;
    //get value to calculate the key from server
    $A = $_SESSION['A'];
    return ($A^$b) % $n;
}

//function to encrypt message and send it to server
function msg_encrypt($text,$name){

    $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>".$name."</b> ".stripslashes(htmlspecialchars($text))."<br></div>";

    //Store the cipher method
    $ciphering = "AES-128-CTR";

    // Store the encryption key
    $key = get_key();
    $options = 0;

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);

    // Non-NULL Initialization Vector for encryption
    $init_vector = '1234567891011121';

    // Use openssl_encrypt() function to encrypt the data
    $encrypted = openssl_encrypt($text_message, $ciphering, $key, $options, $init_vector);
    $encrypted = $encrypted.PHP_EOL;

    file_put_contents("log.html", $encrypted, FILE_APPEND | LOCK_EX);
    header("Location: index.php");
}

//the decryption function
function msg_decrypt($encrypted){

    //Store the cipher method
    $ciphering = "AES-128-CTR";

    // Store the encryption key
    $key = get_key();
    $options = 0;

    // Use OpenSSl Encryption method
    $iv_length = openssl_cipher_iv_length($ciphering);

    // Non-NULL Initialization Vector for encryption
    $init_vector = '1234567891011121';

    $decrypted=openssl_decrypt ($encrypted, $ciphering,$key, $options, $init_vector);
    echo $decrypted;

}

//encrypting every time a message is sent
if(isset($_POST['submitmsg'])){
  msg_encrypt($_POST['usermsg'], $_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Chatting App</title>
        <link rel="stylesheet" href="style.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    </head>
    <body>
        <nav>
          <div class="nav-items">
            <li><a href="#">Online Chat</a></li>
            <li><a href="add.php">Add a user</a></li>
            <li><a href="logout.php">Logout</a></li>
          </div>
        </nav>
        <div class="container">
            <div id="header">
                <p class="welcome">Welcome, <b><?php echo $_SESSION['username']; ?></b></p>
            </div>
            <div class="chatbox">
            <?php
            //ensuring there are messages to retrieve
            if(file_exists("log.html") && filesize("log.html") > 0){
              $contents = file_get_contents("log.html");
              $stringArr = explode("\n", $contents);
              foreach  ($stringArr as $line){
                msg_decrypt($line);
              }
            }
            ?>
            </div>
            <form name="message" action="#" method="POST">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the chatbox
                            //Auto-scroll
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
                            }
                        }
                    });
                setInterval (loadLog, 2500);
            }
        </script>
    </body>
</html>
