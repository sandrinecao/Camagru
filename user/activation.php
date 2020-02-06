<?php
require("../config/database.php");

$message = '';
$user_status = '';

if(isset($_GET['activationCode']) && isset($_GET['username'])){
    $query = "SELECT * FROM users WHERE username = :username AND activation_code = :activation_code";
    $statement = $pdo->prepare($query);
    $statement->execute(array(
        ':username'   => $_GET['username'],
        ':activation_code'   => $_GET['activationCode']
    ));
    $result = $statement->fetchAll();
    foreach($result as $row){
        $user_status = $row['user_status'];
    }
    if ($user_status == 'not verified') {    
        $update_query = "UPDATE users SET user_status = 'verified' WHERE username = :username";
        $statement = $pdo->prepare($update_query);
        $statement->execute(array(
            ':username' => $_GET['username'],
        ));
            if(isset($result)){
                $message = '<span style="color:green">Successfully Verified</span>';
                }
            }
    elseif ($user_status == 'verified'){
                $message = '<span style="color:blue">Already Verified</span>';
    }
    elseif ($user_status == 0){
        $message = '<span style="color:darkred">Invalid Link</span>';
     } else {
        header("Location: ../index.php");
    }
}
?>
    
<?php ob_start();?>
    <div style="min-height:250px;">
       <div class="loginForm" style="border:none;background-color:transparent">
            <h2 id="title2">Activate your account</h2>
            <p><?php echo $message; ?></p>
            <input type="submit" value="Login" 
           onclick="window.location='login.php'" />
        </div>
    </div>
<?php $view=ob_get_clean();?>
<?php require("../template.php");?>