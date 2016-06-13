/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// create redips container
var redips = {};

// functions
var redipsInit,			// define redipsInit variable
	initXMLHttpClient,	// create XMLHttp request object in a cross-browser manner
	sendRequest,		// send AJAX request
	request,			// XMLHttp request object
	printMessage;		// print message
		
// redips initialization
redipsInit = (function () {
	// reference to the REDIPS.drag library and message line
	var	rd = REDIPS.drag,
		msg = document.getElementById('message');
	// how to display disabled elements
	rd.style.borderDisabled = 'solid';	// border style for disabled element will not be changed (default is dotted)
	
	// initialization
	rd.init();
	//check if chat is minimized by user previously
	checkChat();
	// run delegateRadioButtons function to add event listeners to radio buttons
	delegateRadioButtons();
	//start AJAX
	request = initXMLHttpClient();
	
	// print message to the message line
	//printMessage('AJAX version');
							
	// only "shortonly" can be placed to the marked cell
	rd.mark.exception.S45 = 'shortOnly';
	rd.mark.exception.D45 = 'shortOnly';
	rd.mark.exception.C45 = 'shortOnly';
	rd.mark.exception.s45 = 'shortOnly';
	rd.mark.exception.d45 = 'shortOnly';
	rd.mark.exception.c45 = 'shortOnly';
	
	//websocket connection
	  var conn = new WebSocket('ws://10.2.0.237:8083/');
      var mess = document.getElementById('message');
      var connected = false;
      var m = function(string, cname) {
        mess.className = cname;
        mess.innerHTML = string;
      }
      // let us know we are live
      conn.onopen = function(e) {
        m("Connection established!", 'success');
        connected = true;
		conn.send('{"user": {"username" : "' + NAME + '", "avatar": "' + AVATAR + '"}, "type": "userconnecting"}');
      };
      conn.onclose = function(e) {
        m("Connection closed!", 'error');
        connected = false;
      };
	    // when a new message is created
      conn.onmessage = function(e) {
		  
        var data = JSON.parse(e.data);
		//check what kind of response it is 
		switch(data.type) {
			case "status":
				statusChange(data);
				break;
			case "users":
				chat.getUsers(data);
				break;
			case "chat":
				chat.addChatLine(data);
				$('#chatTopBar').repeat(2000).animate({backgroundColor: "#FFCC00"}, 1000).animate({backgroundColor: "#d0d0d0"}, 1000);
				$("#chatTopBar").click(function() { $('#chatTopBar').unrepeat(); });
				$("#chatText").focus(function() { $('#chatTopBar').unrepeat(); });
				break;
			case "loadType":
				productChange(data, "inPlant");
				break;
			case "delete":
				trailerDelete(data);
				break;
			case "updatenotes":
				notesChange(data, "inPlant");
				break;
			case "updatedriver":
				driverChange(data, "inPlant");
				break;
			case "moved":
				newMove(data);
				break;
			default: 
				
		}		
      };
	// prepare handlers
	
	rd.event.clicked = function () {
		msg.innerHTML = 'Clicked';
		document.getElementById(rd.obj.id).style.zIndex = "104";
	};
	rd.event.dblClicked = function () {
		msg.innerHTML = 'Dblclicked ' + rd.obj.id;
		redips.details(rd.obj.id);
	};
	rd.event.moved  = function () {
		msg.innerHTML = 'Moved ' + rd.obj.id;
		
	};
	rd.event.notMoved = function () {
		msg.innerHTML = 'Not moved ';
		if (rd.obj.id !== "CL") {
		redips.details(rd.obj.id);
		}
		
	};
	rd.event.dropped = function () {
		msg.innerHTML = 'Dropped ' + rd.obj.id;
		// get element position (method returns array with current and source positions - tableIndex, rowIndex and cellIndex)
		var pos = rd.getPosition();
		// get building number
		var buildingNumber = document.getElementById('b' + pos[0]).innerHTML;
			//If the move is not a created trailer
			if (!document.getElementById('clonedtxt')) {
				//re-initialization of event listeners for radio buttons (needed for trailer product)
				delegateRadioButtons();
				//open the menu to force the user to pick the driver
				var dialog,
				div = document.getElementById(rd.obj.id + 'nt');	// second child node is hidden DIV (with containing component details) 
					// disable drag
					REDIPS.drag.enableDrag(false);
					//open dialog box
					dialog = $( div ).dialog({
						autoOpen: true,
						height: 300,
						width: 450,
						modal: true,
						close: function() {
							console.log('http://10.2.0.237/dashboard/save?p=' + document.getElementById(rd.obj.id + 'nm').textContent + '_' + document.getElementById(rd.obj.id + 'op').value + '_' + document.getElementById(rd.obj.id + 'no').value + '_' + rd.obj.id + '_' + pos.join('_') + '_' + document.getElementById(rd.obj.id + 'yd').value + '_' + $('input[name=' + rd.obj.id + '_product]:checked').val());
							//send trailer name, option selected, notes, ID, position, and driver
							sendRequest('http://10.2.0.237/dashboard/save?p=' + document.getElementById(rd.obj.id + 'nm').textContent + '_' + document.getElementById(rd.obj.id + 'op').value + '_' + document.getElementById(rd.obj.id + 'no').value + '_' + rd.obj.id + '_' + pos.join('_') + '_' + document.getElementById(rd.obj.id + 'yd').value + '_' + $('input[name=' + rd.obj.id + '_product]:checked').val());
							// object prepare
							var data = {name: NAME, trailerid: rd.obj.id, target: [pos[0], pos[1], pos[2]], plant: plant, source: [pos[3], pos[4], pos[5]], deleted: false, targetcell: rd.td.target.id, trailerName: document.getElementById(rd.obj.id + 'nm').textContent, building: buildingNumber, product: document.getElementById(rd.obj.id + 'p').innerHTML, updatedriver: document.getElementById(rd.obj.id + 'yd').value, updatenotes: document.getElementById(rd.obj.id + 'no').value, type: "moved"};
							// send the data
							conn.send(JSON.stringify(data));
							//disable moving trailer that is dropped in another plant
							if(pos[0] == 0) {
								//document.getElementById(rd.obj.id).classList.remove("drag");
								var trailerId = document.getElementById(rd.obj.id);
								// unregister event listener for DIV element
								trailerId.onmousedown = null;
								trailerId.ontouchstart = null;
								// set cursor for DIV element
								trailerId.style.cursor = 'auto';
								// set enabled property to false
								trailerId.redips.enabled = false;
							}
							//set driver to null
							document.getElementById(rd.obj.id + 'yd').value = "";
							document.getElementById(rd.obj.id + 'no').value = "";
						}
					});
						$(document).mouseup(function (e)
						{
							var container = $(div);
							if (!container.is(e.target) // if the target of the click isn't the container...
								&& container.has(e.target).length === 0) // ... nor a descendant of the container
							{
								dialog.dialog( "close" );
								REDIPS.drag.enableDrag(true);
							}
						});
				
			}
			//If the move is a created trailer
			else 
			{
				//alert(document.getElementById('clonedtxt').value + '_' + document.getElementById('clop').value + '_' + document.getElementById('clno').value + '_' + rd.obj.id + '_' + pos.join('_'));
				console.log('http://10.2.0.237/dashboard/save?p=' + (document.getElementById('clonedtxt').value).toUpperCase() + '_' + document.getElementById('CLop').value + '_' + document.getElementById('CLno').value + '_' + rd.obj.id + '_' + pos.join('_') + '_' + document.getElementById(rd.obj.id + 'yd').value);
				sendRequest('http://10.2.0.237/dashboard/save?p=' + (document.getElementById('clonedtxt').value).toUpperCase() + '_' + document.getElementById('CLop').value + '_' + document.getElementById('CLno').value + '_' + rd.obj.id + '_' + pos.join('_') + '_' + document.getElementById(rd.obj.id + 'yd').value);
				checkDuplication();
				setTimeout(function() {
					if ($(".feedback").length > 0) 
					{
						var code = plant.substring(0,3);
							function pad(n) {
								return (n < 10) ? ("0" + n) : n;
							}
							
						document.getElementById(code + pad(pos[0]) + pad(pos[1]) + '_' + pad(pos[2])).lastChild.remove();
					}
					else 
					{
						// object prepare
						var data = {name: NAME, trailerid: rd.obj.id, target: [pos[0], pos[1], pos[2]], plant: plant, source: [pos[3], pos[4], pos[5]], deleted: false, targetcell: rd.td.target.id, trailerName: document.getElementById('clonedtxt').value, building: buildingNumber, type: "moved"};
						//remove the text field from the clone
						document.getElementById('clonedtxt').remove();
						// send the data
						conn.send(JSON.stringify(data));
						
					}
				}, 200);
				delegateRadioButtons();
			}
			
	};
	// DELETE TRAILER Function
	function trailerDelete(obj) {
				if(obj.plant == plant) {
					try {
					  document.getElementById(obj.trailerid).remove();
					  msg.innerHTML = obj.trailerName + " was deleted";
					  if(obj.name !== NAME) {
					  $().toastmessage('showToast', {
									text     : obj.trailerName + " was deleted",
									sticky   : true,
									position : 'top-right',
									type     : 'warning',
									closeText: ''
									});
					  }
					}
					catch(err) {
						console.log(err + ' ' + obj.trailerid);
					}
				}	  
	};
	
	// NEW MOVE FUNCTION called by websockets
      function newMove(obj) {//if move was in same plant
		var trlrId = (obj.trailerid).toUpperCase();
		  if (plant == obj.plant) 
		  {//create trailer if the trailer is created in plant
			  if(!document.getElementById(obj.trailerid)) {
					
				    var cloneid = document.getElementById('CL');
					var clonednode = rd.createObject(cloneid, trlrId);
					var code = plant.substring(0,3);
						function pad(n) {
							return (n < 10) ? ("0" + n) : n;
						}
					document.getElementById(code + pad(obj.target[0]) + pad(obj.target[1]) + '_' + pad(obj.target[2])).appendChild(clonednode);
					$('#clonedtxt').remove();
					document.getElementById(trlrId + 'nm').innerHTML = obj.trailerName;
					var responseText = obj.trailerName + " created and moved to " + obj.building + " door " + obj.target[1] * (obj.target[2] + 1) + ' ' + plant;
					msg.innerHTML = responseText;
					//Notify all other users but not the user that made the move 
					if(obj.name !== NAME) {
					$().toastmessage('showToast', {
												text     : responseText,
												sticky   : true,
												position : 'top-right',
												type     : 'warning',
												closeText: ''
												});
					}
					//add attribute to labels
					for (var r = 0; r < 13; r++) {
						function pad(n) {
							return (n < 10) ? ("0" + n) : n;
						}
						document.getElementById(obj.trailerid + 'lb' + pad(r)).setAttribute('for', obj.trailerid + 'noop' + pad(r));
					}
			  }
			  else {
					msg.innerHTML = obj.trailerName + " moved to " + obj.building + " door " + obj.target[1] * (obj.target[2] + 1) + ' ' + plant;
					REDIPS.drag.moveObject({
											id: obj.trailerid,
											target: obj.target
											});
			  }	
		  }
		  else
		  {//create trailer that is coming from another plant
			  if(obj.target[0] == 0) {
				  var cloneid = document.getElementById('CL');
					var clonednode = rd.createObject(cloneid, trlrId);
						function pad(n) {
							return (n < 10) ? ("0" + n) : n;
						}

					document.getElementById(obj.targetcell).appendChild(clonednode);
					document.getElementById(obj.trailerid + 'nm').innerHTML = obj.trailerName;
					document.getElementById('clonedtxt').remove();
					//add attribute to labels
					for (var r = 0; r < 9; r++) {
						document.getElementById(obj.trailerid + 'lb' + pad(r)).setAttribute('for', obj.trailerid + 'noop' + pad(r));
					}
					var prd = { trailerid: obj.trailerid + 'noop12', product: obj.product};
					var note = { trailerid: obj.trailerid + 'no', updatenotes: obj.updatenotes };
					var driver = { trailerid: obj.trailerid + 'yd', updatedriver: obj.updatedriver };
					
					productChange(prd, "plantMove");
					notesChange(note, "plantMove");
					driverChange(driver, "plantMove");
					msg.innerHTML = obj.trailerName + " trailer is on its way to " + obj.plant;
			  }// delete trailer that is picked up.
			  else {
				 if(obj.source[0] == 0) {
					rd.deleteObject(obj.trailerid);
				 }
			  }
		  }
      };
	  
	rd.event.switched = function () {
		msg.innerHTML = 'Switched';
	};
	rd.event.clonedEnd1 = function () {
		msg.innerHTML = 'Cloned end1';
	};
	rd.event.clonedEnd2 = function () {
		msg.innerHTML = 'Cloned end2';
	};
	rd.event.notCloned = function () {
		msg.innerHTML = 'Not cloned';
	};
	rd.event.deleted = function (cloned, trlName, obj) {
		var pos = rd.getPosition(),
			row = pos[4],
			col = pos[5];
		// if cloned element is directly moved to the trash
		if (/DT[0-9]/.test(rd.obj.id.substring(0, 3)) || /SH[0-9]/.test(rd.obj.id.substring(0, 3)) || /SP[0-9]/.test(rd.obj.id.substring(0, 3)) || /NS[0-9]/.test(rd.obj.id.substring(0, 3))) {
			// set id of original element (read from redips property)
			// var id_original = rd.obj.redips.id_original;
			var questionDelete = confirm("Are you sure you want to delete a DT or SH trailer?");
			if(questionDelete == false) {
			var code = plant.substring(0,3);
						function pad(n) {
							return (n < 10) ? ("0" + n) : n;
						}
				document.getElementById(code + pad(pos[3]) + pad(pos[4]) + '_' + pad(pos[5])).appendChild(obj);
			}
			else {
			msg.innerHTML = 'Deleted ' + trlName;
			// get element position
		
			// delete element
			sendRequest('http://10.2.0.237/dashboard/delete?d=' + trlName + '_' + rd.obj.id + '_' + row + '_' + col);
			// object prepare
			var data = {name: NAME, trailerid: rd.obj.id, trailerName: trlName, target: [pos[0], pos[1], pos[2]], plant: plant, source: [pos[3], pos[4], pos[5]], type: "delete"};
			// send the data
			conn.send(JSON.stringify(data));
			}
		}
		else {
			msg.innerHTML = 'Deleted ' + trlName;
			// get element position
		
			// delete element
			sendRequest('http://10.2.0.237/dashboard/delete?d=' + trlName + '_' + rd.obj.id + '_' + row + '_' + col);
			// object prepare
			var data = {name: NAME, trailerid: rd.obj.id, trailerName: trlName, target: [pos[0], pos[1], pos[2]], plant: plant, source: [pos[3], pos[4], pos[5]], type: "delete"};
			// send the data
			conn.send(JSON.stringify(data));
		}
	};
	rd.event.undeleted = function () {
		msg.innerHTML = 'Undeleted';
	};
	rd.event.cloned = function () {
		// display message
		msg.innerHTML = 'Cloned';

	};
	rd.event.changed = function () {
		// get target and source position (method returns positions as array)
		var pos = rd.getPosition();
		if(pos[0] == 0) {
			document.getElementById("createTable").style.zIndex = "0";
		}
		else if (pos[0] >= 1) {
			document.getElementById("createTable").style.zIndex = "3";
			document.getElementById(rd.obj.id).style.zIndex = "111111";
		}
		rd.hover.colorTd = 'green';
		
		// display current row and current cell
		msg.innerHTML = 'Changed: ' + pos[1] + ' ' + pos[2];
	};


if (!document.getElementById("canDrag").checked) {
		REDIPS.drag.enableDrag(false);
		
}
$('#canDrag').change(function() {
	if (!document.getElementById("canDrag").checked) {
		REDIPS.drag.enableDrag(false);
	}
	else {
	REDIPS.drag.enableDrag(true);
	}
});

//check the selected drop down for trailer status
$( document ).delegate('select','change', function() {
	
var selectedValue= document.getElementById(this.id);
	var trailerId = document.getElementById(this.id.slice(0, -2));
	var trailerName = document.getElementById(trailerId.id + 'nm').innerHTML;
        var selectStatus = selectedValue.options[selectedValue.selectedIndex].value;
		var values = 's=' + this.id + '_' + selectStatus;
		$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: values,
										success: function(){
										},
										error:function(){
										alert("failure");
										}
									});
        if (selectStatus == "Loading") {
			selectedValue.className = "";
			selectedValue.className = "Loading";
			if(ACCOUNT > 1) 
			{
			$(trailerId).removeClass('drag').addClass('noDrag');
				// unregister event listener for DIV element
				trailerId.onmousedown = null;
				trailerId.ontouchstart = null;
				// set cursor for DIV element
				trailerId.style.cursor = 'not-allowed';
				// set enabled property to false
				trailerId.redips.enabled = false;
			}
			// object prepare
			var data = {name: NAME, trailerid: this.id, status: selectStatus, trailerName: trailerName, plant: plant, type: "status"};
			// send the data
			conn.send(JSON.stringify(data)); 
        }
        else {
			selectedValue.className = "";
			selectedValue.className = selectStatus;
			if(ACCOUNT > 1) {
			$(trailerId).removeClass('noDrag').addClass('drag');
			REDIPS.drag.init();
			}
			 // object prepare
			var data = {name: NAME, trailerid: this.id, status: selectStatus, plant: plant, trailerName: trailerName, type: "status"};
			// send the data
			conn.send(JSON.stringify(data));
			
        }
		//unfocus the select field
		$( this ).blur();
		//nullify values
		selectedValue = null;
		trailerId = null;
});

function delegateRadioButtons() {
//save the load type selection for radio options
$('input[type=radio]').click(function() {
	var selectedValue = document.getElementById(this.id).value;
	var trailerId = document.getElementById(this.id.slice(0, -6));
	document.getElementById(this.id.slice(0, -6) + 'p').innerHTML = selectedValue;
		var values = 'r=' + this.id + '_' + selectedValue;
		$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: values,
										success: function(){
										},
										error:function(){
										alert("failure");
										}
									});
		 // object prepare
		var data = {name: NAME, trailerid: this.id, product: selectedValue, plant: plant, type: "loadType"};
		// send the data
		conn.send(JSON.stringify(data));
		selectedValue = null;
		trailerId = null;
		values = null;
});
};

//check for notes input
$('.notes').keyup(function() {
	//check if notes or other input field
		var id = this.id;
		var  timeout = 2e3; // 2 seconds default timeout
                    // reset the clock 
                     clearTimeout(timeoutReference);   
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire doneTyping
                      doneTyping(id);
                    }, timeout);
	
		var notes = document.getElementById(this.id).value;
		if (notes == null || notes == "") {
			notes = " ";
		}
		 // object prepare
		var data = {name: NAME, trailerid: this.id, updatenotes: notes, type: "updatenotes", plant: plant};
		// send the data
		conn.send(JSON.stringify(data));
	

});

//check for driver input
$('.selectDriver').change(function() {
	//check if notes or other input field
		var driver = document.getElementById(this.id).value;
		if (driver == null || driver == "") {
			driver = " ";
		}		
		 // object prepare
		var data = {name: NAME, trailerid: this.id, updatedriver: driver, plant: plant, type: "updatedriver"};
		// send the data
		conn.send(JSON.stringify(data));
});
	
var chat = {
	// data holds variables for use in the class:
	data : {
		lastID 		: 0,
		noActivity	: 0
	},
	// Init binds event listeners and sets up timers:
	init : function(){	
		// Converting the #chatLineHolder div into a jScrollPane,
		// and saving the plugin's API in chat.data:
		
		chat.data.jspAPI = $('#chatLineHolder').jScrollPane({
			verticalDragMinHeight: 12,
			verticalDragMaxHeight: 12
		}).data('jsp');
		
		// We use the working variable to prevent
		// multiple form submissions:
		
		var working = false;

		
		// Submitting a new chat entry:
		
		$('#submitForm').submit(function(){
			
			var text = $('#chatText').val();
			
			if(text.length == 0){
				return false;
			}
			
			if(working) return false;
			working = true;
			
			// Assigning a temporary ID to the chat:
			var tempID = 't'+Math.round(Math.random()*1000000),
				params = {
					id			: tempID,
					author		: chat.data.name,
					gravatar	: chat.data.gravatar,
					text		: text.replace(/</g,'&lt;').replace(/>/g,'&gt;'),
					type        : "chat",
					plant       : plant
				};

			// Using our addChatLine method to add the chat
			// to the screen immediately, without waiting for
			// the AJAX request to complete:		
			chat.addChatLine($.extend({},params));	
			// send the data
			conn.send(JSON.stringify(params));	
			// Using our tzPOST wrapper method to send the chat
			// via a POST AJAX request:
			
			$.tzPOST('submitChat',$(this).serialize(),function(r){
				working = false;
				
				$('#chatText').val('');
				$('div.chat-'+tempID).remove();
				
				params['id'] = r.insertID;
				chat.addChatLine($.extend({},params));
			});
			
			return false;
		});
				
		// Checking whether the user is already logged (browser refresh)
		
		$.tzGET('checkLogged',function(r){
			if(r.logged){
				chat.login(r.loggedAs.name,r.loggedAs.gravatar);
			}
		});		
		// Self executing timeout functions	
		(function getChatsTimeoutFunction(){
			chat.getChats(getChatsTimeoutFunction);
		})();		
	},
	
	// The login method hides displays the
	// user's login data and shows the submit form
	login : function(name,gravatar){	
		chat.data.name = name;
		chat.data.gravatar = gravatar;
		$('#chatTopBar').html(chat.render('loginTopBar',chat.data));	
			$('#submitForm').fadeIn();
			$('#chatText').focus();
	},
	
	// The render method generates the HTML markup 
	// that is needed by the other methods:
	render : function(template,params){	
		var arr = [];
		switch(template){
			case 'loginTopBar':
				arr = [
				'<span><img src="',params.gravatar,'" width="23" height="23" />',
				'<span class="name">',params.name,
				'</span>'];
			break;
			
			case 'chatLine':
				arr = [
					'<div class="chat chat-',params.id,' rounded"><span class="author">',params.author,
					'</span><span class="text">',params.text,'</span><span class="time">',params.time,'</span></div>'];
				
			break;
			
			case 'user':
				arr = [
					'<div class="user" title="',params.name,'"><img src="',
					params.gravatar,'" width="30" height="30" onload="this.style.visibility=\'visible\'" /></div>'
				];
			break;
		}
		
		// A single array join is faster than
		// multiple concatenations
		
		return arr.join('');
		
	},
	
	// The addChatLine method ads a chat entry to the page
	
	addChatLine : function(params){
		
		// All times are displayed in the user's timezone
		
		var d = new Date();
	
		
				if(params.time) {
			
			// PHP returns the time in UTC (GMT). We use it to feed the date
			// object and later output it in the user's timezone. JavaScript
			// internally converts it for us.
			
			d.setUTCHours((params.time.hours) ,params.time.minutes);
		}
		
		params.time = (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
					  (d.getMinutes() < 10 ? '0':'') + d.getMinutes();
		
		var markup = chat.render('chatLine',params),
			exists = $('#chatLineHolder .chat-'+params.id);

		if(exists.length){
			exists.remove();
		}
		
		if(!chat.data.lastID){
			// If this is the first chat, remove the
			// paragraph saying there aren't any:
			
			$('#chatLineHolder p').remove();
		}
		
		// If this isn't a temporary chat:
		if(params.id.toString().charAt(0) != 't'){
			var previous = $('#chatLineHolder .chat-'+(+params.id - 1));
			if(previous.length){
				previous.after(markup);
			}
			else chat.data.jspAPI.getContentPane().append(markup);
		}
		else chat.data.jspAPI.getContentPane().append(markup);

		//check if posted by user, change color.
		if(params.author == NAME) {
			$('#chatLineHolder .chat-'+params.id).addClass('alert-success');
		}
		else {
			$('#chatLineHolder .chat-'+params.id).addClass('alert-info');
		}
		// As we added new content, we need to
		// reinitialise the jScrollPane plugin:
		
		chat.data.jspAPI.reinitialise();
		chat.data.jspAPI.scrollToBottom(true);
		
	},
	
	// This method requests the latest chats
	// (since lastID), and adds them to the page.
	
	getChats : function(){
		$.tzGET('getChats',{lastID: chat.data.lastID},function(r){
			
			for(var i=0;i<r.chats.length;i++){
				chat.addChatLine(r.chats[i]);
			}
			
			if(r.chats.length){
				chat.data.lastID = r.chats[i-1].id;
				
			}
			
			if(!chat.data.lastID){
				chat.data.jspAPI.getContentPane().html('<p class="noChats">No chats yet</p>');
			}
			
		});
	},
	
	// Requesting a list with all the users.
	
	getUsers : function(r){

			var users = [];
			
			for(var i=0; i< r.users.length;i++){
				if(r.users[i]){
					users.push(chat.render('user',r.users[i]));
				
				}
			}
			
			var message = '';
			
			if(r.total<1){
				message = 'No one is online';
			}
			else {
				message = r.total+' '+(r.total == 1 ? 'person':'people')+' online';
			}
			
			users.push('<p class="count">'+message+'</p>');
			
			$('#chatUsers').html(users.join(''));
			
		
	},
	
	// This method displays an error message on the top of the page:
	
	displayError : function(msg){
		var elem = $('<div>',{
			id		: 'chatErrorMessage',
			html	: msg
		});
		
		elem.click(function(){
			$(this).fadeOut(function(){
				$(this).remove();
			});
		});
		
		setTimeout(function(){
			elem.click();
		},5000);
		
		elem.hide().appendTo('body').slideDown();
	}
};
chat.init();
	//get Driver names and append to all drivers input
	$.get( URL + "dashboard/drivers", function( data ) {
		$('.driverSelect').html(data);
	});
	
//toggle big and small all trailers div
$( "#maxmin" ).mousedown(function() {
				if ( $('#alltrailers').height() < 2408) 
				{
					$( '#alltrailers' ).animate({ height: 2409, width: 1048 }, 500 );
					var value = 'min=2';
						$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: value,
										success: function(){
										},
										error:function(){
										
										}
									});	
						value = null;
				}
				else
				{
					$( '#alltrailers' ).animate({ height: 210, width: 265 }, 500 );
					var value = 'min=1';
						$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: value,
										success: function(){
										},
										error:function(){
										console.log("failure");
										}
									});
						value = null;
				}
});

//check create trailers height. If too high for screen, then change to absolute.
$("#createTable").on("resize", function() {
	if($("#createTable").height() > 900) {
		$("#createTable").css({position: 'absolute'});
	}
	else {
		$("#createTable").css({position: 'fixed'});
	}
});
//make all trailers table draggable
$('#alltrailers').draggable({ scroll: true, containment: '#drag', stop: function() { 
                var coords=[];
                var coord = $(this).position();
                var item={ coordTop:  coord.top, coordLeft: coord.left  };
                coords.push(item);
                var order = { coords: coords };
                $.post(URL + 'dashboard/save', 'data='+$.toJSON(order)); 
                }}).dblclick(function() {
				if ( $('#alltrailers').height() != 2409) 
				{
					$( '#alltrailers' ).animate({ height: 2409, width: 1048 }, 500 );
					var value = 'min=2';
						$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: value,
										success: function(){
										},
										error:function(){
										
										}
									});
						value = null;
				}
				else
				{
					$( '#alltrailers' ).animate({ height: 210, width: 265 }, 500 );
					var value = 'min=1';
						$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: value,
										success: function(){
										},
										error:function(){
										console.log("failure");
										}
									});	
						value = null;
				}
				coords = null;
				coord = null;
				item = null;
				order = null;
});
});

//Trailer status change
var statusChange = function(obj) {
  if(plant == obj.plant) {
   try {
    var sel = document.getElementById(obj.trailerid);
	var trailerId = document.getElementById(obj.trailerid.slice(0, -2));
    var opts = sel.options;
    for(var opt, j = 0; opt = opts[j]; j++) {
        if(opt.value == obj.status) {
            sel.selectedIndex = j;
            break;
        }
    }
	if (obj.status == "Loading") {
		sel.className = "";
		sel.className = obj.status;
		if(ACCOUNT > 1) {
		$(trailerId).removeClass('drag').addClass('noDrag');
		// unregister event listener for DIV element
		trailerId.onmousedown = null;
		trailerId.ontouchstart = null;
		// set cursor for DIV element
		trailerId.style.cursor = 'not-allowed';
		// set enabled property to false
		trailerId.redips.enabled = false;
		}
	}
	else {
	sel.className = "";
	sel.className = obj.status;
		if(ACCOUNT > 1) {
			$(trailerId).removeClass('noDrag').addClass('drag');
			REDIPS.drag.init();
		}
	}
	
	var responseText = "Trailer " + obj.trailerName + " status has been changed to " + obj.status;
	if(obj.name !== NAME) {
	$().toastmessage('showToast', {
									text     : responseText,
									sticky   : true,
									position : 'top-right',
									type     : 'warning',
									closeText: ''
									});
	}
  }
  catch(err) {
	console.log(err + ' ' + obj.trailerid);
  }
 }  
 
};

//Product change (websocket)
var productChange = function(obj, move) {
	try {
		if(obj.plant == plant || move == "plantMove") {
			var sel = document.getElementById(obj.trailerid);
			sel.checked = true;
			document.getElementById(obj.trailerid.slice(0, -6) + 'p').innerHTML = obj.product;
		}
	}
	catch(err) {
	console.log(err + ' ' + obj.trailerid);
  }
};

//update notes live (websocket)
var notesChange = function(obj, move) {
	try {
		if(obj.plant == plant || move == "plantMove") {
			document.getElementById(obj.trailerid).value = obj.updatenotes;
		}
	}
	catch(err) {
	console.log(err + ' ' + obj.trailerid);
	}
};
//update driver live (websocket)
var driverChange = function(obj, move) {
	try {
		if(obj.plant == plant || move == "plantMove") {
			document.getElementById(obj.trailerid).value = obj.updatedriver;
		}
	}
	catch(err) {
	console.log(err + ' ' + obj.trailerid);
	}
};

// toggles trash_ask parameter defined at the top
function toggleConfirm() {

	if (localStorage.getItem("toggleConfirm") == "true") {
		REDIPS.drag.trash.question = null;
		localStorage.setItem("toggleConfirm", "false");
		document.getElementById("confirmDelete").checked = false;
	}
	else {
		localStorage.setItem("toggleConfirm", "true");
		
		REDIPS.drag.trash.question = 'Are you sure you want to delete the trailer?';
		document.getElementById("confirmDelete").checked = true;
	}
};


// XMLHttp request object for saving to the database
initXMLHttpClient = function () {
	var XMLHTTP_IDS,
		xmlhttp,
		success = false,
		i;
	// Mozilla/Chrome/Safari/IE7/IE8 (normal browsers)
	try {
		xmlhttp = new XMLHttpRequest(); 
	}
	// IE (?!)
	catch (e1) {
		XMLHTTP_IDS = [ 'MSXML2.XMLHTTP.5.0', 'MSXML2.XMLHTTP.4.0',
						'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP', 'Microsoft.XMLHTTP' ];
		for (i = 0; i < XMLHTTP_IDS.length && !success; i++) {
			try {
				success = true;
				xmlhttp = new ActiveXObject(XMLHTTP_IDS[i]);
			}
			catch (e2) {}
		}
		if (!success) {
			throw new Error('Unable to create XMLHttpRequest!');
		}
	}
	return xmlhttp;
};


// function sends AJAX request to the server (save or delete)
// input parameter is complete URL of service with query string 
sendRequest = function (url) {

	// open asynchronus request
	request.open('POST', url, true);
	// the onreadystatechange event is triggered every time the readyState changes
	request.onreadystatechange = function () {
		//  request finished and response is ready
		if (request.readyState === 4) {
			// if something went wrong
			if (request.status !== 200) {
				// display error message
				document.getElementById('message').innerHTML = 'Error: [' + request.status + '] ' + request.statusText;
			}
			else if(request.status == 200) {

					
			}
	    }
	};
	// send request
	request.send(null);
};

// print message
printMessage = function (message) {
	document.getElementById('message').innerHTML = message;
};

// function shows/hides tables in page containers
// input parameters are button reference (to change button label) and id of page container
 redips.toggle = function (btn, page_id) {
	var page = document.getElementById(page_id);
	
		
	if (page.style.display === '') {
		if (page_id == "trailerlot") {
			document.getElementById('alltrailers').style.width = '90px';
		}
		
		sendRequest('http://10.2.0.237/dashboard/save?ta=1');
		page.style.display = 'none';
		btn.style.border = '2px grey solid';
	}
	else {
			if (page_id == "trailerlot") {
			document.getElementById('alltrailers').style.width = 'auto';
			}
		sendRequest('http://10.2.0.237/dashboard/save?ta=2');
		page.style.display = '';
		btn.style.border = '2px green solid';
	}
};

// find container and return container id
redips.findContainer = function (c) {
	// loop up until found target DIV container 
	while (c && c.id !== redips.left && c.id !== redips.right) {
		c = c.parentNode;
	}
    // return container id
    return c.id;
};

// method shows/hides details of DIV elements sent as input parameter 
redips.details = function (id) {	

	var dialog,
		// find parent DIV element
		div = document.getElementById(id + 'nt');	// second child node is hidden DIV (with containing component details) 
		
	// show component details
		REDIPS.drag.enableDrag(false);
		dialog = $( div ).dialog({
			autoOpen: true,
			height: 300,
			width: 450,
			modal: true
		});
		$(document).mouseup(function (e)
		{
			var container = $(div);

			if (!container.is(e.target) // if the target of the click isn't the container...
				&& container.has(e.target).length === 0) // ... nor a descendant of the container
			{
				dialog.dialog( "close" );
				REDIPS.drag.enableDrag(true);
				//set driver to null
				document.getElementById(id + 'yd').value = "";
			}
		});
};

//applyProperties function counts the amount of ids in the array contIDArray and creates a new string of the array
 var toggleDisableDoors = function() {
	if (document.getElementById('confirmDisableDoors').checked === true) {
		$("table").delegate("td", "click", function() {
					var ajax = function (values) 
					{
						$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: values,
										success: function(){
										},
										error:function(){
										alert("failure");
										}
									});
					}	
				if ($(this).attr('class') == "mark" ) 
				{
						$(this).css('background-color', '#e0e0e0');
					if (confirm("Make this door restricted to 48 footer trailers? Ok for Yes. Cancel for No.") == true) {
						var note = prompt("Why was a change made?");
						$(this).attr("class", plant + " mark shortOnly");
						$(this).attr("title", "48 Only: " + note);
						$(this).css('background-color',  '#666666');
						var values = "q=" + $(this).text() + '_' + this.id.substring(5, 3) + '_' + "only48" + '_' + note;
						ajax(values);
					} else {
						$(this).css("class", plant);
						var values = "q=" + $(this).text() + '_' + this.id.substring(5, 3) + '_' + "normal" + '_' + note;
						ajax(values);
					}
				}
				else {
				$(this).attr('class', 'mark');
				var note = prompt("Why was a change made?");
				$(this).attr('title', 'Disabled: ' + note);
				$(this).css('background-color', 'rgb(0, 0, 0)');
				var values = "q=" + $(this).text() + '_' + this.id.substring(5, 3) + '_' + "disabled" + '_' + note;
				ajax(values);
				}
		});
	}
	else
	{
	$("table").undelegate("td", "click");
	}
};	
var timeoutReference, doneTyping = function(id){
                    timeoutReference = null;
					var notes = document.getElementById(id).value;
                    saveNotes(notes, id);
                };				
//send the notes to trailers
 var saveNotes = function(notes, id) {
 
      var values = "n=" + notes + '_' + id.slice(0, -2);
		$.ajax({
										url: URL + "dashboard/save",
										type: "POST",
										data: values,
										success: function(){
										},
										error:function(){
										alert("failure");
										}
									});
			values = null;
	};

//check for duplication
function checkDuplication() 
{
		$.ajax({
				url: URL + 'dashboard/duplicationError',
				type: "POST",
				success: function(html) {
					if(html.substring(0, 24) == '<div class="feedback">DU') {
						$('#error').append(html);
						$('.feedback').delay(5000).fadeOut(5000);
						setTimeout(function() { $('.feedback').remove(); }, 8000);
					}
				},
				error:function(){
									alert("failure");
								}
			});
};


//update appCache **!!NOT USED YET BECAUSE OF ISSUES!!**
/*
function updateAppCache() {
	var appCache = window.applicationCache;
		appCache.update(); // Attempt to update the user's cache.
			if (appCache.status == window.applicationCache.UPDATEREADY) {
				appCache.swapCache();  // The fetch was successful, swap in the new cache.
			}
};	
*/					
// add onload event listener
if (window.addEventListener) {
	window.addEventListener('load', redipsInit, false);
}
else if (window.attachEvent) {
	window.attachEvent('onload', redipsInit);
}
