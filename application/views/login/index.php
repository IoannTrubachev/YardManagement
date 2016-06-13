<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="login-default-box">
        <h1 class="centerText">Login</h1>
        <form action="<?php echo URL; ?>login/login" method="post">
                <label>Username (or email)</label>
                <input type="text" class="big" name="user_name" autofocus required />
                <label>Password</label>
                <input type="password" name="user_password" required />
                <input type="checkbox" name="user_rememberme" class="remember-me-checkbox" />
                <label class="remember-me-label">Keep me logged in (for 2 weeks)</label>
				<br>
				<br>
                <input type="submit" class="login-submit-button" />
        </form>
        <a href="<?php echo URL; ?>login/register">Register</a>
        |
        <a href="<?php echo URL; ?>login/requestpasswordreset">Forgot my Password</a>
    </div>


</div>
