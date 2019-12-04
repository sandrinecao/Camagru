<?php
require("../config/database.php");

$message = '';

if(isset($_GET['activationCode'])){
    $query = "SELECT * FROM users WHERE activation_code = :activation_code";
    $statement = $pdo->prepare($query);
    $statement->execute(array(':activation_code'   => $_GET['activationCode']));
    $no_of_row = $statement->rowCount();

    if($no_of_row > 0)
    {
        $result = $statement->fetchAll();
        foreach($result as $row){
            if($row['user_status'] == 'not verified'){     
                $update_query = "UPDATE users SET user_status = 'verified' WHERE username = :username";
                $statement = $pdo->prepare($update_query);
                $sub_result = $statement->fetchAll();
                $statement->execute(array(':username' => $_GET['username']));
                if(isset($sub_result)){
                    $message = '<span style="color:green">Successfully Verified</span>';
                }
            }else{
                $message = '<span style="color:blue">Already Verified</span>';
            }
        }
    }else{
        $message = '<span style="color:darkred">Invalid Link</span>';
    }
}else{
    header("Location: ../index.php");
}
?>

<?php ob_start();?>
<div style="min-height:250px;">
   <div class="loginForm" style="border:none;background-color:transparent">
        <h2 id="title2">Activate your account</h2>
        <h3 style="text-align:center"><?php echo $message; ?></h3>
        <input type="submit" value="Login" 
       onclick="window.location='login.php'" />
    </div>
</div>
<?php $view=ob_get_clean();?>
 <?php require("../template.php");?>