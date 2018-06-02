<header>
  <div id="wowslider-container1">
      <div class="ws_images"><ul>
      <?php 
        $sql="SELECT * FROM slide WHERE status=1";

        // $records_per_page=3;
        // $newquery = $crud->paging($sql,$records_per_page);
        // $crud->getBlog($newquery);
         $crud->getSlide($sql);
          ?>

          </ul>
      </div>
        </div>
    </header>