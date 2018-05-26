<?php

/**
 * Create class for Course
 */
 class Course
 {
 	private $db;


 	function __construct($DB_con)
 	{
 		$this->db=$DB_con; 		
 	}


// Function for get Slide Data
 	public function getCourse($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();

 		if($stmt->rowCount()>0){
 			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){				 		?>
			 <div class="col-lg-4 col-sm-6 portfolio-item">
			    <div class="card h-100">
			      <a href="#"><img class="card-img-top" src="./uploads/course/<?php echo $row['image']; ?>" alt="<?php echo $row['image']; ?>"></a>
			      <div class="card-body">
			        <h4 class="card-title">
			          <a href="#"><?php echo $row['course_title']; ?></a>
			        </h4>
			        <p class="card-text"><?php echo $row['course_description']; ?></p>
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