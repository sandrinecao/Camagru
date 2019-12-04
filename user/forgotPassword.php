<?php
require("../config/database.php");

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['forgotPassword'])){
        if(empty(test_input($_POST['email']))){
            $error = "Please enter an email";
        }else{
            $email = test_input($_POST["email"]);
            $username = test_input($_POST["username"]);
        }  
        $query = $pdo->prepare('SELECT username FROM users WHERE username = :username');
        $query->bindParam(':username', $username);
        $query->execute();
        $userExists = $query->fetch(PDO::FETCH_ASSOC);
        if ($userExists['username']){
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $updated_token = "UPDATE users SET token = :token WHERE username = :username";
            $stmt = $pdo->prepare($updated_token);
            $stmt->execute(array(
                ':token' => $token,
                ':username' => $username
            ));
            $to      = $email;
            $subject = 'Reset password'; 
            $message = '
            Dear '.$username.',
        
            If this e-mail does not apply to you please ignore it. 
            It appears that you have requested a password reset. 

            To reset your password, please click the link below :
            http://localhost:8080/user/resetPassword.php?username='.$username.'&reset='.$token.'
            If you cannot click it, please paste it into your web browser\'s address bar.
            Thanks,
            The Administration :)';
            $headers = 'From:camagru@42.fr' . "\r\n";
            mail($to, $subject, $message, $headers);
            $reset_mess = "Email has been sent to $email";
        }else{
            $error = "No user with that e-mail address exists.";
        }
    }$pdo = null;
}
?>
        
<?php ob_start();?>
<div class="loginForm">
    <h2 id="title2">Forgotten Password</h2>
    <p id="actMsg" style="color:green;"><?php echo $reset_mess; ?></p>
        <form method="post" action="" style="margin-top:7%;">
        <input type="text" placeholder="Enter your login" name="username">
          <input type="email" placeholder="Enter your email" name="email">
          <span><?php echo $error; ?></span><br />
          <input type="submit" value="Send Link" name="forgotPassword">
        </form>
    </div><br/>
    <div class="loginForm">
            <p style="text-align:center">Do you know your password? <a href="login.php">Click here to login.</a></p>
    </div><br>
<?php $view = ob_get_clean();?>
<?php require("../template.php");?>