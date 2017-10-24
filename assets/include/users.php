<?php
	global $test_db;
	$query = "SELECT * FROM cust_data WHERE signedin = 'TRUE' ORDER BY id ASC LIMIT 5";
	$result_array = array();
	// The search
	$result = $test_db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}
	
	foreach ($result_array as $result) {
		// The output			
			$timeago = $result['timestamp'];
			$fname = $result['fname']. " ";
			$lname = substr($result['lname'],0,1) . ".";
			$userstatus = $result['userstatus'];
			$uid = $result['id'];
			
			echo'<div class="desc">';
            echo'<div class="thumb">';
            echo'<img class="img-circle" src="assets/img/users/'.$uid.'.jpg" width="35px" height="35px" align="">';
            echo'</div>';
            echo'<div class="details">';
            echo'<p><a href="#">'.$fname.$lname.'</a><br/>';
            echo'<muted>'.$userstatus.'</muted><br/><span class="time need_to_be_rendered" datetime="'.$timeago.'">'.$timeago.'</span>';
            echo'</p>';
            echo'</div>';
            echo'</div>';
	}
	
	if(empty($result_array)){
		echo'<div class="desc">';
            echo'<div class="thumb">';
            echo'<img class="img-circle" src="assets/img/users/nobody.png" width="35px" height="35px" align="">';
            echo'</div>';
            echo'<div class="details">';
            echo'<p>No one is available.<br/>';
            echo'<muted>Unavailable</muted>';
            echo'</p>';
            echo'</div>';
            echo'</div>';
	}
?>