<?php

/**
 * DashboardwModel
 * Handles data for dashboard (Yard Management and AJAX requests)
 */
class DashboardModel
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

	/**
	*Sanitizing function
	*/
	public function test_input($data) 
			{
				$data = filter_var($data, FILTER_SANITIZE_STRING);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				$data = strip_tags($data);
				$data = preg_replace('/[^a-zA-Z0-9_ ()-.,$#!%]*/', '' , $data);
				return $data;
			}
			
		public function sqlSelect($sql) {	
			try {
				$q = $this->db->prepare($sql);
				$q->execute();
				return $q->fetchAll(PDO::FETCH_BOTH);
			}
			catch (PDOException $e) {
				print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
			}
		}
		
		public function sqlQuery($sql) {	
			try {
				$q = $this->db->prepare($sql);
				$q->execute();
				return $rw = $q->fetchAll();
			}
			catch (PDOException $e) {
				print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
			}
		}

	public function sqlQueryOne($sql) {	
			try {
				$q = $this->db->prepare($sql);
				$q->execute();
				return $rw = $q->fetch();
			}
			catch (PDOException $e) {
				print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
			}
		}

		public function sqlInsert($sql) {	
			try {
				$q = $this->db->prepare($sql);
				return $rw = $q->execute();
			}
			catch (PDOException $e) {
				print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
			}
		}


/**
* Building class is written in procedural style. Will need some maintenance and conversion to OOP. 
* Will need to remove NON-PREPARED SQL. Need to check validate and sanitize data! 
* Should be moved to another file and integrated with a Framework.
* @desc class buildings is for building the tables for building in the complex
**/		

public $pos;
public $rows;
public $res;
public $dbs;
public $bld;
public $elements;
public $id;
public $name;
public $class;
public $textbox = '';
public $tblCount;
public $dtbs;
public $nm1;
public $nm2;
public $bldNumber;

public function addTable($plant, $tp) {		

			// query the number of buildings from complex _doors
			$this->bld = $this->sqlQueryOne("SELECT count(id) AS counts
						FROM " . $plant . "_buildings");

				// query the number of complexes from complexes table.
			$cmpnm = $this->sqlQueryOne("SELECT count(name) AS compxName
						FROM complexes");
			// query the number of lots
			$this->lot = $this->sqlQueryOne("SELECT count(id) AS lt
						FROM " . $plant . "_lots");
			// query the the data from lots
			$lotData = $this->sqlSelect("SELECT lots, lot_name, id
						FROM " . $plant . "_lots");
			//query for getting amount of doors and what building they belong to 
			$res = $this->sqlSelect("SELECT bld_id, doors
											FROM " . $plant . "_buildings");	
			//query for getting amount of doors and what building they belong to 
			$upd = $this->sqlQueryOne("SELECT `x_pos`, `y_pos`, `user_name`, `min`
											FROM `users` 
											WHERE `user_name` = '" .Session::get('user_name'). "'");											

	//check if buildings or lots
	if ($tp == "lot") 
	{
		$this->tblCount = $this->lot->lt;
		$this->dtbs = $lotData;
		$this->nm1 = 'lots';
		$this->nm2 = 'id';
	}
	else
	{
		$this->tblCount = $this->bld->counts;
		$this->dtbs = $res;
		$this->nm1 = 'doors';
		$this->nm2 = 'bld_id';
	}

	// count the tables (which are the amount of buildings in the complex)
		for ($tables=0; $tables < $this->tblCount ; $tables++) {
		//drop one from tables number to allow it to be used for the array
		$drs = $tables;
		//get doors from res query this uses drs as a key. eg. $res[0]['doors']
		$doors = $this->dtbs[$drs][$this->nm1];
		//gets building number
		$building = $this->dtbs[$drs][$this->nm2];
		//check if the amount of doors is greater or equal to 20, if it is then create another column
		if ($tp == "lot" && $doors < 10) 
		{
			$colum = ($doors - 1);
			$col = ' ';
			$colSpan = ' colspan="'.$doors.'"';
			$doors = ($doors / $doors);
		}
		else if ($tp == "lot" && $doors >=10) 
		{
			
			$colum = 9;
			$col = '<col width="100"/>
					<col width="100"/>
					<col width="100"/>
					<col width="100"/>
					<col width="100"/>
					<col width="100"/>
					<col width="100"/>
					<col width="100"/>
					<col width="100"/>';
			$colSpan = 'colspan="10"';
			$doors = ($doors / 10);
		}
		//check if the amount of doors is greater or equal to 40, if it is then create 3 columns
		else if ($doors >=20)
		{
			$colum = 1;
			$col = '<col width="100"/>';
			$colSpan = 'colspan="2"';
			$doors = ($doors / 2);
		}
		else
		{
			$colum = 0;
			$col = ' ';
			$colSpan = ' ';
		}
	//check if tables = 0, if so, define id and class. Table 0 is for cloning and drops for other plants.
	if($building == 0 && $tp !== "lot") 
	{
		$idClass = 'createTrailers';
		$bldName = "Create trailers";
		$doors = 1 + $cmpnm->compxName;
		$col = '<col width="100"/>
					<col width="100"/>';
		$colSpan = 'colspan="2"';
	}
	else if($tp == "lot")
	{
		$bldName = $lotData[$tables]['lot_name'];
		$idClass = "lot' ". $building . "'";
	}
	else
	{
		$idClass = "building ". $building . "";
		$bldName = "Building  ". $building . "";
	}
	//check if lot, then add input button
	if ($tp == "lot") 
	{
		$this->names = $lotData[$tables]['lot_name'];
		if($this->names == "All Trailers")
		{
			$this->num = "trailerlot";
		}
		else
		{
			$this->num = "page" . ($tables + 2);
		}		
		//check if it is all trailers lot. Create a draggable div
		if($this->names == "All Trailers")
			{
				//check if out-of-scope (only for Leola)
				if ($upd->y_pos < -1450 || $upd->x_pos < -930 || $upd->x_pos > 2125 || $upd->y_pos > 4640) 
				{
					$y_pos = 0;
					$x_pos = 0;
				}
				else
				{
					$y_pos = $upd->y_pos;
					$x_pos = $upd->x_pos;
				}
				//check if minimized
				if($upd->min == 1) {$heightWidth = "height:210px; width:265px;";} else {$heightWidth = "";}
				print ' <div class="bottomDrag"><div id="alltrailers" style="left: ' . $x_pos . 'px; top: ' . $y_pos . 'px; '.$heightWidth.'"><input type="button" id="maxmin" class="button2" style="z-index: 999999;" value="-" />';
			}
		print '<input type="button" class="button2" value="' . $this->names . '" onclick="redips.toggle(this, \'' . $this->num . '\')"/>
				<div id="' . $this->num . '" class="page other">
					<h3 id="white">' . $this->names . '</h3>';
		$this->bldNumber = ($this->bld->counts + $tables);
	}
	else
	{
		$this->bldNumber = $tables;
	}
	
	if($bldName == "Create trailers") 
	{
		print '<div id="createTable" style="position: fixed; margin-left: -210px; z-index: 2;">';
	}
		// create the table
			print '<table id="' . $idClass . '">
				<colgroup><col width="100"/>' . $col . '</colgroup>
					<tr style="background-color: #eee">
						<th id="b' .$this->bldNumber.'" ' . $colSpan . ' class="mark" title="You can not drop here">' . $bldName . '</th>
					</tr>';
			//create the rows 
				$this->building($doors, $colum, $this->bldNumber, $plant);
			
			print '</table>';
			
				if($bldName == "Create trailers") 
				{
					print '</div>';
				}
				if ($tp == "lot") 
				{
						//check if it is all trailers lot. Create a draggable div
						if($this->names == "All Trailers")
						{
							print ' </div></div>';
						}
					print '</div>
					<br />';
				}
			}
	}//END addTable method

	public $disabled;
	public $notes;
	public $cls;
	public $dt;
	public $comments;
	public $options = '';

	public function building($row, $colum, $bld, $plant) {		
	
			// first column of the query is used as key in returned array
			$res = $this->sqlQuery("SELECT concat(c.tbl_row,'_',c.tbl_col) AS pos, c.trl_code, c.bld_id, t.trl_name, t.trl_code, t.trl_status, t.trl_comments, t.user_full_name
						FROM " . $plant . "_complex c, trailers t
						WHERE c.trl_code = t.trl_code AND c.bld_id = $bld");
			// first column of the query is used as key in returned array
			$rsr = $this->sqlQuery("SELECT concat(c.tbl_row,'_',c.tbl_col) AS pos, c.trl_code, c.bld_id, t.trl_name, t.trl_code, t.trl_status, t.trl_comments, t.user_full_name
						FROM inter_complexes c, trailers t
						WHERE c.trl_code = t.trl_code AND c.bld_id = $bld");
			//query trailer status
			$stat = $this->sqlQuery("SELECT `status` FROM `trailer_status`");
			// query the number of complexes from complexes table.
			$cnm = $this->sqlQuery("SELECT `name`
						FROM `complexes` ");
			
	// count the rows
		for ($rows=1; $rows <= $row; $rows++) {
		
			print '<tr>';
			// column loop starts from 0
			for ($col=0; $col <= $colum; $col++) 
			{	//query door status
			$dstat = $this->sqlQuery("SELECT `status`, `only48`, `notes`, `bld_side` FROM " . $plant . "_doors WHERE `bld_id` = '" . $bld . "' AND `door` = '" . ($rows + $row * $col) . "' ;");
			//get disabled doors status for each door
				if (count($dstat)) 
				{
					foreach ($dstat as $ds) 
					{
						if($ds->status == 1) 
						{
							$this->disabled = "mark";
							$this->notes = $ds->notes;
						}
						elseif ($ds->only48 == 1) 
						{
							$this->disabled = "mark shortOnly";
							$this->notes = $ds->notes;
						}
						elseif ($ds->bld_side == 1) 
						{
							$this->disabled = "side1";
							$this->notes = "Side 1 Docks";
						}
						elseif ($ds->bld_side == 2) 
						{
							$this->disabled = "side2";
							$this->notes = "Side 2 Docks";
						}
						else
						{
							$this->disabled = "";
							$this->notes = "";
						}
					}
				}	
				//controller for door names
				if($bld == 0)
				{
					if($rows == 1) 
					{
						$drName = "Create";
						$idS = "clone";
						$this->cls = "mark";
					}
					elseif($cnm[$rows - 2]->name == Session::get('user_location') && $rows <> 1)
					{					
						$rw = ($rows - 2);
						$drName = $cnm[$rw]->name;
						$idS = $cnm[$rw]->name;
						$this->cls = "mark";
					}
					else
					{					
						$rw = ($rows - 2);
						$drName = $cnm[$rw]->name;
						$idS = $cnm[$rw]->name;
						$this->cls = "";
					}
				}
				else
				{
					$drName = ($rows + $row * $col);
					$idS = substr(Session::get('user_location'), 0, 3) . sprintf("%02s", $bld) . sprintf("%02s", $rows) . '_' . sprintf("%02s", $col);
					$this->cls = "";
				}
				// create table cell
				print '<td id="' . $idS . '" class="' . $plant . ' ' . $this->disabled . ' ' . $this->cls . '" title="'.mb_convert_encoding($this->notes, "UTF-8", "HTML-ENTITIES").'"><strong>' . $drName .'</strong>'  ;
				// prepare position key in the same way as the array key looks
				$pos = $rows . '_' . $col;
				if($bld == 0) 
				{
					$this->dt = $rsr;
				}
				else
				{
					$this->dt = $res;
				}
				// if content for the current position exists
				if (count($this->dt)) 
				{
					foreach ($this->dt as $key => $position) 
					{// prepare elements for defined position (it could be more than one element per table cell)
						if ($pos == $position->pos) 
						{//get status of trailers
							if(count($stat))
							{
								$this->options = '';
								foreach ($stat as $status) 
								{//select the status that is for the trailer under trl_status
									$selected = '';
									if($status->status == $position->trl_status) 
									{
										$selected = "selected='selected'";
									}
									$this->options[] = "<option value='" . $status->status . "' " . $selected . ">" . $status->status . "</option>";
								}// END foreach
							}//END IF count stat
							// id of DIV element will start with sub_id and followed with 'b' (because cloned elements on the page have 'c') and with tbl_id
							// this way content from the database will not be in collision with new content dragged from the left table and each id stays unique
							$id = strtoupper($position->trl_code);
							$name = strtoupper($position->trl_name);
								substr($id, 1, 1) == '4' ? $clss = substr($id, 0, 3) : $clss = substr($id, 0, 2);
								//make trailers that are not in current complex not draggable !!!HAS BEEN EDITED NOT TO ALLOW DRAG!!! $bld == 0 && $drName <> Session::get('user_location') && $rows <> 1 ||
								if ($position->trl_status == "Loading")
								{
									$class = "noDrag " . $clss ; // class name is only first 2 letters from ID
								}
								else
								{
									$class = "drag " . $clss ; // class name is only first 2 letters from ID
								}
							$this->comments = $position->trl_comments;
							$this->lastUser = $position->user_full_name;
							// if it is cloning, create a text box for naming.
							if( $id == 'CL') 
							{
								$this->textbox = "<input id='" . $id . "txt' class='inputTrailer' list='company' style='width: 85px;' placeholder='Outside Trailer'/> <datalist id='company'>
								<option value='AD Trans '>
								<option value='AHUMAKER '>
								<option value='AMS '>
								<option value='Bard '>
								<option value='BLACKHORSE '>
								<option value='Central Penn '>
								<option value='Choice '>
								<option value='Cowan '>
								<option value='COX '>
								<option value='C Ville '>
								<option value='DG '>
								<option value='D.L.Landis '>
								<option value='Express '>
								<option value='GGH '>
								<option value='Granite '>
								<option value='HGIU '>
								<option value='LANDSTAR '>
								<option value='Leiphart '>
								<option value='LIVE '>
								<option value='MST '>
								<option value='New Penn '>
								<option value='NILL '>
								<option value='NDCP '>
								<option value='Outside Company '>
								<option value='SCHNIEDER '>
								<option value='SHUMAKER '>
								<option value='SnH '>
								<option value='STIDHAM '>
								<option value='SYSTEMS '>
								<option value='TCLU '>
								<option value='TMT '>
								<option value='TPA '>
								<option value='TQL '>
								<option value='Triple Crown '>
								<option value='WTC '>
								</datalist>";
							}
							else
							{
								$this->textbox = "";
							}
							//print the trailer status drop down
							print "<div id=\"" . $id . "\" class=\"" . $class . "\" ><h5 id=\"" . $id . "nm\">" . $name . "</h5>" . htmlspecialchars_decode($this->textbox) . "" .$this->current_load($id). "<select  id=\"" . $id . "op\"  class=\"selectOption " . htmlspecialchars_decode($position->trl_status) . "\" style=\"width: 90px; font-weight: bold;\"> " . implode("\n", $this->options) . "</select>
							<br><div id=\"" . $id . "nt\" class=\"nDetails ui-widget\">Notes ".$this->lastUser. ": <input type=\"text\" id=\"" . $id . "no\" class=\"notes\" pattern=\"[a-zA-Z0-9 '?@#$%!,.]{0,64}\" title=\"Please use Alphanumeric, commas, periods, Question marks and Exclamation marks!\"  onblur=\"saveNotes(this.value, this.id)\" name=\"notes\"/ value=\"" . htmlspecialchars_decode($this->comments) . "\">  
							";//get radio options for load selection
								 $this->radio_options('toolbar', $id);
							 print "Driver: <input id=\"". $id . "yd\" class=\"selectDriver\" list=\"drivers\" style=\"width: 120px; font-weight: bold;\" autofocus/><datalist id=\"drivers\" class=\"driverSelect\"></datalist></div></div>";
						}//END if POS
					}//END for each
				}//END if count dt
				// close table cell
				print '</td>';
			}//END for colm
				//place trash in table 0
				if ($bld == 0 && $row < 20) 
				{
					print '<td class="trash" title="Trash"><i class="icon-trash icon-white"></i> Trash</td>';
				}
			print "</tr>\n";
		}//END for Rows
	}//END building method

	public function getDrivers() 
	{
		// return drivers names
			$drivers = $this->sqlQuery("SELECT driver_name
						FROM ". Session::get('user_location') ."_drivers");
		
		$options = [];
		foreach($drivers as $driver) {
			$options[] = '<option value="'. $driver->driver_name .'">'. $driver->driver_name .'</option>';
		}
		
		echo implode("/n",$options);
	}
	public $complex;
	public function complexName() {
			$this->complex = Session::get('user_location');
			return $this->complex;
	}
	public function current_load($id) 
	{
		$qry = $this->db->prepare("SELECT `trl_load` FROM `trailers` WHERE `trl_code` = :trl_code");
		$qry->execute(array(":trl_code" => $id));
		$res = $qry->fetch();
		
		return "<p id=\"" .$id. "p\" class=\"red\">" .$res->trl_load. "</p>";
	}
	//radio options for selecting load. eg. McDonalds
	public function radio_options($option_name, $id) {
		$qry = $this->db->prepare("SELECT `trl_load` FROM `trailers` WHERE `trl_code` = :trl_code");
		$qry->execute(array(":trl_code" => $id));
		$res = $qry->fetch();
		echo "<div id=\"".$id."rd\" class=\"radio-".$option_name."\">";
		$products = array('McD', 'DD', 'Chic', 'CB', 'RAW', 'NISSIN', 'Maruchan', 'Bead', 'Fusion', 'CENTERVILL', 'DD16RCL', 'SCRAP', 'ﾠ');
		foreach($products as $key => $product) {
			if($res->trl_load == $product) { $checked = "checked=\"true\"";} else { $checked = ""; }
			 echo "<input type=\"radio\" id=\"" . $id . "noop".sprintf("%02d", $key)."\" name=\"" . $id . "_product\" value=" .$product. " style=\"height: 40px;\" title=\"" .$product. " load\"".$checked." /><label id=\"".$id."lb".sprintf("%02d", $key)."\" for=\"" . $id . "noop".sprintf("%02d", $key)."\">" .$product. "</label>";
			 if($key == 5) {print "<br>";}
		}
		echo "</div>";
	}

	public $p;
	public $trl_length = 53;
	public function saveAjax($p) {
	// explode input parameters:
	// 0 - $trl_code-  trailer code (sh001)
	// 1 - $tbl1   - target table index
	// 2 - $row1   - target row
	// 3 - $col1   - target column
	// 4 - $tbl0   - source table index
	// 5 - $row0   - source row
	// 6 - $col0   - source column
	
			list($trl_name, $trl_status, $trl_comments, $trl_code, $tbl1, $row1, $col1, $tbl0, $row0, $col0, $truck_driver, $product) = explode('_', $p);				
					$src_door = $row0 + ($row0 * $col0);
					$dst_door = $row1 + ($row1 * $col1);
					$destTrailer = "";
					$sourceTrailer = "";							
					//get name of building or lot from tbl id
					if($tbl1 == 0) { $dest = array('buildingNumber' => ""); $destTrailer = " Plant Drop"; }
					else 
					{
					$sth = $this->db->prepare("SELECT `buildingNumber` FROM `".$this->test_input(Session::get('user_location'))."_doors` WHERE `bld_id` = :bld_id LIMIT 1;");
					$sth->execute(array(":bld_id" => $this->test_input($tbl1)));
					$dest = $sth->fetch();
					}
					if($tbl0 == 0) {$source = array('buildingNumber' => ""); $sourceTrailer = " Created"; }
					else 
					{
					$qry = $this->db->prepare("SELECT `buildingNumber` FROM `".$this->test_input(Session::get('user_location'))."_doors` WHERE `bld_id` = :bld_id LIMIT 1;");
					$qry->execute(array(":bld_id" => $this->test_input($tbl0)));
					$source = $qry->fetch();
					}
					//if from clone
				if ($tbl0 == 0 && $row0 == 1) 
				{	
				// check if trailer already exists
						$sth = $this->db->prepare("SELECT *
						FROM trailers
						WHERE trl_code = :trl_name");
						$sth->execute(array(':trl_name' => $trl_name));
						$count =  $sth->rowCount();
						//check the name for special names
					if(strpos(substr(strtoupper($trl_name), 0, 3), '/SH[0-9]/') || strpos(substr(strtoupper($trl_name), 0, 3), '/DT[0-9]/') || strpos(substr(strtoupper($trl_name), 0, 3), '/SP[0-9]/') || substr($trl_name, 1, 1) == '4' || substr($trl_name, 1, 1) == '2' || substr(strtoupper($trl_name), 0, 2) == 'NS') 
					{
						//if there is a more than one count, then trailer exists.
						if ($count == 1) 
						{
							$_SESSION["feedback_duplicate"][] = FEEDBACK_DUPLICATE_TRAILER;
							return false;
						}
						else 
						{
							
							if(substr($trl_name, 1, 1) == '4') 
							{
							$this->trl_length = "48";
							}
								$this->sqlInsert("INSERT INTO " . $this->complexName() . "_complex (bld_id, tbl_row, tbl_col, trl_code) VALUES ($tbl1, $row1, $col1, '" . substr($trl_name, 0, 8) . "'); 
								INSERT INTO trailers (`trl_code`, `trl_name`, `trl_length`, `trl_plant`, `trl_status`) VALUES ('" . substr($trl_name, 0, 8) . "', '" . $trl_name . "', '" . $this->trl_length . "', '" . $this->complexName() . "', '$trl_status');
								INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES ('" . $trl_name . "', '$trl_status', '$trl_code', '$dest->buildingNumber $destTrailer', '$dst_door', '$sourceTrailer', '$src_door', '".$trl_comments. " " .$product. "', '" .Session::get('user_full_name'). "', '$truck_driver');");
						}
						
						
					}
					else 
					{
						if ($count == 1) 
						{
								header('location: ' . URL . 'help/index');
						}
						else 
						{
							if ($trl_name == null || $trl_name == '')
							{
								$trl_name = "Outside Trailer " . $trl_code;
							}
								$this->sqlInsert("INSERT INTO " . $this->complexName() . "_complex (bld_id, tbl_row, tbl_col, trl_code) VALUES ($tbl1, $row1, $col1, '$trl_code'); 
								INSERT INTO trailers (`trl_code`, `trl_name`, `trl_length`, `trl_plant`, `trl_status`) VALUES ('$trl_code', '$trl_name', '" . $this->trl_length . "', '" . $this->complexName() . "', '$trl_status');
								INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES ('" . $trl_name . "', '$trl_status', '$trl_code', '$dest->buildingNumber $destTrailer', '$dst_door', '$sourceTrailer', '$src_door', '".$trl_comments. " " .$product. "', '" .Session::get('user_full_name'). "', '$truck_driver');");
						}
						
					}
				}
				//trailer moved within the create trailers table
				else if ($tbl1 == 0 && $tbl0 == 0) {
					$this->sqlInsert("UPDATE inter_complexes SET `tbl_row`='$row1', `tbl_col` = '$col1' WHERE `trl_code` = '$trl_code'; 
					INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES ('" . $trl_name . "', '$trl_status', '$trl_code', '$dest->buildingNumber $destTrailer', '$dst_door', '$source->buildingNumber $sourceTrailer', '$src_door', '".$trl_comments. " " .$product. "', '" .Session::get('user_full_name'). "', '$truck_driver');");
					exit();
				}
				else if ($tbl0 == 0 && $row0 > 1) {
					$this->sqlInsert("INSERT INTO " . $this->complexName() . "_complex (bld_id, tbl_row, tbl_col, trl_code) VALUES ($tbl1, $row1, $col1, '$trl_code');
					DELETE FROM inter_complexes WHERE trl_code = '$trl_code';");
					exit();
				}
				else if ($tbl1 == 0) {
					$this->sqlInsert("INSERT INTO inter_complexes (bld_id, tbl_row, tbl_col, trl_code, user_full_name) VALUES ($tbl1, $row1, $col1, '$trl_code', '" .SESSION::get('user_full_name'). "');
					DELETE FROM " . $this->complexName() . "_complex WHERE `trl_code` = '$trl_code';
					INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES ('" . $trl_name . "', '$trl_status', '$trl_code', '$destTrailer', '$dst_door', '$source->buildingNumber $sourceTrailer', '$src_door', '".$trl_comments. " " .$product. "', '" .Session::get('user_full_name'). "', '$truck_driver');");
				exit();	
				}
				else if ($tbl1 !== $tbl0) {
					$this->sqlInsert("UPDATE " . $this->complexName() . "_complex SET `bld_id` = $tbl1, tbl_row=$row1, tbl_col=$col1 WHERE `trl_code` = '$trl_code';
						INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES ('" . $trl_name . "', '$trl_status', '$trl_code', '$dest->buildingNumber', '$dst_door', '$source->buildingNumber', '$src_door', '".$trl_comments. " " .$product. "', '" .Session::get('user_full_name'). "', '$truck_driver');");
						exit();
				}
				// else, trailer is moved to the new door within the building
				else {
				$this->sqlInsert("UPDATE " . $this->complexName() . "_complex SET tbl_row=$row1, tbl_col=$col1 WHERE `trl_code` = '$trl_code'; 
				INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES ('" . $trl_name . "', '$trl_status', '$trl_code', '$dest->buildingNumber', '$dst_door', '$source->buildingNumber', '$src_door', '".$trl_comments. " " .$product. "', '" .Session::get('user_full_name'). "', '$truck_driver');");
				}	
	}
	
	public function saveNotesAjax($notes, $trlid) 
	{
				$sth = $this->db->prepare("UPDATE trailers SET `trl_comments` = :notes, `user_full_name` = :user_full_name WHERE `trl_code` = :trlid");
				$sth->execute(array(':notes' => $this->test_input($notes), ':trlid' => $this->test_input($trlid), ':user_full_name' => Session::get('user_full_name')));
				$ins = $this->db->prepare("INSERT INTO " . $this->complexName() . "_trailermoves (`trl_code`, `notes`, `user_full_name`) VALUES (:trl_id, :notes, :user_full_name)");
				$ins->execute(array(':trl_id' => $this->test_input($trlid), ':notes' => $this->test_input($notes),  ':user_full_name' => Session::get('user_full_name')));
	}
	public function saveOpenTable($value) 
	{
			$sth = $this->db->prepare("UPDATE `yardmanagement`.`users` SET `user_table_open` = :user_table_open WHERE `user_name` = :user_name");
			$sth->execute(array(":user_table_open" => $this->test_input($value), ":user_name" => $this->test_input(Session::get('user_name'))));
	}
	public function saveMinTable($value) 
	{
			$sth = $this->db->prepare("UPDATE `yardmanagement`.`users` SET `min` = :min WHERE `user_name` = :user_name");
			$sth->execute(array(":min" => $this->test_input($value), ":user_name" => $this->test_input(Session::get('user_name'))));
	}
	public function getOpenTable() 
	{
			$sth = $this->db->prepare("SELECT `user_table_open` FROM `yardmanagement`.`users` WHERE `user_name` = :user_name");
			$sth->execute(array(":user_name" => $this->test_input(Session::get('user_name'))));
			$this->tableOpen = $sth->fetchAll();
			
			foreach($this->tableOpen as $key => $o) {
					if($o->user_table_open == 1) {
					echo " 	
					// initially hide all Trailers
					hideTables = function () {
						var div, div2;
						// collect page containers in right DIV container
							div = document.getElementById('trailerlot');
							div2 = document.getElementById('alltrailers');
							div.style.display = 'none';
							div2.style.width = '90px';
					};// hide all trailers table
					hideTables(); ";
					} 
				}
	}

	function saveDrStAjax($door, $building, $notes, $stat, $only48) 
	{
				$sth = $this->db->prepare("UPDATE " . $this->complexName() . "_doors SET `status` = :status, `only48` = :only48, `notes` = :notes WHERE `bld_id` = :bld_id AND `door` = :door");
				$sth->execute(array(":status" => $this->test_input($stat), ":only48" => $this->test_input($only48), ":notes" => $this->test_input($notes), ":bld_id" => $this->test_input($building), ":door" => $this->test_input($door)));
	}
	
	//save trailer status eg. Empty, Loading...
	public function saveTrStAjax($s) 
	{
			list($trlid, $trlStat) = explode('_', $s);
				$this->trl_code = substr($trlid, 0, -2);
				$sth = $this->db->prepare("UPDATE trailers SET `trl_status` = :trl_status WHERE `trl_code` = :trl_code");
				$sth->execute(array(":trl_status" => strip_tags($trlStat), ":trl_code" => $this->test_input($this->trl_code)));
	}
	
	//save trailer load eg. McDonalds, Dunkin Donuts...
	public function saveTrLoadAjax($r) 
	{
			list($trlcode, $trlLoad) = explode('_', $r);
				$this->trl_code = substr($trlcode, 0, -6);
				$sth = $this->db->prepare("UPDATE trailers SET `trl_load` = :trl_load WHERE `trl_code` = :trl_code");
				$sth->execute(array(":trl_load" => strip_tags($trlLoad), ":trl_code" => $this->test_input($this->trl_code)));
	}
	
	function update($data) 
	{
	
			foreach($data->coords as $item) {
				//Extract X number for panel
				$coord_X = $item->coordLeft;
				//Extract Y number for panel
				$coord_Y = $item->coordTop;
				//check if out-of-scope (outside permitted area, Leola only, other plants will have to contact the site administrator)
				if ($coord_Y < -1450 || $coord_X < -930 || $coord_X > 2125 || $coord_Y > 4640) {
					$coord_Y = 0;
					$coord_X = 0;
				}
				//Setup  Query
				$upd = $this->db->prepare("UPDATE `yardmanagement`.`users` SET `x_pos` = :x_pos, `y_pos` = :y_pos WHERE `user_name` = :user_name");
				$upd->execute(array(":x_pos" => $this->test_input($coord_X), ":y_pos" => $this->test_input($coord_Y), ":user_name" => $this->test_input(Session::get('user_name'))));
			}
	}
	function getNotification() 
	{
		$sth = $this->db->prepare("SELECT * FROM `yardmanagement`.`inter_complexes` WHERE `time` BETWEEN DATE_SUB(NOW(), INTERVAL 15 SECOND) AND NOW(); ");
		$sth->execute();
		$this->notifications = $sth->fetchAll();
			
			foreach($this->notifications as $key => $notification) 
			{
				$sth = $this->db->prepare("SELECT * FROM `yardmanagement`.`complexes` WHERE `id` = " .$notification->tbl_row. "; ");
				$sth->execute();
				$this->plant = $sth->fetch();
				return $notification->user_full_name . " dropped trailer " .$notification->trl_code." to plant " .$this->plant->name. " at <br>" .$notification->time;
			}
		
		//update notes notification also
		$qry = $this->db->prepare("SELECT `note_public`, `plant` FROM `notes` WHERE note_public = '1' AND plant = :plant");
		$qry->execute(array(":plant" => Session::get('user_location')));
		Session::set('notes_public', $qry->rowCount());
			
	}
	
////////////////// Ajax DELETE Methods below 
	protected $row;
	protected $col;
	public function deleteAjax($d) {
			list($trl_name, $trl_code, $row, $col) = explode('_', $d);
				$trailer_code = $this->test_input($trl_code);
				$trailer_name = $this->test_input($trl_name);
					if (is_numeric($row) && is_numeric($col)) {
						$qry = $this->db->prepare("DELETE FROM trailers WHERE trl_code = :trl_code");
						$qry->execute(array(":trl_code" => $trailer_code));
						$sth = $this->db->prepare("DELETE FROM  " . $this->complexName() . "_complex WHERE trl_code = :trl_code");
						$sth->execute(array(":trl_code" => $trailer_code));
						$sql = $this->db->prepare("DELETE FROM  inter_complexes WHERE trl_code = :trl_code");
						$sql->execute(array(":trl_code" => $trailer_code));
						
						$sve = $this->db->prepare("INSERT INTO " . $this->complexName() . "_trailermoves (`trl_name`, `trl_status`, `trl_code`, `bld_dst`, `bld_door_dst`, `bld_src`, `bld_door_src`, `notes`, `user_full_name`, `truck_driver`) VALUES (:trl_name, 'DELETED', :trl_code, 'DELETED', 'DELETED', 'DELETED', 'DELETED', 'DELETED BY', :user_full_name, 'DELETED');");
						$sve->execute(array(":trl_name" => $trailer_name, ":trl_code" => $trailer_code, ":user_full_name" =>$this->test_input(Session::get('user_full_name'))));
					}
	}
}//END Class 


