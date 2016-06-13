
<div>
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
	<script>var pfHeaderImgUrl = '';var pfHeaderTagline = '';var pfdisableClickToDel = 0;var pfHideImages = 1;var pfImageDisplayStyle = 'right';var pfDisablePDF = 0;var pfDisableEmail = 0;var pfDisablePrint = 0;var pfCustomCSS = '';var pfBtVersion='1';(function(){var js, pf;pf = document.createElement('script');pf.type = 'text/javascript';if('https:' == document.location.protocol){js='https://pf-cdn.printfriendly.com/ssl/main.js'}else{js='http://cdn.printfriendly.com/printfriendly.js'}pf.src=js;document.getElementsByTagName('head')[0].appendChild(pf)})();</script><a href="http://www.printfriendly.com" style="color:#6D9F00;text-decoration:none;" class="printfriendly" onclick="window.print();return false;" title="Printer Friendly and PDF"><img style="border:none;-webkit-box-shadow:none;box-shadow:none;" src="http://cdn.printfriendly.com/pf-button-both.gif" alt="Print Friendly and PDF"/></a>
	<h1 style='margin-top: 50px; color: white;'>List of Trailer Moves</h1>
	<h1 class='white'>Report</h1>
	<table id='container-table' class='purple' border = '1' style='background: #FFF;'>
		<thead>
		<tr>
			<th>
				<span style='font-weight: bold;'>Trailer Name</span>
			</th>
			<th> 
				Trailer Code
			</th>
			<th>
				<span style='font-weight: bold;'>Source Building</span>
			</th>
			<th>
				Source Door
			</th>
			<th>
				<span style='font-weight: bold;'>Destination Building</span>
			</th>
			<th>
				Destination Door</th>
			<th>
				<span style='font-weight: bold;'>Trailer Update Time</span>
			</th>
			<th>
				Notes
			</th>
			<th>
				<span style='font-weight: bold;'>User</span>
			</th>
			<th>
				<span style='font-weight: bold;'>Driver</span>
			</th>
		</tr>
	</thead>
	<tbody>
   
    <?php
		
        if ($this->notes) {
			$i = 0;
           foreach ( $this->notes as $key => $rpt) {
						if (($key + 1) % 22 == 0)
						{
							$i++;
							print "</tbody></table>\n <br><h1 style='margin-top: 50px; color: white;'>List of Trailer Moves</h1>\n<h1 class='white'>Report</h1>\n<table id='container-table' class='purple' border = '1' style='background: #FFF;'>
									<thead><tr><th><span style='font-weight: bold;'>Trailer Name</span></th><th> Trailer Code</th><th><span style='font-weight: bold;'>Source Building</span></th><th>Source Door</th><th><span style='font-weight: bold;'>Destination Building</span></th><th>Destination Door</th><th><span style='font-weight: bold;'>Trailer Update Time</span></th><th>Notes</th><th><span style='font-weight: bold;'>User</span></th><th><span style='font-weight: bold;'>Driver</span></th></tr></thead><tbody>";
						}
						print "<tr> <td><span style='font-weight: bold;'>" . $rpt->trl_name . "</span></td><td>" . $rpt->trl_code . "</td><td><span style='font-weight: bold;'>" . $rpt->bld_src . "</span></td><td>" . $rpt->bld_door_src . "</td><td><span style='font-weight: bold;'>" . $rpt->bld_dst . "</span></td><td>" . $rpt->bld_door_dst . "</td><td><span style='font-weight: bold;'>" . $rpt->time . "</span></td><td>" . $rpt->notes . "</td><td><span style='font-weight: bold;'>" .$rpt->user_full_name. "</span></td><td><span style='font-weight: bold;'>" .$rpt->truck_driver. "</span></td></tr>";
				}
            
        } else {
            echo '<p class="centerText">No results found! Please search again.</p>';
        }
		print "</tbody></table>";
    ?>

</div>
<script type="text/javascript">
<!--
;(function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap('<div class="container-table"/>');
            $t_fixed = $this.clone();
            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
            resizeFixed();
         }
         function resizeFixed() {
            $t_fixed.find("th").each(function(index) {
               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
            });
         }
         function scrollFixed() {
            var offset = $(this).scrollTop(),
            tableOffsetTop = $this.offset().top,
            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
            if(offset < tableOffsetTop || offset > tableOffsetBottom)
               $t_fixed.hide();
            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
               $t_fixed.show();
			   $t_fixed.css('margin-top', -tableOffsetTop);
         }
         $(window).resize(resizeFixed);
         $(window).scroll(scrollFixed);
         init();
      });
   };
})(jQuery);
//-->
<!--
$(document).ready(function(){
   $("table").fixMe();
   
});//--></script> 