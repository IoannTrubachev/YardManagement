<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DTY Application</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../public/favicon.ico" />
    <!-- CSS -->
    <link rel="stylesheet" href="../public/css/reset.css" />
    <link rel="stylesheet" href="../public/css/style.css" />
	<link rel="stylesheet" href="../public/css/style2.css" />
	<link rel="stylesheet" href="../public/js/jquery-ui-1.11.2/jquery-ui.css" />
	
    <!-- JavaScript -->
    <script type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery-ui-1.11.2/jquery-ui.js"></script>
	<script type="text/javascript" src="../public/js/jquery.json.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery.ba-resize.min.js"></script>
	<script type="text/javascript" src="../public/js/application.js"></script>
	<script type="text/javascript" src="../public/js/garlic.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery-timing.min.js"></script>
	
	<!-- Define globals -->
	<script type="text/javascript">var plant = "<?php echo Session::get('user_location'); ?>",
								   URL = "<?php echo URL; ?>",
								   ACCOUNT = "<?php echo Session::get('user_account_type'); ?>",
								   NAME = "<?php echo Session::get('user_full_name'); ?>",
								   AVATAR = "<?php echo Session::get('user_avatar_file'); ?>";</script>
	
</head>
<body>
    <div class="header" <?php if ($this->checkForActiveControllerAndAction($filename, "report/report")) { echo ' style="display:none;" '; } ?>>			
        <div class="header_left_box">
        <ul  class="group menu">
			<div class="header_center_box">Dart Yard Management  &nbsp  </div>
			<li <?php if ($this->checkForActiveController($filename, "index")) { echo ' class="current_page_item" '; } ?> >
				<div class="header_clogo_box"><a href="<?php echo URL; ?>index/index"><img src="<?php echo URL . IMG_PATH; ?>" class="current_page_item" height="43px" width="80px" alt="Dart Yard Management"></a></div>
			</li>
			<div class="header_c_box"><?php  if(Session::get('user_location')) {echo Session::get('user_location'). " Complex";} ?></div>
            <li <?php if ($this->checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> >
            <li <?php if ($this->checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> >
                <a href="<?php echo URL; ?>index/index">Index</a>
            </li>
            <li <?php if ($this->checkForActiveController($filename, "help")) { echo ' class="active current_page_item" '; } ?> >
                <a href="<?php echo URL; ?>help/index">Help</a>
            </li>
            <li <?php if ($this->checkForActiveController($filename, "overview")) { echo ' class="active current_page_item" '; } ?> >
                <a href="<?php echo URL; ?>overview/index">Users</a>
            </li>
            <?php if (Session::get('user_logged_in') == true):?>
            <li <?php if ($this->checkForActiveController($filename, "dashboard")) { echo ' class="active current_page_item" '; } ?> >
                <a href="<?php echo URL; ?>dashboard/index">Dashboard</a>
            </li>
            <?php endif; ?>
            <?php if (Session::get('user_logged_in') == true):?>
            <li <?php if ($this->checkForActiveController($filename, "note")) { echo ' class="active current_page_item" '; } ?> >
                <a id="notes-notification" <?php echo Session::get('notes_public') <> 0 ? "data-notifications=\"" .Session::get('notes_public')."\"":"";?> href="<?php echo URL; ?>note/index">Notes</a>
            </li>
            <?php endif; ?>
			<?php if (Session::get('user_logged_in') == true):?>
            <li <?php if ($this->checkForActiveController($filename, "report")) { echo ' class="active current_page_item" '; } ?> >
                <a href="<?php echo URL; ?>report/index">Reports</a>
            </li>
            <?php endif; ?> 			
            <?php if (Session::get('user_logged_in') == true):?>
                <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active current_page_item" '; } ?> >
                    <a href="<?php echo URL; ?>login/showprofile">Account</a>
                 
                </li>
            <?php endif; ?>
			<?php if (Session::get('user_logged_in') == true && Session::get('user_account_type') == 3):?>
            <li <?php if ($this->checkForActiveController($filename, "admin")) { echo ' class="active current_page_item" '; } ?> >
                <a href="<?php echo URL; ?>admin/index">Admin</a>
            </li>
            <?php endif; ?>

            <!-- for not logged in users -->
            <?php if (Session::get('user_logged_in') == false):?>
                <li <?php if ($this->checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active current_page_item" '; } ?> >
                    <a href="<?php echo URL; ?>login/index">Login</a>
                </li>
                <li <?php if ($this->checkForActiveControllerAndAction($filename, "login/register")) { echo ' class="active current_page_item" '; } ?> >
                    <a href="<?php echo URL; ?>login/register">Register</a>
                </li>
            <?php endif; ?>
        </ul>
        </div>
		 
        <?php if (Session::get('user_logged_in') == true): ?>
            <div class="header_right_box">
                <div class="namebox">
                  <a href="<?php echo URL; ?>login/showprofile">  Hello <?php echo Session::get('user_full_name'); ?> !</a>
                </div>
                <div class="avatar">
                    <?php if (USE_GRAVATAR) { ?>
                      <a href="<?php echo URL; ?>login/showprofile"><div class="circular" style="background: url(<?php echo Session::get('user_gravatar_image_url'); ?>) no-repeat; width:<?php echo AVATAR_SIZE; ?>px; height:<?php echo AVATAR_SIZE; ?>px;"></div></a>
                    <?php } else { ?>
                        <a href="<?php echo URL; ?>login/showprofile"><div class="circular" style="background: url(<?php echo Session::get('user_avatar_file'); ?>) no-repeat; width:<?php echo AVATAR_SIZE; ?>px; height:<?php echo AVATAR_SIZE; ?>px;"></div></a>
                    <?php } ?>
					
                </div>
            </div>
        <?php endif; ?>
			<div class="header_cright_box"></div>
        <div class="clear-both"></div>
    </div>
