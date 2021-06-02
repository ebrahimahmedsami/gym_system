<?php

    ob_start();
    session_start();
    if (isset($_SESSION['username'])) {
        include 'init.php';

        $do = isset($_GET['do']) ?  $_GET['do'] : 'manage';
        $query = isset($_GET['query']) ?  $_GET['query'] : 1;

        if ($do == 'manage') {
             //manage users page
             //select all users

             $stmt = $con->prepare("SELECT * FROM users WHERE gender = $query");
             $stmt->execute();
             $row = $stmt->fetchAll();
             
             ?>

            <h1 class="text-center">إدارة المشتركين</h1>
            <h5 class="text-center">
                <?php if ($query == 1) {
                   echo 'الأولاد';
                }else{
                    echo 'البنات';
                } ?>
            </h5>
            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-dark">
                        <thead>
                            <th>التسلسل</th>
                            <th>الاسم</th>
                            <th>الموبايل</th>

                            <th>بداية الإشتراك</th>
                            <th>نهاية الإشتراك</th>
                            <th>اشتراك الشهر</th>
                            <th>المدفوع</th>
                            <th>المتبقي</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </thead>
                        <?php 
                        
                        foreach ($row as $val) {
                           ?>
                            <tr>
                                <td><?php echo $val['id']; ?></td>
                                <td><?php echo $val['name']; ?></td>
                                <td><?php echo $val['phone']; ?></td>

                                <td><?php echo $val['start_date']; ?></td>
                                <td><?php echo $val['end_date']; ?></td>
                                <td><?php echo $val['month_money']; ?></td>
                                <td><?php echo $val['mony_paided']; ?></td>
                                <td><?php echo $val['mony_left']; ?></td>
                                <td><?php
                                if ($val['status'] == 0 || $val['end_date'] < date("Y-m-d")) {
                                    echo '<span style="color:red;font-weight:bold;">لم يدفع</span>';
                                }else{
                                    echo '<span style="color:green;font-weight:bold;">دفع</span>';
                                }
                                 
                                  ?></td>
                                <td>
                                    <a href="member.php?do=sedit&sid=<?php echo $val['id']; ?>" class="btn btn-success"><i class="fa fa-edit"></i> تعديل</a>
                                    <a href="member.php?do=sdelete&sid=<?php echo $val['id']; ?>" class="btn btn-danger confirm"><i class="fa fa-trash-alt"></i> مسح</a>
                                </td>
                            </tr>
                           <?php
                        }
                        
                        ?>
                    </table>
                </div>

                <a href="member.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i>إضافة مشترك جديد</a>
            </div>

             <?php
            ?>
            <?php
            
        }elseif($do == 'add'){
            //add user page
            echo "<h1 class=\"text-center\">إضافة مشترك</h1>";
            ?>

                <div class="container">
                    <form class="form-horizontal" action="?do=insert" method="POST">
                    <input type="hidden" name="sid">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">الاسم</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="name" class="form-control" autocomplete="off" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">الموبايل</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="phone" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">بداية الإشتراك</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="date" name="startdate" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">نهاية الإشتراك</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="date" name="enddate" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">إشتراك الشهر</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="monthmoney" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">المبلغ المدفوع</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="paided" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">المبلغ المتبقي</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="left" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">النوع</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="s-first" type="radio" name="gender" value="1" checked> 
                                    <label for="s-first">ولد</label>
                                </div>
                                <div>
                                    <input id="s-second" type="radio" name="gender" value="2"> 
                                    <label for="s-second">بنت</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="إضافة مشترك" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>

            <?php

        }elseif($do == 'insert'){

            echo "<h1 class=\"text-center\">تسجيل مشترك</h1>";
                
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //get variables from the form
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $startdate = $_POST['startdate'];
                $enddate = $_POST['enddate'];
                $monthmoney = $_POST['monthmoney'];
                $paided = $_POST['paided'];
                $left = $_POST['left'];
                $gender = $_POST['gender'];
                $status = 0;

                if ($left > 0) {
                    $status = 0;
                }else{
                    $status = 1;
                }

                //check if user exist in database
                $check = checkItem("name","users",$name);

                if ($check == 0) {

                //insert new user into database
                $stmt = $con->prepare("INSERT INTO users (name, phone, start_date, end_date, month_money, mony_paided, mony_left, status, gender)
                VALUES (:name, :phone, :startdate, :enddate, :monthmoney, :paided, :left, :status, :gender)");
                $stmt->execute(array(
                    'name' => $name,
                    'phone' => $phone,
                    'startdate' => $startdate,
                    'enddate' => $enddate,
                    'monthmoney' => $monthmoney,
                    'paided' => $paided,
                    'left' => $left,
                    'status' => $status,
                    'gender' => $gender
                )); 

                echo '<div class="alert alert-success">تمت إضافة مشترك بنجاح</div>';
                header("refresh:2;url=home.php");
                exit();
                }else{
                    echo '<div class="alert alert-danger">هذا المشترك موجود بالفعل</div>';
                }
            }else{
                redirectHome("you can not proceed this page directly",5);
            }


        }elseif($do == 'sedit'){
            //update user informayions
            $sid =  isset($_GET['sid']) && is_numeric($_GET['sid']) ? intval($_GET['sid']) : 0;
            
            $stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute(array($sid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0) { ?>
        
                <h1 class="text-center">تعديل بيانات المشترك</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=supdate" method="POST">
                    <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                    <div class="form-group">
                            <label class="col-sm-2 control-label">الاسم</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" autocomplete="off" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">الموبايل</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="phone" value="<?php echo $row['phone']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">بداية الإشتراك</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="date" name="startdate" value="<?php echo $row['start_date']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">نهاية الإشتراك</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="date" name="enddate" value="<?php echo $row['end_date']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">إشتراك الشهر</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="monthmoney" value="<?php echo $row['month_money']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">المبلغ المدفوع</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="paided" value="<?php echo $row['mony_paided']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">المبلغ المتبقي</label>
                            <div class="col-sm-10 col-lg-4">
                                <input type="number" name="left" value="<?php echo $row['mony_left']; ?>" class="form-control" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">النوع</label>
                            <div class="col-sm-10 col-lg-4">
                                <div>
                                    <input id="s-first" type="radio" name="gender" value="1" checked> 
                                    <label for="s-first">ولد</label>
                                </div>
                                <div>
                                    <input id="s-second" type="radio" name="gender" value="2"> 
                                    <label for="s-second">بنت</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="حفظ" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
        
       <?php 
            }else{
                redirectHome("لا يوجد رقم تسلسل",4);
            } 


        }elseif($do == 'supdate'){
            //update users page
            echo "<h1 class=\"text-center\">User informations</h1>";
            echo '<div class="container">';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               //get variables from the form
               $id = $_POST['sid'];
               $name = $_POST['name'];
               $phone = $_POST['phone'];
               $startdate = $_POST['startdate'];
               $enddate = $_POST['enddate'];
               $monthmoney = $_POST['monthmoney'];
               $paided = $_POST['paided'];
               $left = $_POST['left'];
               $gender = $_POST['gender'];
               $status = 0;

               if ($left > 0) {
                   $status = 0;
               }else{
                   $status = 1;
               }

                    //update user the database
                    $stmt = $con->prepare("UPDATE users SET name = ?,phone = ?,start_date = ?,end_date = ?,
                    month_money = ?,mony_paided = ?,mony_left = ?, status = ?, gender = ? WHERE id = ? LIMIT 1");
                    $stmt->execute(array($name,$phone,$startdate,$enddate,$monthmoney,$paided,$left,$status,$gender,$id));
                    echo '<div class="alert alert-success">تم تعديل بيانات المشترك بنجاح</div>';
                    ?>
                    <ul class="list-group">
                        <li class="list-group-item active" aria-current="true"><strong>بيانات المشترك</strong></li>
                        <li class="list-group-item"><strong>الاسم : </strong><?php echo $name; ?></li>
                        <li class="list-group-item"><strong>التليفون : </strong><?php echo $phone; ?></li>
                        <li class="list-group-item"><strong>بداية الإشتراك : </strong><?php echo $startdate; ?></li>
                        <li class="list-group-item"><strong>نهاية الإشتراك : </strong><?php echo $enddate; ?></li>
                        <li class="list-group-item"><strong>إشتراك الشهر : </strong><?php echo $monthmoney; ?></li>
                        <li class="list-group-item"><strong>الدفوع : </strong><?php echo $paided; ?></li>
                        <li class="list-group-item"><strong>المتبقي : </strong><?php echo $left; ?></li>
                        <li class="list-group-item"><strong>النوع : </strong><?php
                         if ($gender == 1) {
                            echo 'ولد';
                         }else{
                             echo 'بنت';
                         }
                         
                          ?></li>
                    </ul>

                    <?php  
                             

            }else{
                redirectHome("لا يسمح بفتح هذه الصفحة مباشرة");
            }
            echo '</div>';

        }elseif($do == 'sdelete'){
            //delete user page
            ?>
                <h1 class="text-center">مسح مشترك</h1>
                <div class="container">
            <?php

                $sid =  isset($_GET['sid']) && is_numeric($_GET['sid']) ? intval($_GET['sid']) : 0;
                $check = checkItem("id","users",$sid);
                

                if ($check > 0) {
                    //delete user from database
                    $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute(array($sid));
                    echo '<div class="alert alert-success">تم مسح المشترك بنجاح</div>';
                    header("refresh:2;url=home.php");
                    exit();
                }else{
                    redirectHome("هذا الرقم التسلسلي ليس موجود",4);
                }

            ?>
                </div>
            <?php
        }

        include $tpl . 'footer.php';
    
    }else{
        header('Location: index.php');
        exit();
    }
ob_end_flush();
?>