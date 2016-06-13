<div class="content">
    <h1>Change your email adress</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
<div class="center">
    <form action="<?php echo URL; ?>login/edituseremail_action" method="post">
        <label>New email address:</label>
        <input id="login_input_email" class="login_input" type="email" name="user_email" required />
        <input type="submit" value="Submit" />
    </form>
	</div>
</div>
