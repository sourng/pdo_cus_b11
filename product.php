<?php 
$_SESSION['cart']=isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

 include_once('./db/dbconf.php');
  include_once('./class/class.crud.php');
  $crud=new Crud($DB_con);
extract($crud->getPages(9));
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($title) ? $title : "Sourng Products";?> - Pages</title>
    <?php include_once('inc/script.php');  ?>


    <style>
  #fb-root > div.fb_dialog.fb_dialog_advanced.fb_shrink_active {
     right: initial !important;
     left: 18pt;
     z-index: 9999999 !important;
  }
  .fb-customerchat.fb_invisible_flow.fb_iframe_widget iframe {
     right: initial !important;
     left: 18pt !important;
  }
</style>
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
      <h2 class="mt-4 mb-3"><?php echo isset($title) ? $title : "Sourng Products";?>
        <!-- <small>Subheading</small> -->
      </h2>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Home</a>
        </li>
        <li class="breadcrumb-item active"><?php echo isset($title) ? $title : "Sourng Products";?></li>
      </ol>
      <div class="row">
        <?php 
          $sqlPro="SELECT p.*, pimg.name as img FROM products as p INNER JOIN product_images as pimg ON p.id=pimg.product_id";
          $row=$crud->get_by_sql($sqlPro);
          foreach ($row as $rows) {
            $id=$rows['id'];
            $name=$rows['name'];
            $image=$rows['img'];
            $price=$rows['price'];
            $description=$rows['description'];

            echo "<div class='col-md-3' style='margin-bottom:15px;'> 
                  <img style='width:100%; background-color:#0161ac;padding:15px;' src='./uploads/product/$image'/>

             <h5><strong>$name</strong></h5>

             <a class='btn btn-primary' href='#?p=$id'>Add to Cart</a>

            </div>" ; 
          }

        ?>
      </div>

      



        




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


<script>
$(document).ready(function(){
    // add to cart button listener
    $('.add-to-cart-form').on('submit', function(){
 
        // info is in the table / single product layout
        var id = $(this).find('.product-id').text();
        var quantity = $(this).find('.cart-quantity').val();
 
        // redirect to add_to_cart.php, with parameter values to process the request
        window.location.href = "add_to_cart.php?id=" + id + "&quantity=" + quantity;
        return false;
    });
});
</script>



</body>
</html>