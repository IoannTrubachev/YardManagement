<?php

/* The Chat class exploses public static methods, used by ajax.php */

class ChatModel{
	  /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
		
	public function login($name,$email){
		if(!$name || !$email){
			throw new Exception('Fill in all the required fields.');
		}
		
		if(!filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)){
			throw new Exception('Your email is invalid.');
		}
		
		// Preparing the gravatar hash:
		$gravatar = md5(strtolower(trim($email)));
		
		$this->saveUser($_SESSION['user_full_name']);
		
	
		
		$_SESSION['user']	= array(
			'name'		=> $name,
			'gravatar'	=> $gravatar
		);
		
		return array(
			'status'	=> 1,
			'name'		=> $name,
			'gravatar'	=> ChatModel::gravatarFromHash($gravatar)
		);
	}
	
	public static function checkLogged(){
		$response = array('logged' => false);
			
		if($_SESSION['user_name']){
			$response['logged'] = true;
			$response['loggedAs'] = array(
				'name'		=> $_SESSION['user_full_name'],
				'gravatar'	=> ChatModel::gravatarFromHash(Session::get('user_avatar_file'))
			);
		}
		
		return $response;
	}
	
	
	public function submitChat($chatText){
		if(!$_SESSION['user_name']){
			throw new Exception('You are not logged in');
		}
		
		if(!$chatText){
			throw new Exception('You haven\'t entered a chat message.');
		}
	
		$insertID = $this->saveLine($_SESSION['user_full_name'], Session::get('user_avatar_file'), $chatText);
	
		
		
	
		return array(
			'status'	=> 1,
			'insertID'	=> $insertID
		);
	}
	
	public function getUsers(){
		if($_SESSION['user_full_name']){
			$this->updateUser($_SESSION['user_full_name'], Session::get('user_avatar_file'));
			
		}
		
		// Deleting chats older than 2 days and users inactive for 2 minutes
		
		$del = $this->db->prepare("DELETE FROM webchat_lines WHERE ts < SUBTIME(NOW(),'48:0:0')");
		$del->execute();
		$inac = $this->db->prepare("DELETE FROM webchat_users WHERE last_activity < SUBTIME(NOW(),'0:2:0')");
		$inac->execute();
	
		
		
		$res = $this->db->prepare('SELECT * FROM webchat_users ORDER BY name ASC LIMIT 18');
		$res->execute();
		$results = $res->fetchAll();
		
		$qry = $this->db->prepare('SELECT count(*) as cnt FROM webchat_users');
		$qry->execute();
		$usersOnline = $qry->fetch();
		
		
		$users = array();
		foreach($results as  $key => $user) {
		
			$users[] = $user;
		}
	
		return array(
			'users' => $users,
			'total' => $usersOnline->cnt
		);
	}
	
	public  function getChats($lastID){
		$lastID = (int)$lastID;
	
		$res = $this->db->prepare('SELECT * FROM webchat_lines  ORDER BY id ASC');
		$res->execute();
		$result = $res->fetchAll();
		$chats = array();
		foreach($result as $key => $chat){
			
			// Returning the GMT (UTC) time of the chat creation:
			
			$chat->time = array(
				'hours'		=> gmdate('H',strtotime($chat->ts)),
				'minutes'	=> gmdate('i',strtotime($chat->ts))
			);
			
			
			
			$chats[] = $chat;
		}
	
		return array('chats' => $chats);
	}
	
	public static function gravatarFromHash($hash, $size=23){
		return $hash;
	}
	
	protected $name = '', $gravatar = '';
	
	public function saveUser(){
		
		$sth = $this->db->prepare("
			INSERT INTO webchat_users (name, gravatar)
			VALUES (
				'".$this->name."',
				''
		)");
		$sth->execute();
		
	
	}
	
	public function updateUser($name, $gravatar){
		$sth = $this->db->prepare("
			INSERT INTO webchat_users (name, gravatar)
			VALUES (
				'".$name."',
				'".$gravatar."'
			) ON DUPLICATE KEY UPDATE last_activity = NOW()");
			$sth->execute();
	}
	
	protected $text = '', $author = '';
	
	public function saveLine($author, $gravatar, $text){
		$sth = $this->db->prepare("
			INSERT INTO webchat_lines (author, gravatar, text)
			VALUES (
				'".$author."',
				'".$gravatar."',
				'".$text."'
		)");
		$sth->execute();
		
		$qry = $this->db->prepare("
			SELECT id FROM webchat_lines ORDER BY id DESC LIMIT 1;
		)");
		$qry->execute();
		$LastId = $qry->fetch();
		
		return $LastId->id;
		
	}

}









