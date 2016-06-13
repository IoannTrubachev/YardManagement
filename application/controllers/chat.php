<?php

class Chat extends Controller
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


public function index() 
{
	if(get_magic_quotes_gpc()){
	
	// If magic quotes is enabled, strip the extra slashes
	array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
	array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
	}
	
	try{
	
		$response = array();
	
		// Handling the supported actions:
			$chat_model = $this->loadModel('Chat');
		switch($_GET['action'])
		{
		
			case 'login':
			 
			$response = $chat_model->login($_POST['name'],$_POST['email']);
				
			break;
		
			case 'checkLogged':
				$response = $chat_model->checkLogged();

			break;
		
			case 'submitChat':
				$response = $chat_model->submitChat($_POST['chatText']);
				
			break;
		
			case 'getUsers':
				$response = $chat_model->getUsers();
				
			break;
		
			case 'getChats':
				$response = $chat_model->getChats($_GET['lastID']);
				
			break;
		
			default:
				throw new Exception('Wrong action');
		}
	
	echo json_encode($response);
	}
	catch(Exception $e){
		die(json_encode(array('error' => $e->getMessage())));
	}
}

}