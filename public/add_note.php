<h3 class="popupTitle">Add a new note</h3>

<!-- The preview: -->
<div id="previewNote" class="note yellow" style="left:0;top:65px;z-index:1">
	<div class="body"></div>
	<div class="author"></div>
	<span class="data"></span>
</div>

<div id="noteData"> <!-- Holds the form -->
<form method="post" action="http://10.2.0.237/note/create">

<label for="note-body">Text of the note</label>
<textarea name="note-body" id="note-body" class="pr-body" cols="30" rows="6"></textarea>

<label for="note-name">Make Public (Same Plant)</label>
<div class="radio-toolbar">
<input id="yellow" class="color yellow" type="radio" name="note_public" value="private" checked="checked" /><label for="yellow">private</label><br>
<input id="green" class="color green" type="radio" name="note_public" value="public" /><label for="green">public</label><br>
</div>

<!-- The green submit button: -->

<input id="note_submit" type="submit" value='Create note' style="position: absolute; top:250px; right: 50px;" disabled/>

</form>
</div>
<!-- check if there is data in the textbox, if not, do not let submit. -->
<script>
	document.getElementById('note-body').addEventListener("keypress",textareaLengthCheck,false);
	function textareaLengthCheck(e) 
	{
		if(document.getElementById('note-body').value.length < 2) 
		{
			document.getElementById('note_submit').disabled = true;
		}
		else
		{
			document.getElementById('note_submit').disabled = false;
		}
	}
</script>
