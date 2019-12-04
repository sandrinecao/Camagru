<?php
require("config/database.php");
date_default_timezone_set('Europe/Paris');
session_start();

if(empty($_SESSION['loggedin']))
    header('Location: /user/login.php');

if (isset($_POST['submit']))
{
    $photo = $_POST['photo'];
    $sticker = $_POST['sticker'];
    $photo = explode(',', $photo);
    $data = base64_decode($photo[1]);
    $filePath = 'public/upload/'.date("YmdHis").'.png';
    file_put_contents($filePath, $data);


    $photoCopy = imagecreatefrompng($filePath);
    $stickerCopy = imagecreatefrompng($sticker);
    $resized_mask = imagecreatetruecolor(265, 250);
    $trans_color = imagecolorallocatealpha($resized_mask, 0, 0, 0, 127);
    imagefill($resized_mask, 0, 0, $trans_color);
    imagealphablending($stickerCopy, true);
    imagesavealpha($stickerCopy, true);
    $src_x = imagesx($stickerCopy);
    $src_y = imagesy($stickerCopy);
    imagecopyresampled($resized_mask, $stickerCopy, 0, 0, 0, 0, 265, 250, $src_x, $src_y);
    imagecopy($photoCopy, $resized_mask, 0, 0, 0, 0, 265, 250);
    imagepng($photoCopy, $filePath);
    imagedestroy($photoCopy);
    
    $username = ($_SESSION['username']);
    $sql = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $sql->bindParam(":username", $username);
	$sql->execute();
    $profile = $sql->fetchAll();
    foreach ($profile as $user){
        $addPhoto = "INSERT INTO picture (id_user, img) VALUE ('".$user['id']."', '".$filePath."')";
        $pdo->query($addPhoto);
        header('location: camera.php');
    }
}
?>

<?php ob_start(); ?>
<div class="background">
    <div id="camera">
        <div id="video_div">
            <div id="live_video">
                <img src="public/stickers/dog.png" id="overlay">
            </div> 
            <img id='upload_img' width=100% height=100%/>
        </div>
        <div id="sticker_div">
            <img src="public/stickers/dog.png" class="stickerImg active">
            <img src="public/stickers/deer.png" class="stickerImg">
            <img src="public/stickers/fries.png" class="stickerImg">
            <img src="public/stickers/grumpy.png" class="stickerImg">
            <img src="public/stickers/callme.png" class="stickerImg">
        </div>

        <form action="" method="POST" enctype="multipart/form-data"  id="upload" onsubmit="uploadPhoto()">
            <label for="uploadPic">Load file</label>
            <input type="file" name="uploadPic" id="uploadPic" style="display:none" accept="image/*">
            <input id="photo" name="photo" type="hidden" value="">
            <input id="sticker" name="sticker" type="hidden" value="">
            <button type="submit" name="submit" id="uploadBtt" value="">Save</button>
        </form>

        <canvas style="display:none" id="canvas" width=640 height=480></canvas>
    </div>
    <canvas style="display:none" id="canvasCopy" width="640" height="480"></canvas>
</div><br/><br/>
<script src="/public/js/upload.js"></script>
<?php $view = ob_get_clean(); ?>
<?php require("template.php"); ?>