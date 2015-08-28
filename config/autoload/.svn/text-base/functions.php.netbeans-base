<?php
   

    function doPages($page_size, $thepage, $query_string, $total=0, $cur_page) {// echo 'test'; exit;
        
        //per page count
        $index_limit = 10;

        //set the query string to blank, then later attach it with $query_string
        $query='';
        
        if(strlen($query_string)>0){
            $query = $query_string;
        }
        
        //get the current page number example: 3, 4 etc: see above method description
        $current = $cur_page;
        
        $total_pages=ceil($total/$page_size);
        $start=max($current-intval($index_limit/2), 1);
        $end=$start+$index_limit-1;

        echo '<div id="navcontainer"><ul >';

        if($current==1) {
            echo '<li><a>Previous</a></li>';
        } else {
            $i = $current-1;
            echo '<li><a  href="'.$thepage.'page/'.$i.$query.'" class="prn" rel="nofollow" title="go to page '.$i.'">Previous</a></li>';
            //echo '<span class="prn">...</span>&nbsp;';
        }

        if($start > 1) {
            $i = 1;
            echo '<li><a href="'.$thepage.'page/'.$i.$query.'" title="go to page '.$i.'">'.$i.'</a></li>';
        }

        for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
            if($i==$current) {
                echo '<li class="active"><a>'.$i.'</a></li>';
            } else {
                echo '<li><a href="'.$thepage.'page/'.$i.$query.'" title="go to page '.$i.'">'.$i.'</a></li>';
            }
        }

        if($total_pages > $end){
            $i = $total_pages;
            echo '<li><a href="'.$thepage.'page/'.$i.$query.'" title="go to page '.$i.'">'.$i.'</a></li>';
        }

        if($current < $total_pages) {
            $i = $current+1;
            //echo '<span class="prn">...</span>&nbsp;';
            echo '<li><a href="'.$thepage.'page/'.$i.$query.'" class="prn" rel="nofollow" title="go to page '.$i.'">Next</a></li>';
        } else {
            echo '<li><a>Next</a></li>';
        }
        
        //if nothing passed to method or zero, then dont print result, else print the total count below:
        if ($total != 0){
            //prints the total result count just below the paging
            echo '</ul></div><div class="clr"></div><p id="total_count" style="width:100%; text-align:center;"><strong>(Total '.$total.' Records)</strong></p>';
        }
        
    }//end of method doPages()
	
	
