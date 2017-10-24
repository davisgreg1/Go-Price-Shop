<?php
	global $test_db;
	$query = "SELECT * FROM (SELECT * FROM errorlog ORDER BY id DESC) sub ORDER BY id ASC";
	$result_array = array();
	// The search
	$result = $test_db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}
	
	foreach ($result_array as $result) {
		$errornum = $result['errorcode'];
		if($errornum == ""){
			$errordef = $result['errormessage'];
		} else {
		$errordef = ${'error'.$errornum};
		}
		// The output
			echo '<tr>';
			echo '<td class="text-left">'.$result['timestamp'].'</td>';
			echo '<td>'.$result['filename'].'</td>';
			echo '<td class="text-left">'.$errordef.'</td>';
			echo '<td class="text-left">IP: '.$result['ipadd'] . ' Proxy: '.$result['ipproxy'].'</td>';
			echo '<td class="text-left"><a href="php/delete-error.php?id='.$result['id'].'">Delete</a></td>';
			echo '</tr>';
	}
	
	if(empty($result_array)){
		echo '<tr>';
		echo '<td class="text-left">No errors at this time.</td>';
		echo '<td></td>';
		echo '<td class="text-left"></td>';
		echo '<td class="text-left"></td>';
		echo '<td class="text-left"></td>';
		echo '</tr>';
	}
?>