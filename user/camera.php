<?php
require("../config/database.php");
date_default_timezone_set('Europe/Paris');
session_start();

if(empty($_SESSION['loggedin']))
    header('Location: /user/login.php');

if ((isset($_POST['tookAphoto']))){
    $photo = $_POST['photo'];
    $sticker = $_POST['sticker'];
    $photo = explode(',', $photo);
    $data = base64_decode($photo[1]);
    $filePath = '../public/upload/'.date("YmdHis").'.png';
    file_put_contents($filePath, $data);
    $photoCopy = imagecreatefrompng($filePath);
    $stickerCopy = imagecreatefrompng($sticker);
    $resized_filter = imagecreatetruecolor(265, 250);
    $trans_color = imagecolorallocatealpha($resized_filter, 0, 0, 0, 127);
    imagefill($resized_filter, 0, 0, $trans_color);
    imagealphablending($stickerCopy, true);
    imagesavealpha($stickerCopy, true);
    $src_x = imagesx($stickerCopy);
    $src_y = imagesy($stickerCopy);
    imagecopyresampled($resized_filter, $stickerCopy, 0, 0, 0, 0, 265, 250, $src_x, $src_y);
    imagecopy($photoCopy, $resized_filter, 0, 0, 0, 0, 265, 250);
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
        header('location: /user/camera.php');
    }
}

?> 
<?php ob_start(); ?>
<div class="background">
    <div id="camera">
        <div id="video_div">
            <div id="live_video">
                <img src="../public/stickers/dog.png" id="overlay">
            </div>
            <video id="video"></video>
        </div>
        <div id="sticker_div">
            <img src="../public/stickers/dog.png" class="stickerImg active">
            <img src="../public/stickers/deer.png" class="stickerImg">
            <img src="../public/stickers/fries.png" class="stickerImg">
            <img src="../public/stickers/grumpy.png" class="stickerImg">
            <img src="../public/stickers/callme.png" class="stickerImg">
        </div>
        <form method="POST" action="" onsubmit=takePhoto();>
            <input id="photo" name="photo" type="hidden" value="">
            <input id="sticker" name="sticker" type="hidden" value="">
            <input id="snap" style="display:none;" type="submit"  name="tookAphoto" value="" >
        </form>

        <canvas style="display:none" id="canvas" width=640 height=480></canvas>
        <h2 id="title2">My images</h2>
        <div id='camera_gallery'>
        <?php
            $stmt = $pdo->prepare("SELECT img, id_img FROM picture WHERE id_user = :id_user ORDER BY date DESC");
            $stmt->bindParam(":id_user", $_SESSION['id']);
    		$stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $photos){
                echo "<img src='".$photos['img']."' id='photo' id='".$photos['id_img']."'>";
            }
        ?>
        </div>
        <div><p id="text-camera"><a style="color: #174873; font-weight: bold" href="/user/upload.php" hover="underline">Or upload your own picture!</a></p></div>
    </div>
</div><br/><br/>

<script src="../public/js/camera.js"></script>
<?php $view = ob_get_clean(); ?>
<?php require("../template.php"); ?>