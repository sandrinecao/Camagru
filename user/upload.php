<?php
require("../config/database.php");
date_default_timezone_set('Europe/Paris');
session_start();
$error_1 = '';
$error_2 = '';
$error_3 = '';
if (empty($_SESSION['loggedin']))
    header('Location: /user/login.php');

if (isset($_POST['submit']))
{
    $maxsize = 500000;
    $format = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');

    if ($_FILES['uploadPic']['size'] >= $maxsize) {
        $error_1 = 'Sorry, your file is too large. 500 KB max.';
        header("Refresh: 2");
    } elseif ($_FILES['uploadPic']['size'] == 0) {
        $error_2 = 'Invalid File';
        header("Refresh: 2");
    } elseif (!in_array($_FILES['uploadPic']['type'], $format)) {
        $error_3 = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        header("Refresh: 2");
    } else {
        $photo = $_POST['photo'];
        $sticker = $_POST['sticker'];
        $photo = explode(',', $photo);
        $data = base64_decode($photo[1]);
        $filePath = '../public/upload/' . date("YmdHis") . '.png';
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
        foreach ($profile as $user) {
            $addPhoto = "INSERT INTO picture (id_user, img) VALUE ('" . $user['id'] . "', '" . $filePath . "')";
            $pdo->query($addPhoto);
            header('location: /user/camera.php');
        }
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
            <img id='upload_img' width=100% height=100% />
        </div>
        <div id="sticker_div">
            <img src="../public/stickers/dog.png" class="stickerImg active">
            <img src="../public/stickers/deer.png" class="stickerImg">
            <img src="../public/stickers/fries.png" class="stickerImg">
            <img src="../public/stickers/grumpy.png" class="stickerImg">
            <img src="../public/stickers/callme.png" class="stickerImg">
        </div>

        <form action="" method="POST" enctype="multipart/form-data" id="upload" onsubmit="uploadPhoto()">
            <label for="uploadPic">Load file</label>
            <input type="file" name="uploadPic" id="uploadPic" style="display:none">
            <input id="photo" name="photo" type="hidden" value="">
            <input id="sticker" name="sticker" type="hidden" value="">
            <button type="submit" name="submit" id="uploadBtt" value="">Save</button>
            <span><?php echo $error_1; ?></span><br />
            <span><?php echo $error_2; ?></span><br />
            <span><?php echo $error_3; ?></span><br />
        </form>

        <canvas style="display:none" id="canvas" width=640 height=480></canvas>
        <h2 id="title2">My images</h2>
        <div id='camera_gallery'>
            <?php
            $stmt = $pdo->prepare("SELECT img, id_img FROM picture WHERE id_user = :id_user ORDER BY date DESC");
            $stmt->bindParam(":id_user", $_SESSION['id']);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($res as $photos) {
                echo "<img src='" . $photos['img'] . "' id='photo' id='" . $photos['id_img'] . "'>";
            }
            ?>
        </div>
    </div><br /><br />
    <script src="../public/js/upload.js"></script>
    <?php $view = ob_get_clean(); ?>
    <?php require("../template.php"); ?>