<?php
require("../config/database.php");

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    return $data;
}

$error = $reset_mess = $username_err = $email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = test_input($_POST["username"]);
        if(empty(test_input($_POST["username"]))){
        $username_err = "Please enter your username.";
        }else{
        $username = test_input($_POST["username"]);}
        if(empty(test_input($_POST['email']))){
            $email_err = "Please enter an email address.";
        }
        elseif(!filter_var((test_input($_POST["email"])), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
        }else{
            $email = test_input($_POST["email"]);
            $username = test_input($_POST["username"]);
        }  
        if(empty($username_err) && empty($email_err)){
        $query = $pdo->prepare("SELECT * FROM users WHERE username = :username AND email = :email");
        $query->bindParam(':username', $username);
        $query->bindParam(':email', $email);
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
            To reset your password, please click the link below:
            http://'.$_SERVER['HTTP_HOST'].'/user/resetPassword.php?username='.$username.'&reset='.$token.'
            If you cannot click it, please paste it into your web browser\'s address bar.
            Thanks,
            The Camagru Team';
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
          <span><?php echo $username_err; ?></span>
          <span><?php echo $email_err; ?></span>
          <span><?php echo $error; ?></span><br />
          <input type="submit" value="Send Link" name="forgotPassword">
        </form>
    </div><br/>
    <div class="loginForm">
            <p style="text-align:center">Know your password?<a href="login.php"> Login here.</a></p>
    </div><br>
<?php $view = ob_get_clean();?>
<?php require("../template.php");?>