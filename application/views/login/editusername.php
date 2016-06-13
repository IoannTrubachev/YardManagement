<div class="content">
    <h1>Change your username</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
<div class="center">
    <form action="<?php echo URL; ?>login/editusername_action" method="post">
        <label>New username</label>
       <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
        <input type="submit" value="Submit" />
    </form>
	</div>
</div>
