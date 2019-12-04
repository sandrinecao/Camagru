<?php
require_once("../config/database.php");
session_start();

if(empty($_SESSION['loggedin']))
    header('Location: ../index.php');

$username = $email = $password = $confirm_password = "";
$error = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //username modifications
    if(empty(test_input($_POST["new_username"]))){
        $error = "Please enter a new username.";
    } else{
        $sql = "SELECT username FROM users WHERE username = :username";    
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", test_input($_POST["new_username"]), PDO::PARAM_STR);
            if($stmt->execute()){
                if($stmt->rowCount() == 1 && test_input($_POST["new_username"]) !== $_SESSION['username']){
                    $error = "This username is already taken.";
                } else{
                    $username = test_input($_POST["new_username"]);
                }
            }
        }
        unset($stmt);
    }
    if(!empty($username)){
        $update_user = "UPDATE users SET username = :username WHERE id = :id";
        $stmt = $pdo->prepare($update_user);
        $ok = $stmt->execute(array(
            ':username' => $username,
            ':id' => $_SESSION["id"]
        ));
        if ($ok){
            $message = "Your account has been updated";
            session_start();
            $_SESSION['username'] = $username;
            header("Refresh: 2; url=account.php");
        }else{
            $message = "Something went wrong :(";
        }
        unset($stmt);
    }
    //email modifications
    if(empty(test_input($_POST["new_email"]))){
        $error = "Please enter a new email.";
    } else{
        $sql = "SELECT email FROM users WHERE email = :email";    
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", test_input($_POST["new_email"]), PDO::PARAM_STR);
            if($stmt->execute()){
                if($stmt->rowCount() == 1 && test_input($_POST["new_email"]) !== $_SESSION['email']){
                    $error = "This email is already taken.";
                } else{
                    $email = test_input($_POST["new_email"]);
                }
            }
        }
        unset($stmt);
    }
    if(!empty($email)){
        $update_email = "UPDATE users SET email = :email WHERE id = :id";
        $stmt = $pdo->prepare($update_email);
        $ok = $stmt->execute(array(
            ':email' => $email,
            ':id' => $_SESSION["id"]
        ));
        if ($ok){
            $message = "Your account has been updated";
            session_start();
            $_SESSION['email'] = $email;
            header("Refresh: 2; url=account.php");
        }else{
            $message = "Something went wrong :(";
        }
        unset($stmt);
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
                    <div class="loginForm accountForm EdProf">
                        <h2 id="subTitle">Edit your profile</h2>
                        <form action="" method="post">
                            <span style="color:green;    margin-top: 34px;"><?php echo $message; ?></span>
                            <span style="color:red;    margin-top: 34px;"><?php echo $error; ?></span>
                            <input type="text" name="new_username" placeholder="New Username" value="<?php echo $_SESSION["username"]; ?>">
                            <input type="email" name="new_email" placeholder="New Email" value="<?php echo $_SESSION['email']; ?>">
                            <span></span>
                            <input type="submit" id="saveBtt" value="Update" name="save">
                        </form>
                    </div><br>
                </div>
            </article>
        </div>
    </div>
</div>

<?php $view = ob_get_clean(); ?>
<?php require("../template.php"); ?>