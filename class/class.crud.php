<?php
$_SESSION['cart']=isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
/**
 * Create class for Course
 */
 class Crud
 {
 	private $db;
 	private $conn;

 	private $table_name_pro="products";

 	private $table_name_img = "product_images";
 
    // object properties
    public $id;
    public $product_id;
    public $name;
    public $timestamp;


 	function __construct($DB_con)
 	{
 		$this->db=$DB_con; 
 		$this->conn=$DB_con;		
 	}

// Work on Cart

 	// read the first product image related to a product
function readFirst(){
 
    // select query
    $query = "SELECT id, product_id, name
            FROM " . $this->table_name_pro . "
            WHERE product_id = ?
            ORDER BY name DESC
            LIMIT 0, 1";
 
    // prepare query statement
    $stmt = $this->db->prepare( $query );
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind prodcut id variable
    $stmt->bindParam(1, $this->product_id);
 
    // execute query
    $stmt->execute();
 
    // return values
    return $stmt;
}


// read all products
function read($from_record_num, $records_per_page){
 
    // select all products query
    $query = "SELECT
                id, name, description, price 
            FROM
                " . $this->table_name_pro . "
            ORDER BY
                created DESC
            LIMIT
                ?, ?";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind limit clause variables
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
 
    // execute query
    $stmt->execute();
 
    // return values
    return $stmt;
}
 
// used for paging products
public function count(){
 
    // query to count all product records
    $query = "SELECT count(*) FROM " . $this->table_name_pro;
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // execute query
    $stmt->execute();
 
    // get row value
    $rows = $stmt->fetch(PDO::FETCH_NUM);
 
    // return count
    return $rows[0];
}


// ================================== End Work on Cart ==================



 	// Function for get data by sql
 	public function get_by_sql($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();
 		// return($stmt);	 

 		if($stmt->rowCount()>0){ 		
	 		// $stmt->fetch(PDO::FETCH_ASSOC);
	 		return($stmt);	 		
 		}else{
 			echo "Nothing";
 		}

 	} // End Function get all data by sql


 	// Get Detail page
 	public function getBlogDetail($id)
	{
		$stmt = $this->db->prepare("SELECT b.*,u.last_name FROM blog as b INNER JOIN users as u ON b.user_id=u.id WHERE b.id=:id");
		$stmt->execute(array(":id"=>$id));
		$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $editRow;
	}

// Get each Page
 	public function getPages($page_id)
	{
		$stmt = $this->db->prepare("SELECT * FROM page WHERE page_id=:page_id");
		$stmt->execute(array(":page_id"=>$page_id));
		$getPage=$stmt->fetch(PDO::FETCH_ASSOC);
		return $getPage;
	}


	// Function for get Slide Data
 	public function getSlide($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();

 		if($stmt->rowCount()>0){ 			
 			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){	
 				?>
		<li><img src="uploads/slide/<?php  print($row['img']); ?>" alt="<?php  print($row['img']); ?>" title="<?php print($row['title']); ?>" id="<?php  print($row['id']); ?>"/>
		</li>
		<?php
 			}
 		}else{
 			echo "Nothing";
 		}

 	} // End Function getSlide


// Function for getTeam
 	public function getTeam($SQL_Query){

 		$stmt=$this->db->prepare($SQL_Query);
 		$stmt->execute();

 		if($stmt->rowCount()>0){ 			
 			while($row=$stmt->fetch(PDO::FETCH_ASSOC)){	
 				
 				?>
		<div class="col-lg-4 mb-4">
          <div class="card h-100 text-center">
            <img style="border-radius: 50%;" class="card-img-top" src="./uploads/team/<?php  print($row['image']); ?>" alt="">
            <div class="card-body">
              <h4 class="card-title"><?php  print($row['name']); ?></h4>
              <h6 class="card-subtitle mb-2 text-muted">
              	<?php  print($row['position']); ?>             		
              	</h6>
              	<h6 class="card-subtitle mb-2 text-muted">
              	<?php  print($row['phone']); ?>             		
              	</h6>
              <p class="card-text"><?php  print($row['description']); ?></p>
            </div>
            <div class="card-footer">
              <a href="<?php  print($row['fb']); ?>">FB</a>
              <a href="<?php  print($row['tw']); ?>">TW</a>
              <a href="<?php  print($row['gp']); ?>">GP</a>
            </div>
          </div>
        </div>

	
		<?php
 			}
 		}else{
 			echo "Nothing";
 		}

 	} // End Function getTeam


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