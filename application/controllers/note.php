<?php

/**
 * Class Note
 * The note controller. Here we create, read, update and delete (CRUD) example data.
 */
class Note extends Controller
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
        $note_model = $this->loadModel('Note');
        $this->view->notes = $note_model->getAllNotes();
		$this->view->stickynotes = $note_model->getStickyNotes();
		$note_model->notes_notification();
        $this->view->render('note/index');
    }
	


    /**
     * This method controls what happens when you move to /dashboard/create in your app.
     * Creates a new note. This is usually the target of form submit actions.
     */
    public function create()
    {
        // optimal MVC structure handles POST data in the controller, not in the model.
        // personally, I like POST-handling in the model much better (skinny controllers, fat models), so the login
        // stuff handles POST in the model. in this note-controller/model, the POST data is intentionally handled
        // in the controller, to show people how to do it "correctly". But I still think this is ugly.
        if (isset($_REQUEST['note-body']) AND !empty($_REQUEST['note-body'])) {
            $note_model = $this->loadModel('Note');
            $note_model->create($_REQUEST['note-body'], $_REQUEST['note_public']);
        }
        header('location: ' . URL . 'note');
    }
	
	public function update()
	{
		if(!is_numeric($_GET['id']) || !is_numeric($_GET['x']) || !is_numeric($_GET['y']) || !is_numeric($_GET['z']))
		die("0");

		// Escaping:
		$id = (int)$_GET['id'];
		$x = (int)$_GET['x'];
		$y = (int)$_GET['y'];
		$z = (int)$_GET['z'];
		$note_model = $this->loadModel('Note');
        $note_model->update($id, $x, $y, $z);
	}

    /**
     * This method controls what happens when you move to /note/edit(/XX) in your app.
     * Shows the current content of the note and an editing form.
     * @param $note_id int id of the note
     */
    public function edit($note_id)
    {
        if (isset($note_id)) {
            // get the note that you want to edit (to show the current content)
            $note_model = $this->loadModel('Note');
            $this->view->note = $note_model->getNote($note_id);
            $this->view->render('note/edit');
        } else {
            header('location: ' . URL . 'note');
        }
    }

    /**
     * This method controls what happens when you move to /note/editsave(/XX) in your app.
     * Edits a note (performs the editing after form submit).
     * @param int $note_id id of the note
     */
    public function editSave($note_id)
    {
        if (isset($_POST['note_text']) && isset($note_id)) {
            // perform the update: pass note_id from URL and note_text from POST
            $note_model = $this->loadModel('Note');
            $note_model->editSave($note_id, $_POST['note_text']);
        }
        header('location: ' . URL . 'note');
    }

    /**
     * This method controls what happens when you move to /note/delete(/XX) in your app.
     * Deletes a note. In a real application a deletion via GET/URL is not recommended, but for demo purposes it's
     * totally okay.
     * @param int $note_id id of the note
     */
    public function delete($note_id)
    {
        if (isset($note_id)) {
            $note_model = $this->loadModel('Note');
            $note_model->delete($note_id);
        }
        header('location: ' . URL . 'note');
    }
}
