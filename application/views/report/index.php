<!-- CSS -->
	<link rel="stylesheet" href="../public/js/jquery-ui-1.11.2/jquery.ui.datepicker.css" />
<!-- JavaScript -->
	<script type="text/javascript" src="../public/js/jquery-ui-timepicker-addon.js"></script>
<div class="content">
    <h1>Search Reports</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
	<div class="center">
    <form method="post" target="_blank" action="<?php echo URL;?>report/report">
        <label>Trailer Name or Code: </label><i class="icon-search" style="position: absolute; margin-top:10px; margin-left: -3px; z-index:99;"></i><input class="big" type="text" name="trailer_code" />
		<label>From (Date & Time):</label><input type="text" name="filter_date" value="" size="12" class="date" /><i class="icon-calendar"></i>
		<label>To (Date & Time):</label><input type="text" name="filter_date_last" value="" size="12" class="date" /><i class="icon-calendar"></i>
        <input type="submit" value='Search' autocomplete="off" />
    </form>
	</div>
  </div>
  <script type="text/javascript"><!--
var myControl=  {
	create: function(tp_inst, obj, unit, val, min, max, step){
		$('<input class="ui-timepicker-input" value="'+val+'" style="width:50%">')
			.appendTo(obj)
			.spinner({
				min: min,
				max: max,
				step: step,
				change: function(e,ui){ // key events
						// don't call if api was used and not key press
						if(e.originalEvent !== undefined)
							tp_inst._onTimeChange();
						tp_inst._onSelectHandler();
					},
				spin: function(e,ui){ // spin events
						tp_inst.control.value(tp_inst, obj, unit, ui.value);
						tp_inst._onTimeChange();
						tp_inst._onSelectHandler();
					}
			});
		return obj;
	},
	options: function(tp_inst, obj, unit, opts, val){
		if(typeof(opts) == 'string' && val !== undefined)
			return obj.find('.ui-timepicker-input').spinner(opts, val);
		return obj.find('.ui-timepicker-input').spinner(opts);
	},
	value: function(tp_inst, obj, unit, val){
		if(val !== undefined)
			return obj.find('.ui-timepicker-input').spinner('value', val);
		return obj.find('.ui-timepicker-input').spinner('value');
	}
};
$(document).ready(function() {
	$('.date').datetimepicker({controlType: myControl, dateFormat: 'yy-mm-dd'});
});
//-->
</script> 