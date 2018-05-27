<?php 
 include_once('./db/dbconf.php');
  include_once('./class/class.crud.php');
  $crud=new Crud($DB_con);

?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modern Business - Start Bootstrap Template</title>
    <?php include_once('inc/script.php');  ?>
  </head>

  <body>

   <!-- Navigation -->
   <?php  
      // include_once('folder/filename');
      include_once('inc/nav.php');
    ?>
    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <h2 class="mt-4 mb-3">ពត៌មាន​របស់​យើង
        <!-- <small>Subheading</small> -->
      </h2>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.html">Home</a>
        </li>
        <li class="breadcrumb-item active">ពត៌មាន​របស់​យើង</li>
      </ol>

        <?php 
          $sql="SELECT b.*,u.last_name FROM blog as b INNER JOIN users as u
ON b.user_id=u.id";

        $records_per_page=3;
        $newquery = $crud->paging($sql,$records_per_page);
        $crud->getBlog($newquery);
         // $crud->getBlog($sql);

        ?>
    

     <!-- Pagination -->
      <ul class="pagination justify-content-center mb-4">
        <?php $crud->paginglink($sql,$records_per_page); ?>
      </ul>

    </div>

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <?php include_once('inc/footer.php'); ?>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>
</html>