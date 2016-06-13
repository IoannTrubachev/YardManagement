<div class="content">
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <h1>Admin Tools</h1>
<div class="cr">
			<h1 id="create">Create Complex</h1>
				<form method="POST" name="createComplex"> 
					<fieldset>
						<legend><strong>Complex, Buildings, and Doors:</strong></legend>
							<br>
							<div class="left">
							<br>
							<select id="complexOptions" name="optionIs">
								<option value="Create">Create new Complex</option>
								<option value="AddBuilding">Add Building and Doors</option>
								<option value="AddDoors">Add Doors Only</option>
								<option value="DeleteBuilding">Delete Building or Door</option>
								<option value="AddLots">Add Lots</option>
							</select>
							<br><br>
							<strong>Complex Name:</strong><br>
							<input type="text" name="complex" value="" >
							<br><br>
							<div id="complexForm" ><span class='warning'>*** Type the buildings in ORDER ***</span><br><br>
								<span id="id_0">
									<input type="text" class="building" placeholder="Building #" name="building[]" pattern="[0-9]{1,3}" required title="Numbers Only! 3 characters max!" maxlength="3" min="1" max="300" id="building_0">
								</span>
								<span id="id_0">
									<input type="text" class="door" placeholder="Doors (Include all doors)" name="doors[]" pattern="[0-9]{1,3}" required title="Numbers Only! 3 characters max!" maxlength="3" min="1" max="300" id="doors_0">
									<img src="http://10.2.0.237:888/dty/public/img/delete.png" onclick="removeElement('complexForm', 'id_0')">
								</span>
							</div>
							<br><br>
							<input id='add' type="button" onclick="buildingFunction()" value="Add Building"></button>
							<br><br>
							<input id="sub" type="submit"  value="Submit">
							</div> <!-- END Left Div --> 
					</fieldset>	
				</form> <!-- END Create Complex -->
		</div> <!-- END Cr Div -->
</div>

<script>
//Javascript for complex. Creates a text field for Building and Door.
var io= 0; /* Set Global Variable io */
function increment(){
io += 1; /* Function for automatic increment of field's "Name" attribute. */
}
function removeElement(parentDiv, childDiv){
if (childDiv == parentDiv){
alert("The parent div cannot be removed.");
}
else if (document.getElementById(childDiv)){
	while (document.getElementById(childDiv)) {
var child = document.getElementById(childDiv);
var parent = document.getElementById(parentDiv);
parent.removeChild(child);
}
}
else{
alert("Child div has already been removed or does not exist.");
return false;
}
};
	function buildingFunction(){
		var re = document.createElement('span');
		var ye = document.createElement("INPUT");
			ye.setAttribute("type", "text");
			ye.setAttribute("class", "building");
			ye.setAttribute("name", "building[]");
			ye.setAttribute("required", true);
		var ra = document.createElement('span');
		var ba = document.createElement('br');
		var ya = document.createElement("INPUT");
			ya.setAttribute("type", "text");
			ya.setAttribute("class", "door");
			ya.setAttribute("name", "doors[]");
			ya.setAttribute("maxlength", "3");
			ya.setAttribute("pattern", "[0-9]{1,3}");
			ya.setAttribute("required", true);
			ya.setAttribute("title", "Numbers Only! 3 characters max!");
			if (document.getElementById("complexOptions").value == "AddLots") {
				ya.setAttribute("placeholder", "Lot Spots (increments of 5)");
				ye.setAttribute("placeholder", "Lot Name");
				ye.setAttribute("pattern", "[A-Za-z0-9 ]{1,35}");
				ye.setAttribute("title", "Alphanumeric Only! 35 characters max!");
				ye.setAttribute("maxlength", "35");
			}
			else {
				ya.setAttribute("placeholder", "Doors (Include all doors)");
				ye.setAttribute("placeholder", "Building #");
				ye.setAttribute("pattern", "[0-9]{1,3}");
				ye.setAttribute("maxlength", "3");
				ye.setAttribute("title", "Numbers Only! 3 characters max!");
			}
			
			
	var g = document.createElement("IMG");
	g.setAttribute("src", "http://10.2.0.237:888/dty/public/img/delete.png");
increment();
	ye.setAttribute("id", "building_" + io);
	ya.setAttribute("id", "doors_" + io);
	re.appendChild(ye);
	ra.appendChild(ya);
	g.setAttribute("onclick", "removeElement('complexForm','id_" + io + "')");
	ra.appendChild(g);
	re.setAttribute("id", "id_" + io);
	ra.setAttribute("id", "id_" + io);
	document.getElementById("complexForm").appendChild(re);
	document.getElementById("complexForm").appendChild(ra);
	document.getElementById("complexForm").appendChild(ba);
};
//add Event Listener to options list
$( "#complexOptions" ).change(function() {
	if (document.getElementById("complexOptions").value == "AddLots") {
			$(".door").attr("placeholder", "Lot Spots (increments of 5)");
			$(".building").attr("placeholder", "Lot Name");
			$(".building").attr("pattern", "[A-Za-z0-9 ]{1,35}");
			$(".building").attr("title", "Alphanumeric Only! 35 characters max!");
			$(".building").prop("maxlength", "35");
			$("#add").attr("value", "Add Lot");
		}
		else {
			$(".door").attr("placeholder", "Doors (Include all doors)");
			$(".building").attr("placeholder", "Building #");
			$(".building").attr("pattern", "[0-9]{1,3}");
			$(".building").attr("title", "Numbers Only! 3 characters max!");
			$(".building").prop("maxlength", "3");
			$("#add").attr("value", "Add Building");
		}
});
</script>