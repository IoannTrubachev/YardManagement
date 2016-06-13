<div class="content">
    <h1>Change your location</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
<div class="center">
    <form action="<?php echo URL; ?>login/changeLocation_action" method="post">
        <label>New Location</label>
       			<?php if ($this->locations) 
							{
								print '<select id="complexes" name="complexSelect" style="width: 200px; height: 40px;" >';
									foreach($this->locations as $key => $location) 
									{
										print "<option value='" .htmlspecialchars($location->name). "'>" .htmlspecialchars($location->name). "</option>";
									}	
								print '</select>';
							}
							else
							{
							   echo 'No Complexes found! Admin needs to create a complex first!';
							}
				?>
        <input type="submit" value="Submit" />
    </form>
	</div>
</div>