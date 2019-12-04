<?php
require_once("../config/database.php");
session_start();

if (($_SESSION['loggedin'] == true))
    header('Location: ../index.php');
 
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = $activation_mess = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(test_input($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = test_input($_POST["username"]);
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = test_input($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }

    // Validate email
    if(empty(test_input($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $sql = "SELECT id FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = test_input($_POST["email"]);
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This email is already taken.";
                } else{
                    $email = test_input($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }

    $uppercase = preg_match('@[A-Z]@', test_input($_POST["password"]));
    $lowercase = preg_match('@[a-z]@', test_input($_POST["password"]));
    $number    = preg_match('@[0-9]@', test_input($_POST["password"]));

    if(empty(test_input($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(test_input($_POST["password"])) < 8 || !$uppercase || !$lowercase || !$number){
        $password_err = "Password : 8 characters, uppercase (A-Z), number (0-9).";
    } else{
        $password = test_input($_POST["password"]);
    }
    
    if(empty(test_input($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = test_input($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (username, email, password, activation_code, user_status, token, notif)
                VALUES (:username, :email, :password, :activation_code, :user_status, :token, :notif)";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":activation_code", $param_activationCode, PDO::PARAM_STR);
            $stmt->bindParam(":user_status", $param_userStatus, PDO::PARAM_STR);
            $stmt->bindParam(":token", $param_token, PDO::PARAM_STR);
            $stmt->bindParam(":notif", $param_notif, PDO::PARAM_STR);

            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_activationCode = md5(rand(0,1000));
            $param_userStatus = 'not verified';
            $param_token = '';
            $param_notif = 1;
            if($stmt->execute()){

                $to      = $email; // Send email to our user
                $subject = 'Signup | Verification'; // Give the email a subject 
                $message = '

                Thanks for signing up!
                Your account Camagru has been created ! 
                You can login with the following credentials after you have activated your account by pressing the url below.

                ------------------------
                Username: '.$username.'
                Password: '.$password.'
                ------------------------

                Please click this link to activate your account:
                http://'.$_SERVER['HTTP_HOST'].'/user/activation.php?username='.$username.'&activationCode='.$param_activationCode.'

                '; 

                $headers = 'From:camagru@42.fr' . "\r\n"; 
                mail($to, $subject, $message, $headers); 
                $activation_mess = "Go check your email to activate your account";
            } else{
                $activation_mess = "Something went wrong. Please try again later";
            }
        }
        unset($stmt);
    }
    unset($pdo);
}
?>
<?php ob_start(); ?>

    <div class="loginForm" style="min-height:364px;">
        <h2 id="title2">Sign Up</h2>
        <p id="actMsg"><?php echo $activation_mess; ?></p><br>
        <form action="" method="post">
            <span><?php echo $username_err; ?></span>
            <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
            <span><?php echo $email_err; ?></span>
            <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
            <span><?php echo $password_err; ?></span>
            <input type="password" name="password" placeholder="Enter Password" value="<?php echo $password; ?>">
            <span><?php echo $confirm_password_err; ?></span>
            <input type="password" name="confirm_password" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>">
            <input type="submit" value="Register">
        </form>
    </div><br>
    <div class="loginForm">
            <p>Already have an account? <a href="login.php">Login here.</a></p>
    </div><br>

<?php $view = ob_get_clean(); ?>
<?php require("../template.php");?>