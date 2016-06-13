<div class="content">
    <h1>A public user profile</h1>
    <p>More information on:</p>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <?php if ($this->user) { ?>
        <p>
            <table class="overview-table">
            <?php

                if ($this->user->user_active == 0) {
                    echo '<tr class="inactive">';
                } else {
                    echo '<tr class="active">';
                }

                echo '<td>'.$this->user->user_id.'</td>';
                echo '<td class="avatar"><img src="'.$this->user->user_avatar_link.'" /></td>';
                echo '<td>'.$this->user->user_name.'</td>';
                echo '<td>'.$this->user->user_email.'</td>';
				if (Session::get('user_account_type') == 3) {
                echo '<td>Active: <td><a href="'.URL.'login/activate/'.$this->user->user_id.'">'.$this->user->user_active.'</a></td>';
				} else
					{
					echo '<td>Active: '.$this->user->user_active.'</td>';
					}
				if ($this->user->user_account_type == 1) {
					echo '<td><a href="'.URL.'login/changeaccounttype/'.$this->user->user_id.'">Account type: Shipping Coordinator/ Supervisor</a></td>';
				} elseif ($this->user->user_account_type == 2) {
					echo '<td><a href="'.URL.'login/changeaccounttype/'.$this->user->user_id.'">Account type: Dispatcher</a></td>';
				} else {
					echo '<td><a href="'.URL.'login/changeaccounttype/'.$this->user->user_id.'">Account type: Administrator</a></td>';
				}
                echo '<td>Location: '.$this->user->user_location.'</td>';
                echo "</tr>";
            ?>
            </table>
        </p>
    <?php } ?>
</div>
