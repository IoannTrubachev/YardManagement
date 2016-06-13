<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class NoteModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Getter for all notes (notes are an implementation of example data, in a real world application this
     * would be data that the user has created)
     * @return array an array with several objects (the results)
     */
    public function getAllNotes()
    {
        $sql = "SELECT user_id, note_id, note_text, note_public FROM notes WHERE user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $_SESSION['user_id']));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }
	
	public function getStickyNotes()
	{
		//delete any public posts that are older than 24 hours
		$del = $this->db->prepare("DELETE FROM notes WHERE note_public = '1' AND dt<SUBTIME(NOW(),'0 48:0:0')");
		$del->execute();
		$sth = $this->db->prepare("SELECT * FROM notes WHERE (note_public = :note_public AND plant = :plant) OR user_id = :user_id ORDER BY note_id DESC");
		$sth->execute(array(":note_public" => 1, ":plant" => Session::get('user_location'), ":user_id" => Session::get('user_id')));
		$query = $sth->fetchAll();
			
			return $query;

	}
	
	function update($id, $x, $y, $z) 
	{
	
		
				$sth = $this->db->prepare("UPDATE notes SET xyz = :xyz WHERE note_id = :id");
				$sth->execute(array(":xyz" => $x."x".$y."x".$z, ":id" => $id));
				$this->updateManifest();
	}

    /**
     * Getter for a single note
     * @param int $note_id id of the specific note
     * @return object a single object (the result)
     */
    public function getNote($note_id)
    {
        $sql = "SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id AND note_id = :note_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':user_id' => $_SESSION['user_id'], ':note_id' => $note_id));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Setter for a note (create)
     * @param string $note_text note text that will be created
     * @return bool feedback (was the note created properly ?)
     */
    public function create($note_text, $note_public)
    {
        // clean the input to prevent for example javascript within the notes.
        $note_text = strip_tags($note_text);
		$note_public = strip_tags($note_public);
		if($note_public == 'public') {
			$note_public = '1';
			$color = "green";
		}
		else
		{
			$note_public = '0';
			$color = "yellow";
			
		}
		

        $sql = "INSERT INTO notes (note_text, user_id, xyz, color, name, note_public, plant) VALUES (:note_text, :user_id, :xyz, :color, :name, :note_public, :plant)";
        $query = $this->db->prepare($sql);
        $query->execute(array(':note_text' => $note_text, ':user_id' => $_SESSION['user_id'], ':xyz' => rand(100, 900). 'x' .rand(100, 800). 'x' .rand(1, 100), ':color' => $color, ':name' => $_SESSION['user_full_name'], ':note_public' => $note_public, ':plant' => Session::get('user_location')));

        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_NOTE_CREATION_FAILED;
        }
        // default return
        return false;
		$this->updateManifest();
    }

    /**
     * Setter for a note (update)
     * @param int $note_id id of the specific note
     * @param string $note_text new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public function editSave($note_id, $note_text)
    {
        // clean the input to prevent for example javascript within the notes.
        $note_text = strip_tags($note_text);

        $sql = "UPDATE notes SET note_text = :note_text WHERE note_id = :note_id AND user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':note_id' => $note_id, ':note_text' => $note_text, ':user_id' => $_SESSION['user_id']));

        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_NOTE_EDITING_FAILED;
        }
        // default return
        return false;
    }

    /**
     * Deletes a specific note
     * @param int $note_id id of the note
     * @return bool feedback (was the note deleted properly ?)
     */
    public function delete($note_id)
    {
        $sql = "DELETE FROM notes WHERE note_id = :note_id AND user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':note_id' => $note_id, ':user_id' => $_SESSION['user_id']));

        $count =  $query->rowCount();

        if ($count == 1) {
            return true;
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_NOTE_DELETION_FAILED;
        }
        // default return
        return false;
		$this->updateManifest();
    }
	
	/**
	* Updates Notes Notification number
	*
	*/
	public function notes_notification() 
	{
			$qry = $this->db->prepare("SELECT `note_public`, `plant` FROM `notes` WHERE note_public = '1' AND plant = :plant");
			$qry->execute(array(":plant" => Session::get('user_location')));
			Session::set('notes_public', $qry->rowCount());
			$this->updateManifest();
	}
	public function updateManifest()
	{
		$manifestFile = fopen("public/dty_cache.manifest", "w") or die("Unable to open file!");
		$txt = "CACHE MANIFEST\n #Date: ".Date('h:i:sa')."\n 
			../public/favicon.ico
			../public/css/reset.css 
			../public/css/style.css 
			../public/css/style2.css 
			../public/js/jquery-ui-1.11.2/jquery-ui.css 
			../public/js/jquery-2.1.3.min.js
			../public/js/jquery-ui-1.11.2/jquery-ui.js 
			../public/js/jquery.json.min.js
			../public/js/jquery.ba-resize.min.js 
			../public/js/application.js
			../public/js/garlic.min.js 
			../public/js/jquery-timing.min.js 
			../public/css/jquery.toastmessage.css
			../public/js/jquery.toastmessage.js 
			../public/js/redips-drag-source.js 
			../public/js/script.js 
			../public/js/jScrollPane/jScrollPane.css 
			../public/css/chat.css
			../public/js/jScrollPane/jquery.mousewheel.js
			../public/js/jScrollPane/jScrollPane.min.js 
			../public/js/chat.js 
		NETWORK: \n*\n";
		fwrite($manifestFile, $txt);
		fclose($manifestFile);	
	}
}
