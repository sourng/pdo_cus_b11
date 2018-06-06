<?php 
$_SESSION['cart']=isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

 include_once('./db/dbconf.php');
  include_once('./class/class.crud.php');
  $crud=new Crud($DB_con);
extract($crud->getPages(4));
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



<!-- Facebook Chat -->
<div class="fb-customerchat"
 page_id="sengsourng"
 minimized="true">
</div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '1664205676971856',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.11'
    });
  };
(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>



<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution="setup_tool"
  page_id="1914879295465655"
  logged_in_greeting="Hi! How can we help you?"
  logged_out_greeting="Hi! How can we help you?">
</div>


</body>
</html>