<?php

/**
 * Login Controller
 * Controls the login processes
 */

class Login extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    function index()
    {
        // create a login model to perform the getFacebookLoginUrl() method
        $login_model = $this->loadModel('Login');



        // show the view
        $this->view->render('login/index');
    }

    /**
     * The login action, when you do login/login
     */
    function login()
    {
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        // perform the login method, put result (true or false) into $login_successful
        $login_successful = $login_model->login();

        // check login status
        if ($login_successful) {
            // if YES, then move user to dashboard/index (btw this is a browser-redirection, not a rendered view!)
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if NO, then move user to login/index (login form) again
            header('location: ' . URL . 'login/index');
        }
    }



    /**
     * The logout action, login/logout
     */
    function logout()
    {
        $login_model = $this->loadModel('Login');
        $login_model->logout();
        // redirect user to base URL
        header('location: ' . URL);
    }

    /**
     * Login with cookie
     */
    function loginWithCookie()
    {
        // run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
        $login_model = $this->loadModel('Login');
        $login_successful = $login_model->loginWithCookie();

        if ($login_successful) {
            header('location: ' . URL . 'dashboard/index');
        } else {
            // delete the invalid cookie to prevent infinite login loops
            $login_model->deleteCookie();
            // if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Show user's profile
     */
    function showProfile()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/showprofile');
    }

    /**
     * Edit user name (show the view with the form)
     */
    function editUsername()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/editusername');
    }
	
	   /**
     * Edit full name (show the view with the form)
     */
    function editFullname()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/editfullname');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     */
    function editUsername_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->editUserName();
        $this->view->render('login/editusername');
    }
	
	 /**
     * Edit user name (perform the real action after form has been submitted)
     */
    function editFullname_action()
    {
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->editFullName();
        $this->view->render('login/editfullname');
    }
	 /**
     * Change Location 
     */
    function changeLocation()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
		$login_model = $this->loadModel('Login');
		$this->view->locations = $login_model->getLocations();
        $this->view->render('login/changelocation');
    }

	  /**
     * Edit user name (perform the real action after form has been submitted)
     */
    function changeLocation_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->changeLocation();
		$this->view->locations = $login_model->getLocations();
         header('location: ' . URL . 'dashboard/index');
    }
    /**
     * Edit user email (show the view with the form)
     */
    function editUserEmail()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/edituseremail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     */
    function editUserEmail_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->editUserEmail();
        $this->view->render('login/edituseremail');
    }

    /**
     * Upload avatar
     */
    function uploadAvatar()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $this->view->avatar_file_path = $login_model->getUserAvatarFilePath();
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    function uploadAvatar_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
        $login_model = $this->loadModel('Login');
        $login_model->createAvatar();
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    function changeAccountType($user_id)
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
		if (SESSION::get('user_account_type') == 3) {
		$login_model = $this->loadModel('Login');
		if(isset($_POST["id"])) {
			function test_input($data) 
			{
				$data = trim($data);
				$data = filter_var($data, FILTER_SANITIZE_STRING);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				$data = strip_tags($data);
				return $data;
			}
			$this->id = test_input($_POST['id']);
			 $login_model->changeAccountType($this->id);
		}
		$this->view->user = $login_model->getUserProfile($user_id);
        $this->view->render('login/changeaccounttype');
    }else {
		 header('location: ' . URL . '/overview/index');
	}
	} 

    /**
     *
     */
    function changeAccountType_action()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        // Note: This line was missing in early version of the script, but it was never a real security issue as
        // it was not possible to read or edit anything in the database unless the user is really logged in and
        // has a valid session.
        Auth::handleLogin();
		
        $login_model = $this->loadModel('Login');
        $login_model->changeAccountType($this->id);
        $this->view->render('overview/index');
    }
	
	   /**
     * Activate or Disable account
     */
    function activate($user_id)
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
		if (SESSION::get('user_account_type') == 3) {
		$login_model = $this->loadModel('Login');
		if(isset($_POST["id"])) {
			function test_input($data) 
			{
				$data = trim($data);
				$data = filter_var($data, FILTER_SANITIZE_STRING);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				$data = strip_tags($data);
				return $data;
			}
			$this->id = test_input($_POST['id']);
			 $login_model->changeAccountType($this->id);
		}
		 $login_model->activateUser($user_id);
        header('location: ' . URL . '/overview/index');
    }else {
		 header('location: ' . URL . '/overview/index');
	}
	} 

    /**
     * Register page
     */
    function register()
    {
        $login_model = $this->loadModel('Login');
		$this->view->locations = $login_model->getLocations();
        $this->view->render('login/register');
    }

    /**
     * Register page action (after form submit)
     */
    function register_action()
    {
        $login_model = $this->loadModel('Login');
        $registration_successful = $login_model->registerNewUser();

        if ($registration_successful == true) {
            header('location: ' . URL . 'login/index');
        } else {
            header('location: ' . URL . 'login/register');
        }
    }



    /**
     * Verify user after activation mail link opened
     * @param int $user_id user's id
     * @param string $user_activation_verification_code sser's verification token
     */
    function verify($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) && isset($user_activation_verification_code)) {
            $login_model = $this->loadModel('Login');
            $login_model->verifyNewUser($user_id, $user_activation_verification_code);
            $this->view->render('login/verify');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Request password reset page
     */
    function requestPasswordReset()
    {
        $this->view->render('login/requestpasswordreset');
    }

    /**
     * Request password reset action (after form submit)
     */
    function requestPasswordReset_action()
    {
        $login_model = $this->loadModel('Login');
        $login_model->requestPasswordReset();
        $this->view->render('login/requestpasswordreset');
    }

    /**
     * Verify the verification token of that user (to show the user the password editing view or not)
     * @param string $user_name username
     * @param string $verification_code password reset verification token
     */
	function verifyPasswordReset($user_name, $verification_code)
    {
        $login_model = $this->loadModel('Login');
        if ($login_model->verifyPasswordReset($user_name, $verification_code)) {
            // get variables for the view
            $this->view->user_name = $user_name;
            $this->view->user_password_reset_hash = $verification_code;
            $this->view->render('login/changepassword');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Set the new password
     * Please note that this happens while the user is not logged in.
     * The user identifies via the data provided by the password reset link from the email.
     */
    function setNewPassword()
    {
        $login_model = $this->loadModel('Login');
        // try the password reset (user identified via hidden form inputs ($user_name, $verification_code)), see
        // verifyPasswordReset() for more
        $login_model->setNewPassword();
        // regardless of result: go to index page (user will get success/error result via feedback message)
        header('location: ' . URL . 'login/index');
    }

}
