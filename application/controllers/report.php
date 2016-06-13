<?php

/**
 * Class Report
 * The report controller. Here we create, read, update and delete (CRUD) example data.
 */
class Report extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions. If all of your pages should only
        // be usable by logged-in users: Put this line into libs/Controller->__construct
        Auth::handleLogin();
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function index()
    {
       
        $this->view->render('report/index');
    }

    /**
     * This method controls what happens when you move to /dashboard/create in your app.
     * Creates a new note. This is usually the target of form submit actions.
     */
    public function report()
    {
		$report_model = $this->loadModel('Report');
		if (empty($_POST['trailer_code']) AND empty($_POST['filter_date_last']) AND empty($_POST['filter_date']))
		{
			$this->view->notes = $report_model->getAllResults();
		}
		else if (isset($_POST['trailer_code']) AND !empty($_POST['trailer_code']) AND isset($_POST['filter_date']) AND !empty($_POST['filter_date']))
		{
			$search = strip_tags($_POST['trailer_code']);
			$date = strip_tags($_POST['filter_date']);
			$date_last = strip_tags($_POST['filter_date_last']);
			$this->view->notes = $report_model->getResults($search, $date, $date_last);
		}
		else if (isset($_POST['trailer_code']) AND empty($_POST['filter_date']) AND empty($_POST['filter_date_last'])) 
		{
			
			$search = strip_tags($_POST['trailer_code']);
			$this->view->notes = $report_model->getNameResults($search);
        }
		
		 else if (isset($_POST['filter_date']) AND empty($_POST['trailer_code']) AND isset($_POST['filter_date_last']) AND !empty($_POST['filter_date'])) 
		{
			
			$date = strip_tags($_POST['filter_date']);
			$date_last = strip_tags($_POST['filter_date_last']);
			$this->view->notes = $report_model->getDateResults($date, $date_last);
        }
		
		$this->view->render('report/report');
    }

}
