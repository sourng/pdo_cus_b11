<?php 
$_SESSION['cart']=isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

include_once('db/dbconf.php');
include_once('class/class.crud.php');

// Create Object
$crud=new Crud($DB_con);

// if(isset($_GET['id']))
//   {
//     $id = $_GET['id'];
//     extract($crud->getPages($id)); 
//   }
extract($crud->getPages(2));

?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $detail; ?>">
    <meta name="author" content="SENG Sourng">

    <title><?php echo $title; ?></title>
    <?php include_once('inc/script.php');  ?>

  </head>

  <body>

    <!-- Navigation -->
<?php include_once('inc/nav.php'); ?>

    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <h1 class="mt-4 mb-3"><?php echo $title; ?>
        <!-- <small>Subheading</small> -->
      </h1>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.html">Home</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $title; ?></li>
      </ol>

      <!-- Intro Content -->
      <div class="row">
        <div class="col-lg-6">
          <img class="img-fluid rounded mb-4" src="./uploads/img/<?php echo $image; ?>" alt="">
        </div>
        <div class="col-lg-6">
          <h2><?php echo $description; ?></h2>
          <?php echo $detail ; ?>
        </div>
      </div>
      <!-- /.row -->

      <!-- Team Members -->
      <h2>ក្រុម​របស់​យើង​</h2>

      <div class="row">
        <?php 

        $sql="SELECT * FROM team";
        $crud->getTeam($sql);

        ?>

      </div>
      <!-- /.row -->

      <!-- Our Customers -->
      <h2>អតិថិជន​របស់​យើង​</h2>
      <div class="row">
        <div class="col-lg-2 col-sm-4 mb-4">
          <img class="img-fluid" src="http://placehold.it/500x300" alt="">
        </div>
        <div class="col-lg-2 col-sm-4 mb-4">
          <img class="img-fluid" src="http://placehold.it/500x300" alt="">
        </div>
        <div class="col-lg-2 col-sm-4 mb-4">
          <img class="img-fluid" src="http://placehold.it/500x300" alt="">
        </div>
        <div class="col-lg-2 col-sm-4 mb-4">
          <img class="img-fluid" src="http://placehold.it/500x300" alt="">
        </div>
        <div class="col-lg-2 col-sm-4 mb-4">
          <img class="img-fluid" src="http://placehold.it/500x300" alt="">
        </div>
        <div class="col-lg-2 col-sm-4 mb-4">
          <img class="img-fluid" src="http://placehold.it/500x300" alt="">
        </div>
      </div>
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
