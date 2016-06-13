
/* Resize Menu 
*/

$(document).ready(function() {
		//allow double clicking on non-draggable (loading status) trailers to see the notes.
		$('.noDrag').dblclick(function() { redips.details(this.id); });
		//header adjustment when window is resized
		var headerHeight = $('.header').height();
		var containerHeight = $('.container').height();
		$('.fixedTrash').css('margin-top', -(containerHeight / 2));
		if ($('.header').height() < 49) 
		{
			$('.content').css("margin-top", "0px");
			$('.content_dashboard').css("margin-top", "0px");
			$('.header').css("margin-top", "0px");
		 }
		 else
		 {
			$('.content').css("margin-top", (headerHeight - 48));
			$('.content_dashboard').css("margin-top", (headerHeight - 48));
			$('.header').css("margin-top", -(headerHeight - 48));
		 }
		 
if ($(window).width() < 1642 && $(window).width() > 785) {
		
		 $('.header').css("position", "fixed");
		 $('.leftmenu').css("position", "absolute");
}
else if ($(window).width() < 785) {

		 $('.header').css("position", "absolute");
}
else {
      $('.content').css("margin-top", "0px");
	  $('.header').css("margin-top", "0px");
	  $('.header').css("position", "fixed");
	  $('.leftmenu').css("position", "fixed");

}
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('.header').outerHeight();
	 
$(window).scroll( function (e) {
	var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
  if(st > lastScrollTop) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
    //scroll down
    $( ".header" ).addClass( "hide-nav-bar" );
  } else {
    //scroll up
		if(document.body.scrollTop === 0) {
			$( ".header" ).removeClass( "hide-nav-bar" );
		}	
  }
  lastScrollTop = st;
  //prevent page from scrolling
  //return false;
});

//fade out the success message.
$( '.feedback' ).delay(5000).fadeOut(5000);
	
});




//Following line for Navbar
$(function() {
     
    /*
        MENU FOLLOWING LINE
    */
    
    /* Add Magic Line markup via JavaScript, because it ain't gonna work without */
    $(".menu").append("<li id='magic-line'></li>");
    
    /* Cache it */
    var $magicLine = $("#magic-line");
    
    $magicLine
        .width($(".current_page_item a").width() + 20)
        .css("left", $(".current_page_item a").position().left)
        .data("origLeft", $magicLine.position().left)
        .data("origWidth", $magicLine.width());
        
    $(".menu li").find("a").hover(function() {
        $el = $(this);
        leftPos = $el.position().left;
        newWidth = $el.parent().width();
        
        $magicLine.stop().animate({
            left: leftPos,
            width: newWidth
        });
    }, function() {
        $magicLine.stop().animate({
            left: $magicLine.data("origLeft"),
            width: $magicLine.data("origWidth")
        });    
    });
});




