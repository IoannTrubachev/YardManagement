<?php
/**
 * Class Dashboard
 * This is a demo controller that simply shows an area that is only visible for the logged in user
 * because of Auth::handleLogin(); in line 19.
 */
class Dashboard extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();

        // this controller should only be visible/usable by logged in users, so we put login-check here
        Auth::handleLogin();
    }

    /**
     * This method controls what happens when you move to /dashboard/index in your app.
     */
    function index()
    {
        $this->view->render('dashboard/index');
    }
	/** 
	NOT the most ideal way to display content but for now it works
	**/
	function tables()
	{
			$dashboard_model = $this->loadModel('Dashboard');
				print '<div class="container">';
				
					$dashboard_model->addTable(Session::get('user_location'), "Building");
					
				print '</div>
							<br />		
						<div class="bottom"></a>			<!-- code for expandable tables. More trailers -->';
						
					$dashboard_model->addTable(Session::get('user_location'), "lot");
							
				print '<br />
						</div>';
					
			
			print '<script>$(document).ready(function() { ';
				 $dashboard_model->getOpenTable();
				 
		print '});</script>';
	}
	public function drivers() 
	{
		$dashboard_model = $this->loadModel('Dashboard');
        $dashboard_model->getDrivers();
	}
	 public function save()
    {
        //save via ajax
        if (isset($_REQUEST['p']) AND !empty($_REQUEST['p'])) {
		
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveAjax($_REQUEST['p']);
			$this->noCache();
			
		}
        
		if (isset($_REQUEST['n']) AND !empty($_REQUEST['n'])) {
			$this->n = $_REQUEST['n'];
		
        
			function test_input($data) 
			{
				$data = trim($data);
				$data = filter_var($data, FILTER_SANITIZE_STRING);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				$data = strip_tags($data);
				return $data;
			}
			list($notes, $trlid) = explode('_', $this->n);
			$this->nts = test_input($notes);
			$this->trid = test_input($trlid);
		      if (!preg_match("/^(?=.{0,64}$)[a-zA-Z0-9 '?!@#$%&.,][a-zA-Z0-9 '?!@#$%&.,]*(?: [a-zA-Z0-9 '?!@#$%&.,]+)*$/", $this->nts)) {
            $_SESSION["feedback_negative"][] = FEEDBACK_NOTES_DOES_NOT_FIT_PATTERN;
            return false;
		}
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveNotesAjax($this->nts, $this->trid);
			$this->noCache();
        }
		if (isset($_REQUEST['q']) AND !empty($_REQUEST['q'])) {
		$q = $_REQUEST['q'];
		// explode input parameters:
			list($this->door, $this->building, $this->status, $this->notes) = explode('_', $q);
			
				if($this->status == "only48") 
				{
					$this->stat = 0; $this->only48 = 1;
				}
				else if ($this->status == "disabled")
				{
					$this->stat = 1; $this->only48 = 0;
				}
				else
				{
					$this->stat = 0; $this->only48 = 0;
				}
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveDrStAjax($this->door, $this->building, $this->notes, $this->stat, $this->only48);
			$this->noCache();
        }
		if (isset($_REQUEST['s']) AND !empty($_REQUEST['s'])) {
			
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveTrStAjax($_REQUEST['s']);
			$this->noCache();
        }
		if (isset($_REQUEST['r']) AND !empty($_REQUEST['r'])) {
			
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveTrLoadAjax($_REQUEST['r']);
			$this->noCache();
        }
		if (isset($_REQUEST['ta']) AND !empty($_REQUEST['ta'])) {
			function test_input($data) 
			{
				$data = trim($data);
				$data = filter_var($data, FILTER_SANITIZE_STRING);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				$data = strip_tags($data);
				return $data;
			}
			$this->san = test_input($_REQUEST['ta']);
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveOpenTable($this->san);
			$this->noCache();
        }
		if (isset($_REQUEST['min']) AND !empty($_REQUEST['min'])) {
			function test_input($data) 
			{
				$data = trim($data);
				$data = filter_var($data, FILTER_SANITIZE_STRING);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				$data = strip_tags($data);
				return $data;
			}
			$san = test_input($_REQUEST['min']);
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->saveMinTable($san);
			$this->noCache();
        }
			if (isset($_POST['data']) AND !empty($_POST['data'])) {
			//decode JSON data received from AJAX POST request
			$d = json_decode($_POST['data']);
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->update($d);
        }
        header('location: ' . URL . 'dashboard');
    }
	
	 public function delete()
    {

        //delete via ajax
        if (isset($_REQUEST['d']) AND !empty($_REQUEST['d'])) {
            $dashboard_model = $this->loadModel('Dashboard');
            $dashboard_model->deleteAjax($_REQUEST['d']);
			$this->noCache();
		}
        
        header('location: ' . URL . 'dashboard/index');
    }
	
	public function duplicationError()
	{
	$feedback_duplicate = Session::get('feedback_duplicate');
	// echo out negative messages
		if (isset($feedback_duplicate)) {
			foreach ($feedback_duplicate as $feedback) {
			echo '<div class="feedback">'.$feedback.'</div>';
			}
		}
	Session::set('feedback_duplicate', null);
		
	}
	
	public function checkForNotifications() 
	{
		$dashboard_model = $this->loadModel('Dashboard');
        $notif = $dashboard_model->getNotification();
		$this->noCache();
		
		echo $notif;
	}
	private function noCache()
	{
				// no cache
			header('Pragma: no-cache');
			// HTTP/1.1
			header('Cache-Control: no-cache, must-revalidate');
			// date in the past
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	}
}
