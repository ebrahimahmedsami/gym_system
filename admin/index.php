<?php 
    session_start();
    $nonavbar = '';
    $pageTitle = 'Login';

    if(isset($_SESSION['username'])){
        header('Location: home.php');
    }
    include "init.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        $stmt = $con->prepare("SELECT admin_id, admin_username, admin_pass FROM cov_admin
                                 WHERE admin_username = ? AND admin_pass = ? LIMIT 1");
        $stmt->execute(array($username,$hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        //if count > 0 then database contains record about this username
        if ($count > 0) {
           $_SESSION['username'] = $username;
           $_SESSION['ID'] = $row['admin_id'];
           header('Location: home.php');
           exit();
        }
    }
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h4 class="text-center">Fitness GYM</h4>
    <input class="form-control" type="text" name="user" placeholder="اسم المستخدم" autocomplete="off" />
    <input class="form-control" type="password" name="pass" placeholder="كلمة المرور" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="دخول">
</form>


<?php include $tpl . "footer.php"; ?>
