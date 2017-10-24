      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <ul class="sidebar-menu" id="nav-accordion">
              	  <p class="centered"><a href="profile.php"><img src="assets/img/users/<?php echo $uid; ?>.jpg" class="img-circle" width="60"></a></p>
              	  <h5 class="centered"><?php echo $userinfo['fname'] . " " . $userinfo['lname'];?></h5>
                  <li class="mt">
                      <a class="active" href="dashboard.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
				  <?php if($userinfo['usertype'] == "admin"){?>
				   <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Admin</span>
                      </a>
                      <ul class="sub">
                          <li><a href="errorlist.php"><i class="fa fa-exclamation-triangle"></i> Errors</a></li>
                      </ul>
                  </li>
				  <?php } ?>
              </ul>
          </div>
      </aside>
