<?php
session_start();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta charset="UTF-8">
    <title>Camagru</title>
    <link rel="icon" href="/public/icons/camagru_icon.png">
    <link rel="stylesheet" href="/public/css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Permanent+Marker|Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body>
<header>
    <table class="container"><tr>
        <td><a href="/camera.php"><img src="/public/icons/camera.png" alt="camera"></a></td>
        <td><a href="/index.php" id="Cam_logo" style="padding:0">
            <h1>Camagru</h1>
            <h1 id="C">C</h1>
        </a>
        <td><a href="/index.php" id="C" style="padding:0">
            <h1>C</h1>
        </a>
        </td>
        <td style="width:90%"></td>
        <?php
        if ($_SESSION['loggedin'] != ""){
            echo'
                
                <td style="width:10%"><a href="http://'.$_SERVER['HTTP_HOST'].'/user/account.php"><img src="/public/icons/user.png" alt="login"></a></td>
                <td style="width:10%"><a href="http://'.$_SERVER['HTTP_HOST'].'/user/logout.php"><img src="/public/icons/logout.png" alt="logout"></a></td>      
                ';
        }else{
            echo'
            <td style="width:10%"><a href="http://'.$_SERVER['HTTP_HOST'].'/user/login.php"><img src="/public/icons/user.png" alt="login"></a></td>
            <td style="width:10%"><a href="http://'.$_SERVER['HTTP_HOST'].'/user/register.php"><img src="/public/icons/signup.png" alt="register"></a></td>';
        }?>
    </tr></table>
</header>
<div class="main">
    <?= $view ?>
</div>
<div style="margin-bottom: 43px;"></div>
<div id="footer">
<p style="margin: 10px;">©scao 2019 - 42 Paris</p>
</div>
</body>
</html>