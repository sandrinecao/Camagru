<?php
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../index.php");
}
 
require_once("../config/database.php");
 
$username = $password = "";
$username_err = $password_err = "";
$activation_mess = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(test_input($_POST["username"]))){
        $username_err = "Please enter your username.";
    } else{
        $username = test_input($_POST["username"]);
    }
    
    if(empty(test_input($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = test_input($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT * FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = $username;
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetchAll()){
                        $id = $row[0]["id"];
                        $username = $row[0]["username"];
                        $hashed_password = $row[0]["password"];
                        $email = $row[0]["email"];
                        if ($row[0]["user_status"] == 'verified'){
                            if(password_verify($password, $hashed_password)){
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;                            
                                $_SESSION["email"] = $email;                            
                                header("location: ../camera.php");
                            } else{
                                $password_err = "The password you entered is not valid.";
                            }
                        }else{
                            $activation_mess = "This account hasn't been verified yet. Please go check your email.";
                        }
                    }
                } else{
                    $username_err = "No account found with this username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
    unset($pdo);
}

?>
<?php ob_start(); ?>
<div style="max-height: 705px;" id="a">
    <div class="loginForm" style="min-height:364px;">      
        <form action="" method="post">
            <h2 id="title2">Login</h2>
            <p id="actMsg"><?php echo $activation_mess; ?></p><br>
            <span><?php echo $username_err; ?></span>
            <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
            <span><?php echo $password_err; ?></span>
            <input type="password" name="password" placeholder="Password">
            <input type="submit"value="Login">
            <p>Can't remember your password? <a href="forgotPassword.php"><font color="green">Click here!</font></a></p>
        </form>
    </div><br>
    <div class="loginForm">
        <p style="text-align:center">Don't have an account? <a href="register.php">Join us now!</a></p>
    </div><br>
</div>
<?php $view = ob_get_clean(); ?>
<?php require("../template.php"); ?>