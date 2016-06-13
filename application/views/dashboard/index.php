 <!-- scripts and CSS for Dashboard -->
 <link rel="stylesheet" type="text/css" href="../public/css/jquery.toastmessage.css" />
 <script src="../public/js/jquery.toastmessage.js" type="text/javascript"></script>
 <script type="text/javascript" src="../public/js/redips-drag-source.js"></script>
<script type="text/javascript" src="../public/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="../public/js/jScrollPane/jScrollPane.css" />
<link rel="stylesheet" type="text/css" href="../public/css/chat.css" />

<div class="content_dashboard">
    <!-- echo out the system feedback (error and success messages) -->
    <div id="feedbackMessages"></div><?php $this->renderFeedbackMessages(); ?>
	<div id="error"></div>
		<!-- Begin Menu-->
		<div class="leftmenu">
				<h2 class="menuTitle">Menu</h2>
					<div id="message" class="mark" title="You can not drop here"></div>
			<div id="optionsMenu" class="options">
				<h3>Options</h3>
					<div class="middleCheckbox">
					<form data-persist="garlic">
					<div class="multisingle"><p>Drop multiple </p>
						<input type="radio" id="radio1" name="drop_option" class="css3radio"  value="single" title="Disable dropping to already taken table cells" <?php if (Session::get('user_account_type') == 1) { print 'disabled="true"';}; ?>/><label for="radio1">Disable</label>
						<input type="radio" id="radio2" name="drop_option" class="css3radio"  value="multiple" title="Enable dropping to already taken table cells" <?php if (Session::get('user_account_type') == 1) { print 'disabled="true"';}; ?>/><label for="radio2">Enable</label>
					</div>
				
						<span class="message_line">Disable doors</span>
						<input type="checkbox"  id="confirmDisableDoors" class="oval" name="check" onclick="toggleDisableDoors(this)" title="Remove cloned object if dragged outside of any table" <?php if (Session::get('user_account_type') == 1) { print 'disabled="true"';}; ?>/><label class="toggler" for="confirmDisableDoors"></label> 
						<input type="checkbox" id="confirmDelete" class="oval" onclick="toggleConfirm(this)" title="Confirm delete" <?php if (Session::get('user_account_type') == 1) { print 'disabled="true"';}; ?>/><label class="toggler blue" for="confirmDelete"></label><span class="message_line">Confirm delete</span>
						<input type="checkbox" id="canDrag" class="oval" title="Enable dragging" <?php if (Session::get('user_account_type') > 1) { print 'checked="true"';} else {print 'disabled="true"';}; ?>/><label class="toggler red" for="canDrag"></label><span class="message_line">Enable dragging</span>
					</div>
					</form>
				</div>
		</div>
		<!-- End Menu -->
				<!-- tables inside this DIV could have draggable content -->
				
		<div id="drag">

					<?php $building = new Dashboard();
						$building->tables(); ?>
					  
		</div><!-- close container id -->
		<div id="chatContainer">
					<div id="chatTopBar" class="rounded" onmousedown="toggleChat();"></div>
							<div id="chatLineHolder"></div>
							<div id="chatUsers" class="rounded"></div>
					<div id="chatBottomBar" class="rounded">
							<div class="tip"></div>
						<form id="submitForm" method="post" action="">
							<input id="chatText" name="chatText" class="rounded" maxlength="255" />
							<input type="submit" class="blueButton" value="Submit" />
						</form>
					</div>
		</div>
<!-- chat system JavaScript code below -->
<script src="../public/js/jScrollPane/jquery.mousewheel.js"></script>
<script src="../public/js/jScrollPane/jScrollPane.min.js"></script>
<script src="../public/js/chat.js"></script>
<!-- END Chat JavaScript -->
<!-- Scripts below for Dashboard -->
    <script type="text/javascript">	
 $(document).ready(

			//check for notification of a drop to plants area
            function() {
				<?php if (Session::get('user_account_type') > 1) { print "setTimeout(function() {REDIPS.drag.dropMode = $('input[name=drop_option]:checked').val();}, 600);
	$('input[name=drop_option]:radio').change(function() {
	REDIPS.drag.dropMode = $('input[name=drop_option]:checked').val();
	});
	//check if disable doors is checked.
				setTimeout(function(){toggleDisableDoors();}, 1000);";} ?>
                setInterval(function() {
                    $.ajax({
										url: "<?php echo URL; ?>dashboard/checkForNotifications",
										type: "POST",
										success: function(response){
											if(response !== "") {
											showStickyWarningToast(response);
											}
											function showStickyWarningToast(responseText) {
												$().toastmessage('showToast', {
												text     : responseText,
												sticky   : true,
												position : 'top-right',
												type     : 'notice',
												closeText: ''
												});
												
											}
										},
										error:function(response){
										console.log("failure " + response.statusText);
										}
									});	
                }, 15000);
	//check if Create trailers is too high 
	if($("#createTable").height() > 900) {
		$("#createTable").css({position: 'absolute'});
	}
	else {
		$("#createTable").css({position: 'fixed'});
	}			
});		
</script>

</div>
