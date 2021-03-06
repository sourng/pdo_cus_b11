<?php 
$_SESSION['cart']=isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

  include_once('./db/dbconf.php');
  include_once('./class/class.slide.php');
  include_once('./class/class.course.php');
  include_once('./class/class.crud.php');


  // Create Object
    $objSlide=new slide($DB_con);
    $objCourse=new Course($DB_con);
    $crud=new Crud($DB_con);

// Create Object
// $crud=new Crud($DB_con);

// if(isset($_GET['id']))
//   {
//     $id = $_GET['id'];
//     extract($crud->getPages($id)); 
//   }
extract($crud->getPages(3));

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?></title>

    <?php include_once('inc/script.php');  ?>

  </head>

  <body>

    <!-- Navigation -->
  <?php include_once('inc/nav.php');  ?>

    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <h1 class="mt-4 mb-3"><?php echo $title; ?>
        <!-- <small>Subheading</small> -->
      </h1>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Home</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $title; ?></li>
      </ol>

      <!-- Image Header -->
      <img class="img-fluid rounded mb-4" src="uploads/img/<?php echo $image; ?>" alt="">

    
  <?php 
    include_once('inc/marketing.php');

  ?>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <!-- Footer -->
   <?php include_once('inc/footer.php'); ?>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  </body>

</html>
