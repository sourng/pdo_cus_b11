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
          for($i=1;$i<4;$i++){
            ?>
              <!-- Blog Post -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <a href="post.php?id=1">
                <img class="img-fluid rounded" src="uploads/img/php.jpg" alt="">
              </a>
            </div>
            <div class="col-lg-6">
              <h2 class="card-title">ចំណង​ជើង​ពត៌មាន​របស់​យើង <?php echo $i; ?></h2>
              <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis!</p>
              <a href="post.php?id=1" class="btn btn-primary">Read More &rarr;</a>
            </div>
          </div>
        </div>
        <div class="card-footer text-muted">
          Posted on January 1, 2018 by
          <a href="#">SENG Sourng</a>
        </div>
      </div>


            <?php
          }

        ?>
    

      <!-- Pagination -->
      <ul class="pagination justify-content-center mb-4">
        <li class="page-item">
          <a class="page-link" href="#">&larr; Older</a>
        </li>
        <li class="page-item disabled">
          <a class="page-link" href="#">Newer &rarr;</a>
        </li>
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