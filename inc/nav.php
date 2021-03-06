<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">CUS Projects</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'about.php'){echo 'nav-link active'; }else { echo 'nav-link'; } ?>" href="about.php">អំពីយើង</a>
            </li>         

            <li class="nav-item">
              <a class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'services.php'){echo 'nav-link active'; }else { echo 'nav-link'; } ?>" href="services.php">Services</a>
            </li>
            <li class="nav-item">
              <a class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'contact.php'){echo 'nav-link active'; }else { echo 'nav-link'; } ?>" href="contact.php">Contact</a>
            </li>
            <li class="nav-item">
              <a class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'blog.php'){echo 'nav-link active'; }else { echo 'nav-link'; } ?>" href="blog.php">Blog</a>
            </li>
            <li class="nav-item">
              <a class="<?php if(basename($_SERVER['SCRIPT_NAME']) == 'product.php'){echo 'nav-link active'; }else { echo 'nav-link'; } ?>" href="product.php">Products</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Courses
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                <a class="dropdown-item" href="portfolio-1-col.html">1 Column Portfolio</a>
                <a class="dropdown-item" href="portfolio-2-col.html">2 Column Portfolio</a>
                <a class="dropdown-item" href="portfolio-3-col.html">3 Column Portfolio</a>
                <a class="dropdown-item" href="portfolio-4-col.html">4 Column Portfolio</a>
                <a class="dropdown-item" href="portfolio-item.html">Single Portfolio Item</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Blog
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
                <a class="dropdown-item" href="blog-home-1.html">Blog Home 1</a>
                <a class="dropdown-item" href="blog-home-2.html">Blog Home 2</a>
                <a class="dropdown-item" href="blog-post.html">Blog Post</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Other Pages
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
                <a class="dropdown-item" href="full-width.html">Full Width Page</a>
                <a class="dropdown-item" href="sidebar.html">Sidebar Page</a>
                <a class="dropdown-item" href="faq.html">FAQ</a>
                <a class="dropdown-item" href="404.html">404</a>
                <a class="dropdown-item" href="pricing.html">Pricing Table</a>
              </div>
            </li>
          
		  </ul>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
 
                <!-- highlight if $title has 'Products' word. -->
                <li <?php echo $title=="Products" ? "class='active'" : ""; ?>>
                    <a href="product.php" class="dropdown-toggle">Products</a>
                </li>
 
                <li <?php echo $title=="Cart" ? "class='active'" : ""; ?> >
                    <a href="cart.php">
                        <?php
                        // count products in cart
                        $cart_count=count($_SESSION['cart']);
                        ?>
                        Cart <span class="badge" id="comparison-count"><?php echo $cart_count; ?></span>
                    </a>
                </li>
            </ul>
 
        </div><!--/.nav-collapse -->

      </div>
    </nav>
