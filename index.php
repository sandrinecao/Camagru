<?php 
require("config/database.php");


$PhotoPerPage = 9;
$query = $pdo->query('SELECT id_img FROM picture');
$AllPhotos = $query->rowCount();
$AllPages = ceil($AllPhotos/$PhotoPerPage);
if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $AllPages) {
   $_GET['page'] = intval($_GET['page']);
   $page = $_GET['page'];
} else {
   $page = 1;
}
$start = ($page-1) * $PhotoPerPage;



?>

<?php ob_start();?>
<?php 
    if (!empty($AllPhotos)){
?>
<div class="background galleryB">
    <div id="photoDisplay" >
        <?php
            $stmt = $pdo->query("SELECT picture.id_img, picture.img, picture.date, picture.likes, users.username, count(comments.id_img) AS nb_comment
                                FROM picture
                                LEFT JOIN comments ON (picture.id_img = comments.id_img) 
                                INNER JOIN users ON picture.id_user = users.id 
                                GROUP BY picture.id_img 
                                ORDER BY picture.date DESC 
                                LIMIT $start, $PhotoPerPage");
            foreach ($stmt as $photos){
        ?>
        <div id='img'>
            <?php
                session_start();
                if (empty($_SESSION['loggedin'])){
                    echo '
                        <a href="http://'.$_SERVER['HTTP_HOST'].'/user/login.php"><img src='.$photos['img'].' id='.$photos['id_img'].'></a>';
                } else{
                    echo '
                        <a href="http://'.$_SERVER['HTTP_HOST'].'/user/addLikeCom.php?id='.$photos['id_img'].'"><img src='.$photos['img'].' id='.$photos['id_img'].'></i></a>';
                }
            ?>
            <div id='buttons'>
            <?php
                if (empty($_SESSION['loggedin'])){
                    echo '
                        <a href="http://'.$_SERVER['HTTP_HOST'].'/user/login.php"><i class="fas fa-heart"></i>  '.$photos['likes'].'</a>
                        <a href="http://'.$_SERVER['HTTP_HOST'].'/user/login.php"><i class="fas fa-comment" ></i>  '.$photos['nb_comment'].'</a>';
                } else{
                    echo '
                        <a href="http://'.$_SERVER['HTTP_HOST'].'/user/addLikeCom.php?id='.$photos['id_img'].'"><i class="fas fa-heart"></i>  '.$photos['likes'].'</a>
                        <a href="http://'.$_SERVER['HTTP_HOST'].'/user/addLikeCom.php?id='.$photos['id_img'].'"><i class="fas fa-comment" ></i>  '.$photos['nb_comment'].'</a>';
                }
            ?>
            </div>
        </div>
        <?php
            }
         ?>
    </div>
</div>

 <div class="pagination">
    <?php
        if ($page > 1){
            echo '<a href="index.php?page='.($page-1).'" id="number">&#8249;</a> ';
        }
        for($i=1;$i<=$AllPages;$i++) {
            if ($AllPages == 1){
                echo '<a href="index.php?page='.$i.'" id="number"></a> ';
            }else{
                echo '<a href="index.php?page='.$i.'" id="number">'.$i.'</a> ';
            }
        }
        if ($page < $AllPages){
            echo '<a href="index.php?page='.($page+1).'" id="number">&#8250;</a> ';
        }
    ?>
    </div>
<?php
    }
?>
<?php 
     $view = ob_get_clean();
    require("template.php");
?>