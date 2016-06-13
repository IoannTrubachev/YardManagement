<?php

/**
 * Class Admin
 * This controller shows the admin options that include complex changes and user changes
 */
class Admin extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
		Auth::handleLogin();
		Auth::handleAdminLogin();
    }

    /**
     * This method controls what happens when you move to /admin/index in your app.
     * Shows admin tools menu
     */
    function index()
    {
		Auth::handleLogin();
		Auth::handleAdminLogin();
        $this->view->render('admin/index');
    }
	
	/**
     * 
     * Shows a list of all users.
     */
    function users()
    {
		Auth::handleLogin();
		Auth::handleAdminLogin();
        $admin_model = $this->loadModel('Admin');
        $this->view->users = $admin_model->getAllUsersProfiles();
        $this->view->render('admin/showusers');
    }

    /**
     * This method controls what happens when you move to /overview/showuserprofile in your app.
     * Shows the (public) details of the selected user.
     * @param $user_id int id the the user
     */
    function showUserProfile($user_id)
    {
        if (isset($user_id)) {
            $admin_model = $this->loadModel('Admin');
            $this->view->user = $admin_model->getUserProfile($user_id);
            $this->view->render('admin/showuserprofile');
        } else {
            header('location: ' . URL);
        }
    }
	
	    /**
     * This method controls what happens when you move to /admin/complex in your app.
     * Allows to create new complexes, add/remove doors and lots.
     * @param $user_id int id the the user
     */
    function complex()
    {
		Auth::handleLogin();
		Auth::handleAdminLogin();
        if (isset($_POST) AND !empty($_POST['complex'])) {
		
			$this->building = $_POST['building'];
			$this->doors = $_POST['doors'];
			$this->option = $_POST['optionIs'];
			$this->complx = $_POST['complex'];
            $admin_model = $this->loadModel('Admin');
            $this->view->user = $admin_model->createNewComplex($this->complx, $this->building, $this->doors, $this->option);
            $this->view->render('admin/complex');
        } else {
            $this->view->render('admin/complex');
        }
    }
}

