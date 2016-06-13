
//method checks local storage to see if chat was minimized 
checkChat = function () {
	// check if localStorage is set, if it is, then minimize chat
	if (localStorage.getItem("display") == 'none') 
	{
		localStorage.setItem("display", "none");
		
		$('#chatUsers').hide();
		$('#chatBottomBar').hide();
		$( "#chatContainer" ).css({
			height: "42px", width: "200px"
		});
	}
};
	// method shows/hides chat 
toggleChat = function () {
	// check if chat is hidden/minimized, make it large
	if (localStorage.getItem("display") == 'none') 
	{
		localStorage.removeItem("display");
		
		$('#chatUsers').show(400);
		$('#chatBottomBar').show(500);
		$( "#chatContainer" ).animate({
			height: "488px", width: "395px"
		}, 500, function() {
		// Animation complete.
		});
	}
	// hide component details
	else 
	{
		localStorage.setItem("display", "none");
		
		$('#chatUsers').hide(500);
		$('#chatBottomBar').hide(500);
		$( "#chatContainer" ).animate({
			height: "42px", width: "200px"
		}, 500, function() {
		// Animation complete.
		});
	}
};



// Custom GET & POST wrappers:

$.tzPOST = function(action,data,callback){
	$.post('http://10.2.0.237/chat/index/?action='+action,data,callback,'json');
}

$.tzGET = function(action,data,callback){
	$.get('http://10.2.0.237/chat/index/?action='+action,data,callback,'json');
}

// A custom jQuery method for placeholder text:

$.fn.defaultText = function(value){
	
	var element = this.eq(0);
	element.data('defaultText',value);
	
	element.focus(function(){
		if(element.val() == value){
			element.val('').removeClass('defaultText');
		}
	}).blur(function(){
		if(element.val() == '' || element.val() == value){
			element.addClass('defaultText').val(value);
		}
	});
	
	return element.blur();
}