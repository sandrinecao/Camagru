<?php
session_start();
require("../config/database.php");

if(empty($_SESSION['loggedin']))
    header('Location: ../index.php');

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$old_password = test_input($_POST['old_password']);
$new_password = test_input($_POST['new_password']);
$confirm_password = test_input($_POST['new_confirm_password']);

$password_err = $confirm_password_err = $error = "";
$message = $message_err = "";

$uppercase = preg_match('@[A-Z]@', $new_password);
$lowercase = preg_match('@[a-z]@', $new_password);
$number    = preg_match('@[0-9]@', $new_password);

if (isset($_POST["change_pwd"])){
    if (isset($old_password)){
        $sql = "SELECT password FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
        $hashed_pwd = $stmt->fetch();
        if(password_verify($old_password, $hashed_pwd['password'])){
            if(empty($new_password)){
                $password_err =  "Please enter a new password";
            }elseif(strlen($new_password) < 8 || !$uppercase || !$lowercase || !$number){
                $password_err = "Password : 8 characters, uppercase (A-Z), number (0-9)";
            }elseif (empty($confirm_password)){
                    $confirm_password_err =  "Please confirm password.";     
            } else{
                if(empty($password_err) && ($new_password != $confirm_password)){
                    $confirm_password_err =  "Password did not match.";
                }
            } if(empty($password_err) && empty($confirm_password_err)){
                $new_password1 = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pass = "UPDATE users SET password = :password WHERE username = :username";
                $stmt = $pdo->prepare($update_pass);
                $stmt->execute(array(
                    ':password' => $new_password1,
                    ':username' => $_SESSION['username']
                ));
                $message = "Your password has been changed.";
            } else{
                $message_err = "Your password wasn't changed";
            }
        }else {
            $error = "Your old password is incorrect";
        }
    }
}
?>
<?php ob_start(); ?>
<div class="background galleryB">
    <div id="test">
        <div id="account">
            <nav id="account_nav">
                <a id="EdPro" href="account.php">Edit Profile</a>
                <a id="EdPwd" href="modifyPassw.php">Edit Password</a>
                <a id="DelPho" href="deletePhotos.php" >Delete Photos</a>
                <a id="DelAcc" href="deleteAccount.php" >Delete Account</a>
                <a id="Notif" href="notifications.php" >Notifications</a>
            </nav>
            <article>
                <div style="max-height: 705px;" id="a">
                    <div class="loginForm accountForm ModPwd">      
                        <h2 id="subTitle">Change password</h2>
                        <form action="" method="post">
                            <span style="color:green"><?php echo $message; ?></span>
                            <span style="color:red"><?php echo $message_err; echo $error; ?></span>
                            <input type="password" name="old_password" placeholder="Old Password" value="" required>
                            <span><?php echo $password_err; echo $confirm_password_err;?></span>
                            <input type="password" name="new_password" placeholder="New Password" value="">
                            <input type="password" name="new_confirm_password" placeholder="Confirm New Password" value="">
                            <input type="submit" id="saveBtt" value="Update" name="change_pwd">
                        </form>
                    </div><br>
                </div>
            </article>
        </div>
    </div>
</div>
<?php $view = ob_get_clean(); ?>
<?php require("../template.php"); ?>