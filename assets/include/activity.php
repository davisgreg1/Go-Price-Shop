<?php
	global $test_db;
	$cust_id = 1;
	$query = "SELECT * FROM activitylog WHERE cust_id = '$cust_id' ORDER BY timestamp DESC LIMIT 5";
	$result_array = array();
	// The search
	$result = $test_db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}
	
	foreach ($result_array as $result) {
		// The output
			$statuscode = $result['statuscode'];
			$statusdefinition = ${'status'.$statuscode};
			if($result['store_id'] != ""){
				$store_id = $result['store_id'];
			} else {
				$store_id = "";
			}
			
			$timeago = $result['timestamp'];			
			echo'<div class="desc">';
            echo'<div class="thumb">';
            echo'<span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>';
            echo'</div>';
            echo'<div class="details">';
            echo'<p><span class="time need_to_be_rendered" datetime="'.$timeago.'">'.$timeago.'</span><br/>';
            echo $statusdefinition.$store_id.'<br/>';
            echo'</p>';
            echo'</div>';
            echo'</div>';
	}
	
	if(empty($result_array)){
		echo '<tr>';
			echo '<td class="small">No activity at the moment.</td>';
			echo '<td class="small"></td>';
			echo '</tr>';
	}
?>