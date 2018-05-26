<?php

/**
 * Create class for Course
 */
 class Crud
 {
 	private $db;


 	function __construct($DB_con)
 	{
 		$this->db=$DB_con; 		
 	}


// Function for get GetBlog
 	public function getBlog($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();

 		if($stmt->rowCount()>0){
 			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){				 		?>
			 <!-- Blog Post -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <a href="post.php?id=1">
                <img class="img-fluid rounded" src="uploads/blog/<?php echo $row['image'];  ?>" alt="">
              </a>
            </div>
            <div class="col-lg-6">
              <h2 class="card-title"><?php echo $row['title']; ?></h2>
              <p class="card-text"><?php echo $row['description']; ?></p>
              <a href="post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Read More &rarr;</a>
            </div>
          </div>
        </div>
        <div class="card-footer text-muted">
          <?php echo $row['create_date'];  ?>
          <a href="#"> <?php echo $row['last_name'];  ?></a>
        </div>
      </div>

 				<?php
 			}

 		}else{
 			echo "Nothing";
 		}

 	} // End Function GetBlog





 } // End class slide 


?>