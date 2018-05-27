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

 	// Get Detail page
 	public function getBlogDetail($id)
	{
		$stmt = $this->db->prepare("SELECT b.*,u.last_name FROM blog as b INNER JOIN users as u ON b.user_id=u.id WHERE b.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
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
              <a href="post.php?id=<?php echo $row['id']; ?>">
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



// Paging Function
 	public function paging($query,$records_per_page)
	{
		$starting_position=0;
		if(isset($_GET["page_no"]))
		{
			$starting_position=($_GET["page_no"]-1)*$records_per_page;
		}
		$query2=$query." limit $starting_position,$records_per_page";
		return $query2;
	}

public function paginglink($query,$records_per_page)
	{
		
		$self = $_SERVER['PHP_SELF'];
		
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		
		$total_no_of_records = $stmt->rowCount();
		
		if($total_no_of_records > 0)
		{
			?><ul class="pagination"><?php
			$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
			$current_page=1;
			if(isset($_GET["page_no"]))
			{
				$current_page=$_GET["page_no"];
			}
			if($current_page!=1)
			{
				$previous =$current_page-1;
				echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=1'>First</a></li>";
				echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$previous."'>Previous</a></li>";
			}
			for($i=1;$i<=$total_no_of_pages;$i++)
			{
				if($i==$current_page)
				{
					echo "<li class='page-item active'><a class='page-link' href='".$self."?page_no=".$i."' style='color:white;'>".$i."</a></li>";
				}
				else
				{
					echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$i."'>".$i."</a></li>";
				}
			}
			if($current_page!=$total_no_of_pages)
			{
				$next=$current_page+1;
				echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$next."'>Next</a></li>";
				echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
			}
			?></ul><?php
		}
	}	

 } // End class CRUD 


?>