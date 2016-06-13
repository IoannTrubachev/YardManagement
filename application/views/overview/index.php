<div class="content">
    <h1>Overview</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <p>
    All dispatchers, shipping coordinators, users, and administrators. 
    </p>

    <p>

        <table class="overview-table">
        <?php

        foreach ($this->users as $user) {

            if ($user->user_active == 0) {
                echo '<tr class="inactive">';
            } else {
                echo '<tr class="active">';
            }

            echo '<td>'.$user->user_id.'</td>';
            echo '<td class="avatar">';

            if (isset($user->user_avatar_link)) {
                echo '<div class="circular" style="background: url(' .$user->user_avatar_link. ') no-repeat; width:40px; height:40px;"></div>';
            }

            echo '</td>';
            echo '<td>'.$user->user_full_name.'</td>';
			echo '<td>'.$user->user_name.'</td>';
            echo '<td>'.$user->user_email.'</td>';
			echo '<td>'.$user->user_location.'</td>';
            echo '<td>Active: '.$user->user_active.'</td>';
			if ($user->user_account_type == 1) {
				echo '<td>Shipping Coordinator/Supervisor</td>';
			} elseif ($user->user_account_type == 2) {
				echo '<td>Dispatcher</td>';
			}elseif ($user->user_account_type == 3) {
				echo '<td>Administrator</td>';
			}
            echo '<td><a href="'.URL.'overview/showuserprofile/'.$user->user_id.'">Show user\'s profile</a></td>';
            echo "</tr>";
        }

        ?>
		
		
        </table>
    </p>
</div>
