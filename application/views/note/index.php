	<script type="text/javascript" src="../public/fancybox/jquery.fancybox-1.2.6.js"></script>
	<link rel="stylesheet" type="text/css" href="../public/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
	<script>
		$(document).ready(function() {
			//for sticky notes
			var tmp;
		
			$('.note').each(function(){
				/* Finding the biggest z-index value of the notes */
				tmp = $(this).css('z-index');
				if(tmp>zIndex) zIndex = tmp;
			})

			/* A helper function for converting a set of elements to draggables: */
			make_draggable($('.note'));
		});
		var zIndex = 0;
		function make_draggable(elements)
		{
			/* Elements is a jquery object: */
	
			elements.draggable({
				containment:'parent',
				start:function(e,ui){ ui.helper.css('z-index',++zIndex); },
				stop:function(e,ui){
			
					/* Sending the z-index and positon of the note to update_position.php via AJAX GET: */

					$.get('note/update',{
						x		: ui.position.left,
						y		: ui.position.top,
						z		: zIndex,
						id	: parseInt(ui.helper.find('span.data').html())
					});
				}
			});
		}
	</script>
<div class="content">
    <h1>Create new note</h1>
	
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
	<div class="center">
		<a id="addButton" class="green-button" href="<?php ECHO URL; ?>public/add_note.php">Add a note</a>
	</div>
    <h1 style="margin-top: 50px;">List of your notes</h1>

    <table class="centerTable">
    <?php
        if ($this->notes) {
            foreach($this->notes as $key => $value) {
				if(htmlentities($value->note_public) == 1) {
					$public = "Public";
				}
				else
				{
					$public = "Private";
				}
                echo '<tr>';
                echo '<td>' . htmlentities($value->note_text) . '</td>';
				echo '<td>' . $public . '</td>';
                echo '<td><a href="'. URL . 'note/edit/' . $value->note_id.'">Edit</a></td>';
                echo '<td><a href="'. URL . 'note/delete/' . $value->note_id.'">Delete</a></td>';
                echo '</tr>';
            }
        } else {
            echo 'No notes yet. Create some !';
        }
    ?>
    </table>
</div>
<?php 
			$notes = '';
			$left='';
			$top='';
			$zindex='';
					foreach($this->stickynotes as $key => $row)
						{
							// The xyz column holds the position and z-index in the form 200x100x10:
							list($left,$top,$zindex) = explode('x',$row->xyz);
							if($row->name == Session::get('user_full_name')) { 
								$close = '<a href="'. URL . 'note/delete/' . $value->note_id.'"><div class="note-delete"></div></a>';
							}
							else {
								$close = "";
							}
							print '
							<div class="note '.$row->color.'" style="left:'.$left.'px;top:'.$top.'px;z-index:'.$zindex.'">
							'.htmlspecialchars($row->note_text).' '.$close.'
							<div class="author">'.htmlspecialchars($row->name).'</div>
							<span class="data">'.$row->note_id.'</span>
							</div>';
							
						}
?>
<script>
$(document).ready(function() {
			/* Configuring the fancybox plugin for the "Add a note" button: */
	$("#addButton").fancybox({
		'zoomSpeedIn'		: 600,
		'zoomSpeedOut'		: 500,
		'easingIn'			: 'easeOutBack',
		'easingOut'			: 'easeInBack',
		'hideOnContentClick': false,
		'padding'			: 15
	});
	
	/* Listening for keyup events on fields of the "Add a note" form: */
	$(document).on('keyup', '.pr-body', function(e){
		if(!this.preview)
			this.preview=$('#fancy_ajax .note');
		
		/* Setting the text of the preview to the contents of the input field, and stripping all the HTML tags: */
		this.preview.find($(this).attr('class').replace('pr-','.')).html($(this).val().replace(/<[^>]+>/ig,''));
	});
	
	/* Changing the color of the preview note: */
	$(document).on('click', '.color',function(){
		$('#fancy_ajax .note').removeClass('yellow green blue').addClass($(this).attr('class').replace('color',''));
	});
});
</script>