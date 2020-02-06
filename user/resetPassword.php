<?php
require("../config/database.php");

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    return $data;
}

$password_err = $confirm_password_err = $message = $err_invalid = "";
$password = $confirm_password = "";

if(isset($_GET['username'])){
    $query = $pdo->prepare('SELECT token FROM users WHERE username = :username');
    $query->bindParam(':username', $_GET['username']);
    $query->execute();
    $token = $query->fetch(PDO::FETCH_ASSOC);
    if ($token['token'] != $_GET['reset']){
        $err_invalid = 'Invalid link. You will be redirected to the homepage.';
        header("Refresh: 3; url=../index.php");
    }
}else{
    header("Location: ../index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $uppercase = preg_match('@[A-Z]@', test_input($_POST["password"]));
    $lowercase = preg_match('@[a-z]@', test_input($_POST["password"]));
    $number    = preg_match('@[0-9]@', test_input($_POST["password"]));
    if(isset($_POST['resetPasswordForm'])){
        if(empty(test_input($_POST['password']))){
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
        $query = $pdo->prepare('SELECT token FROM users WHERE token = :token');
        $query->bindParam(':token', $_POST['reset']);
        $query->execute();
        $tokenExists = $query->fetch(PDO::FETCH_ASSOC);
        if ($tokenExists['token']){
            if(empty($password_err) && empty($confirm_password_err)){
                $new_password = password_hash($password, PASSWORD_DEFAULT);
                $update_pass = "UPDATE users SET password = :password WHERE token = :token";
                $stmt = $pdo->prepare($update_pass);
                $stmt->execute(array(
                    ':password' => $new_password,
                    ':token' => $_POST['reset']
                ));
                $message = "Your password has been changed. You'll be sooon redirected to login page";
                header("Refresh: 5; url=login.php");
            }else{
                $message_err = "Your password wasn't changed.";
            }
        }
    }$pdo = null;
}
?>

<?php ob_start();?>

<div class="loginForm">
    <h2 id="title2">Reset password</h2>
    <p style="color:green;"><?php echo $message; ?></p>
    <p style="color:red;"><?php echo $err_invalid; ?></p>
        <form action="" method="post">
            <input type="password" name="password" placeholder="New Password" value="<?php echo $password; ?>">
            <span><?php echo $password_err; ?></span>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" value="<?php echo $confirm_password; ?>">
            <span><?php echo $confirm_password_err; ?></span>
            <input type="hidden" name="reset" value="<?php if(isset($_GET['reset'])){echo($_GET['reset']);}?>">
            <input type="submit" value="Reset Password" name="resetPasswordForm">
        </form>
</div><br/>    
<?php $view=ob_get_clean();?>
<?php require("../template.php");?>