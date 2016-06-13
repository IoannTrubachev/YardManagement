<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="register-default-box">
        <h1>Register</h1>
        <!-- register form -->
        <form data-persist="garlic" method="post" action="<?php echo URL; ?>login/register_action" name="registerform">
            <!-- the user name input field uses a HTML5 pattern check -->
            <label for="login_input_username">
                Username
                <span style="display: block; font-size: 14px; color: #999;">(Only letters and numbers, 2 to 64 characters)</span>
            </label>
            <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
			 <!-- the full name input field uses a HTML5 pattern check -->
			 <label for="login_input_fullname">
                Full Name
                <span style="display: block; font-size: 14px; color: #999;">(only letters, First and Last Name)</span>
            </label>
            <input id="login_input_fullname" class="login_input" type="text" pattern="[a-zA-Z ]{2,64}" name="user_full_name" required />
            <!-- the email input field uses a HTML5 email type check -->
            <label for="login_input_email">
                Email
                <span style="display: block; font-size: 14px; color: #999;">
                    (please provide a <span style="text-decoration: underline; color: mediumvioletred;">real email address</span>,
                    you'll get a verification mail with an activation link)
                </span>
            </label>
            <input id="login_input_email" class="login_input" type="email" name="user_email" required />
            <label for="login_input_password_new">
                Password (min. 6 characters!)
                <span class="login-form-password-pattern-reminder">
        
                </span>
            </label>
            <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
            <label for="login_input_password_repeat">Repeat password</label>
            <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
          	<label for="shift">Select Shift</label>
			<select id="shift" name="shift">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select>
			<label for="login_input_password_repeat">Select Location</label>
			
				<?php if ($this->locations) 
							{
								print '<select id="complexes" name="complexSelect" style="width: 200px; height: 40px;" >';
									foreach($this->locations as $key => $location) 
									{
										print "<option value='" .htmlspecialchars($location->name). "'>" .htmlspecialchars($location->name). "</option>";
									}	
								print '</select>
								<input type="submit"  class="register-button" name="register" value="Register" />';
							}
							else
							{
							   echo 'No Complexes found! Admin needs to create a complex first!';
							}
				?>


        </form>
    </div>


</div>
