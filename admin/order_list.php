<?php

  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if($_SESSION['role'] != 1) {
    header('Location: login.php');
  }

  // if(!empty($_POST['search'])) {
  //   setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
  // } else {
  //   if(empty($_GET['pageno'])) {
  //     unset($_COOKIE['search']);
  //     setcookie('search', null, -1, '/');
  //   }
  // }

?>

<?php
include('header.php');
?>
<!-- Main content -->
<div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Listings</h3>
                <div class="float-right d-none d-sm-inline">
                  <a href="logout.php" type="button" class="btn btn-dark">Logout</a>
                </div>
              </div>
              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else {
                  $pageno = 1;
                }

                $numOfrecs = 2;
                $offset = ($pageno - 1) * $numOfrecs;

                $pdostmt = $pdo -> prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                $pdostmt-> execute();
                $rawResult = $pdostmt->fetchAll();

                $total_pages = ceil(count($rawResult)/$numOfrecs);

                $pdostmt = $pdo -> prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfrecs");
                $pdostmt-> execute();
                $result = $pdostmt->fetchAll();

              ?>

              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="cat_add.php" type="button" class="btn btn-outline-success">Create New Category</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      if ($result) {
                        foreach ($result as $value) {
                     ?>
                     <?php
                      $userStmt = $pdo -> prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                      $userStmt-> execute();
                      $userResult = $userStmt->fetchAll();
                    ?>
                     <tr>
                       <td><?php echo $i; ?></td>
                       <td><?php echo escape($userResult[0]['name']) ?></td>
                       <td><?php echo escape($value['total_price']) ?></td>
                       <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                       <td>
                         <div class="btn-group">
                           <div class="container">
                             <a href="order_detail.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-default">View</a>
                           </div>
                         </div>
                       </td>
                     </tr>
                    <?php
                      $i++;
                        }

                      }

                    ?>
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float:right !important">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno<=1){ echo'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{ echo "?pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno>=$total_pages){ echo'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno>=$total_pages){echo '#';}else{ echo "?pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                  </ul>
                </nav>

              </div>
              <!-- /.card-body -->

            </div>

            <!-- /.card -->
          </div>
          <!-- /.col-md -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php include('footer.php') ?>
