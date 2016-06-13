<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
  protected $clients;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn) {

  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
	   $msgs = json_decode($msg);
        switch ($msgs->type) {
            case 'userconnecting':
				
                $from->user = $msgs->user;
                $from->user->cod = md5(time() . $msgs->user->username);
                $this->clients->attach($from);
                $this->sendUpdateUsersBut($from);
                $this->updateFullUserList($from);
                break;
            default:
                 foreach ($this->clients as $client) {
					if ($from !== $client) {
					// The sender is not the receiver, send to each client connected
					$client->send($msg);
					}
				}
                break;
        }
   
  }

  public function onClose(ConnectionInterface $conn) {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);
    echo "Connection {$conn->resourceId} has disconnected\n";
	$this->sendUserDisconnected($conn);
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
  }
  
	protected function sendUpdateUsersBut($socketNot) {
        $data = array(
            'total' => count($this->clients) - 1,
            'username' => $socketNot->user->username,
            'cod' => $socketNot->user->cod,
            'type' => 'userconnected',
			'gravatar' => $socketNot->user->avatar
        );
		 
        
    }
    protected function updateFullUserList($socket) {
        $users = array();
        foreach($this->clients as $client) {
            
                $users[] = array(
                    'name' => $client->user->username,
                    'cod' => $client->user->cod,
					'gravatar' => $client->user->avatar
                );
            
        }
        $data = array(
            'type' => 'inituserslist',
            'users' => $users,
            'total' => count($users)
        );
		foreach($this->clients as $client) {
        $client->send(json_encode($data));
		}
		echo json_encode($data);
    }
    protected function sendUserDisconnected($socket) {
        $msg = array(
            'type' => 'userdisconneted',
            'name' => $socket->user->username,
            'cod' => $socket->user->cod
        );
        foreach($this->clients as $client) {
            
                $this->updateFullUserList($client);
            
        }
    }
}