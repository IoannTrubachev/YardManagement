<div class="content centerAlign">
    <h1>Change account type</h1>
    <p>
       Change user  <span style="font-weight: bold;"><?php echo '<td>'.$this->user->user_full_name.'</td>'; ?></span> account type.
	  
	   </p>
	   <p>
	   <h2>Account Levels:</h2>
		<ul>
				<li>1 - Shipping Coordinators/ Supervisors</li>
				<li>2 - Dispatchers</li>
				<li>3 - Administrators </li>
		</ul>
    </p>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <h2>Currently the account type is: <?php   echo '<td> '.$this->user->user_account_type.'</td>'; ?></h2>
    <!-- basic implementation for two account type: type 1 and type 2 -->

	    <form action="<?php echo URL; ?>login/changeaccounttype/<?php echo $this->user->user_id;?>" method="post">
        <label><input type="hidden" name="id" value="<?php echo $this->user->user_id;?>"></label>
        <input type="submit" name="user_account_upgrade_admin" value="Upgrade account to Admin" />
    </form>
	
    <form action="<?php echo URL; ?>login/changeaccounttype/<?php echo $this->user->user_id?>" method="post">
        <label><input type="hidden" name="id" value="<?php echo $this->user->user_id;?>"></label>
        <input type="submit" name="user_account_upgrade" value="Upgrade account to Dispatcher" />
    </form>

    <form action="<?php echo URL; ?>login/changeaccounttype/<?php echo $this->user->user_id;?>" method="post">
        <label><input type="hidden" name="id" value="<?php echo $this->user->user_id;?>"></label>
        <input type="submit" name="user_account_downgrade" value="Change account to Shipping Coordinator/Supervisor" />
    </form>

</div>
