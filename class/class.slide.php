<?php

/**
 * Create class for slide name slide
 */
 class slide
 {
 	private $db;


 	function __construct($DB_con)
 	{
 		$this->db=$DB_con; 		
 	}

// Function for get Slide Data
 	public function getSlide($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();

 		if($stmt->rowCount()>0){

 			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
 				 // print($row['slide_id']);
 				 // print($row['title']);
 				 // print($row['description']);
 				 // print($row['img']); 			
 				?>
 				<!-- Slide Three - Set the background image for this slide in the line below -->
          <div class="carousel-item" style="background-image: url('./uploads/slide/<?php print($row['img']); ?>')">
            <div class="carousel-caption d-none d-md-block">
              <h3><?php print($row['title']); ?></h3>
              <p><?php print($row['description']); ?></p>
            </div>
          </div>

 				<?php
 			}

 		}else{
 			echo "Nothing";
 		}

 	} // End Function getSlide



// Function for get Slide Data
 	public function getMarketing($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();

 		if($stmt->rowCount()>0){

 			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
 				 			
 				?>
 				<div class="col-lg-4 mb-4">
				    <div class="card h-100">
				      <h4 class="card-header"><?php print($row['title']); ?></h4>
				      <div class="card-body">
				        <p class="card-text"><?php print($row['description']); ?></p>
				      </div>
				      <div class="card-footer">
				        <a href="#" class="btn btn-primary">Learn More</a>
				      </div>
				    </div>
				  </div>

 				<?php
 			}

 		}else{
 			echo "Nothing";
 		}

 	} // End Function GetMarketing





 } // End class slide 


?>