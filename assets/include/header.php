<?php
					$uid = $_SESSION['cust_id'];
					$countstores = "SELECT id FROM store_data";
					$storeresult = $test_db->query($countstores);
					$numstores = $storeresult->num_rows;
					$countstores = "SELECT id FROM store_data WHERE user_id = '$uid'";
					$storeresult = $test_db->query($countstores);
					$custnumstores = $storeresult->num_rows;
					$countprod = "SELECT id FROM product_store_data";
					$prodresult = $test_db->query($countprod);
					$numprod = $prodresult->num_rows;
					$countprod = "SELECT id FROM product_store_data WHERE uid = '$uid'";
					$prodresult = $test_db->query($countprod);
					$custnumprod = $prodresult->num_rows;
					$checkpoints = "SELECT * FROM pointsystem WHERE cust_id = '$uid'";
					$pointsresult = $test_db->query($checkpoints);
					$pointsresult = $pointsresult->fetch_assoc();
					$userpoints = $pointsresult['points'];
					$getmessage = "SELECT * FROM message_system WHERE touid = '$uid'";
					$messageresult = $test_db->query($getmessage);
					$nummessage = $messageresult->num_rows;
					$uid = $_SESSION['cust_id'];
					$getinfo = "SELECT * FROM cust_data WHERE id = '$uid'";
					$userinfo = $test_db->query($getinfo);
					$userinfo = $userinfo->fetch_assoc();
					?>
<header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <a href="#" class="logo"><b>Go. Price. Shop.</b></a>
            <div class="nav notify-row" id="top_menu">
                <ul class="nav top-menu">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-tasks"></i>
                            <span class="badge bg-theme">4</span>
                        </a>
                        <ul class="dropdown-menu extended tasks-bar">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have 1 pending tasks</p>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info">
                                        <div class="desc">DashGum Admin Panel</div>
                                        <div class="percent">40%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="external">
                                <a href="#">See All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-theme"><?php echo $nummessage;?></span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have <?php echo $nummessage; if($nummessage==1){echo " new message";}else{echo " new messages";}?></p>
                            </li>
							<?php $result_array = array();
							while($results = $messageresult->fetch_array()) {
									$result_array[] = $results;
								}
								$i = 0;
								foreach ($result_array as $result1) {
									if (++$i == 5) break;
									$fromuid = $result1['fromuid'];
									$getinfo = "SELECT * FROM cust_data WHERE id = '$fromuid'";
									$userresult = $test_db->query($getinfo);
									$userresult = $userresult->fetch_assoc();
									$fromfname = $userresult['fname'];
									$fromlname = $userresult['lname'];
									$timeago = $result1['timestamp'];
									echo '<li><a href="#">';
									echo '<span class="photo"><img alt="avatar" src="assets/img/users/'.$userresult["id"].'.jpg"></span>';
									echo '<span class="subject">';
									echo '<span class="from">'.$fromfname.' '.$fromlname.'</span>';
									echo '<span class="time need_to_be_rendered" datetime="'.$timeago.'">'.$timeago.'</span>';
									echo '</span>';
									echo '<span class="message">'.$result1['message'].'</span>';
									echo '</a></li>';
								} ?>
                            <li>
                                <a href="#">See all messages</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="assets/include/logout.php">Logout</a></li>
            	</ul>
            </div>
        </header>
