<?php 
    ob_start();

    session_start();
    if(isset($_SESSION['username'])){
        
        include 'init.php';

        $boys = countItems('name','users WHERE gender = 1');
        $girls = countItems('name','users WHERE gender = 2');


        ?>

        <div class="container text-center paid">
            <h1 class="text-center">الصفحة الرئيسية</h1>
            <div class="row">
                <div class="col-sm-4">
                    <a href="member.php?do=add" class="btn btn-warning"><i class="fa fa-plus"></i> إضافة مشترك جديد</a>
                </div>
                <div class="col-sm-2">
                    <div class="paided1">
                        <h5><i class="fa fa-users"></i> الأولاد </h5>
                        <span><a href="member.php?do=manage&query=1"><?php echo $boys; ?></a></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="paided1">
                        <h5><i class="fa fa-users"></i> البنات </h5>
                        <span><a href="member.php?do=manage&query=2"><?php echo $girls; ?></a></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out-alt"></i>  خروج</a>
                </div>
            </div>
        </div>

        <?php

        include $tpl . "footer.php";

    }else{
        header('Location: index.php');
        exit();
    }

    ob_end_flush();
?>