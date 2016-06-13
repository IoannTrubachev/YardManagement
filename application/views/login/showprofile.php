<div class="content">
    <h1>Your profile</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
<div class="centerTable">
	<table border="0">
		<tr>
			<td>
			 <?php // if usage of gravatar is activated show gravatar image, else show local avatar ?>
				<?php if (USE_GRAVATAR) { ?>
					<a href="<?php echo URL; ?>login/uploadavatar"> <div class="circular" style="background: url(<?php echo Session::get('user_gravatar_image_url'); ?>) no-repeat; width:<?php echo AVATAR_SIZE; ?>px; height:<?php echo AVATAR_SIZE; ?>px;"></div></a>
				<?php } else { ?>
					<a href="<?php echo URL; ?>login/uploadavatar"> <div class="circular" style="background: url(<?php echo Session::get('user_avatar_file'); ?>) no-repeat; width:<?php echo AVATAR_SIZE; ?>px; height:<?php echo AVATAR_SIZE; ?>px;"></div></a>
				<?php } ?>
			</td>
			<td>
				<a href="<?php echo URL; ?>login/editfullname"><?php echo Session::get('user_full_name'); ?></a>
			</td>
		</tr>
		<tr>
			<td>
				 Username: 
			</td>
			<td>
				<a href="<?php echo URL; ?>login/editusername"><?php echo Session::get('user_name'); ?></a>
			</td>
		</tr>
		<tr>
			<td>
				 Email:
			</td>
			<td>
				<a href="<?php echo URL; ?>login/edituseremail"><?php echo Session::get('user_email'); ?></a>
			</td>
		</tr>
		<tr>
			<td>
				Account:
			</td>
			<td>
				<?php if (Session::get('user_account_type')== 1) {
					echo ' Shipping Coordinator/ Supervisor';
				} elseif (Session::get('user_account_type') == 2) {
					echo ' Dispatcher';
				} else {
					echo ' Administrator';
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				Location:
			</td>
			<td>
				<?php echo Session::get('user_location'); ?>
			</td>
		</tr>
	</table>
</div>

	<div class="centerText">
		<h1>Menu</h1> 
					<ul class="profile-menu">
						<?php if (Session::get('user_account_type') >= 2) { echo '
						 <li>
                            <a href="' .URL. 'login/changelocation">Change my Location</a>
                        </li><br>'; } ?>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/uploadavatar">Upload an avatar</a>
                        </li><br>
						<li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/verifypasswordreset/<?php echo Session::get('user_name'); ?>/<?php echo Session::get('user_password_reset_hash'); ?>">Change Password</a>
                        </li><br>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/editfullname">Edit my full name</a>
                        </li><br>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/edituseremail">Edit my email</a>
                        </li><br>
                        <li <?php if ($this->checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                            <a href="<?php echo URL; ?>login/logout">Logout</a>
                        </li><br>
                    </ul>
	</div>

	
</div>
