<div class="content">
    <h1>Edit your Name</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
<div class="center">
    <form action="<?php echo URL; ?>login/editfullname_action" method="post">
        <label>New Full Name</label>
       <input id="login_input_full_name" class="login_input" type="text" pattern="[a-zA-Z ]{2,64}" name="full_name" placeholder="<?php echo SESSION::get('user_full_name'); ?>" required />
        <input type="submit" value="Submit" />
    </form>
	</div>
</div>
