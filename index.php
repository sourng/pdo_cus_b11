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


    extract($crud->getPages(1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home:Project at CUS</title>

 <?php include_once('inc/script.php');  ?>
 
  <link rel="stylesheet" type="text/css" href="engine1/style.css" />
  <script type="text/javascript" src="engine1/jquery.js"></script>



</head>
<body>
 
    <!-- Navigation -->
    <?php  
      // include_once('folder/filename');
      include_once('inc/nav.php');
    ?>

    <!-- Code for slide -->
  <?php
    include_once('inc/slide.php');
  ?>


<!-- Page Content -->
<div class="container">

<h1 class="my-4">Welcome to CUS Project</h1>

<!-- Marketing Icons Section -->
  <?php 
    include_once('inc/marketing.php');

  ?>
<!-- /.row -->

<!-- Portfolio Section -->
<h2>កំរិត​បណ្តុះបណ្តាល​</h2>

<?php 
  include_once('inc/courses.php');
?>
<!-- /.row -->

<!-- Features Section -->
<?php  include_once('inc/feature.php'); ?>
<!-- /.row -->

<hr>

<!-- Call to Action Section -->
<?php include_once('inc/call_action.php');  ?>
<!-- /.container -->



    <!-- Footer -->
   <?php include_once('inc/footer.php'); ?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript" src="engine1/wowslider.js"></script>
  <script type="text/javascript" src="engine1/script.js"></script>



</body>
</html>