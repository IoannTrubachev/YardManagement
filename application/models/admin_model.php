<?php

/**
 * AdminModel
 * Handles data for admin tools
 */
class AdminModel
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
				$data = preg_replace('/[^a-zA-Z0-9_ ()]*/', '' , $data);
				return $data;
			}
	
	/**
	* Database functions
	*NOT ideal since it is not properly preparing statements for PDO.
	*Will remove later
	*/
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
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return object/null The selected user's profile
     */
    public function getUserProfile($user_id)
    {
        $sql = "SELECT user_id, user_name, user_full_name, user_email, user_active, user_account_type, user_has_avatar, user_location
                FROM users WHERE user_id = :user_id";
        $sth = $this->db->prepare($sql);
        $sth->execute(array(':user_id' => $user_id));

        $user = $sth->fetch();
        $count =  $sth->rowCount();

        if ($count == 1) {
            if (USE_GRAVATAR) {
                $user->user_avatar_link = $this->getGravatarLinkFromEmail($user->user_email);
            } else {
                $user->user_avatar_link = $this->getUserAvatarFilePath($user->user_has_avatar, $user->user_id);
            }
        } else {
            $_SESSION["feedback_negative"][] = FEEDBACK_USER_DOES_NOT_EXIST;
        }

        return $user;
    }

    /**
     * Gets a gravatar image link from given email address
     *
     *
     * @param string $email The email address
     * @param int|string $s Size in pixels, defaults to 50px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param array $options Optional, additional key/value attributes to include in the IMG tag
     * @return string
     */
    public function getGravatarLinkFromEmail($email, $s = AVATAR_SIZE, $d = 'mm', $r = 'pg', $options = array())
    {
        $gravatar_image_link = 'http://www.gravatar.com/avatar/';
        $gravatar_image_link .= md5( strtolower( trim( $email ) ) );
        $gravatar_image_link .= "?s=$s&d=$d&r=$r";

        return $gravatar_image_link;
    }

    /**
     * Gets the user's avatar file path
     * @param int $user_has_avatar Marker from database
     * @param int $user_id User's id
     * @return string/null Avatar file path
     */
    public function getUserAvatarFilePath($user_has_avatar, $user_id)
    {
        if ($user_has_avatar) {
            return URL . AVATAR_PATH . $user_id . '.jpg';
        } else {
            return URL . AVATAR_PATH . AVATAR_DEFAULT_IMAGE;
        }
        // default return
        return null;
    }
	
    /**
     * Gets an array that contains all the users in the database. The array's keys are the user ids.
     * Each array element is an object, containing a specific user's data.
     * @return array The profiles of all users
     */
    public function getAllUsersProfiles() 
    {
        $sth = $this->db->prepare("SELECT user_id, user_name, user_full_name, user_email, user_active, user_account_type, user_has_avatar, user_location FROM users");
        $sth->execute();

        $all_users_profiles = array();

        foreach ($sth->fetchAll() as $user) {
            // a new object for every user. This is eventually not really optimal when it comes
            // to performance, but it fits the view style better
            $all_users_profiles[$user->user_id] = new stdClass();
            $all_users_profiles[$user->user_id]->user_id = $user->user_id;
            $all_users_profiles[$user->user_id]->user_name = $user->user_name;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email;
			$all_users_profiles[$user->user_id]->user_full_name = $user->user_full_name;
            $all_users_profiles[$user->user_id]->user_account_type = $user->user_account_type;
			$all_users_profiles[$user->user_id]->user_location = $user->user_location;

            if (USE_GRAVATAR) {
                $all_users_profiles[$user->user_id]->user_avatar_link =
                    $this->getGravatarLinkFromEmail($user->user_email);
            } else {
                $all_users_profiles[$user->user_id]->user_avatar_link =
                    $this->getUserAvatarFilePath($user->user_has_avatar, $user->user_id);
            }

            $all_users_profiles[$user->user_id]->user_active = $user->user_active;
        }
        return $all_users_profiles;
    }

    /**
     * Create a new complex, building, door or lot.
     * @param string $complex, array $building, array $doors, string $option
     * @execute sql insert data into database
     */
   public function createNewComplex($complex, $building, $doors, $option) 
    {
       try {
						$query = $this->db->prepare("SELECT * FROM `complexes` WHERE name = :complex");
						
						$query->execute(array(':complex' => $this->test_input($complex)));

						// fetchAll() is the PDO method that gets all result rows
						 $complexExists = $query->fetchAll();
						
					
						if($complexExists && $this->test_input($complex) <> "") {	
						
							if($option == "AddBuilding") 
							{
								$this->insertDoors($building, $doors, $complex);

							}
							else if ($option == "AddDoors")
							{
								$this->addDoors($building, $doors, $complex);
								
							}
							else if ($option == "DeleteBuilding")
							{
								$this->deleteDoors($building, $doors, $complex);
								
							}
							else if ($option == "AddLots")
							{
								$this->addLots($building, $doors, $complex);
								
							}
							else
							{
								print_r("<div class='caution'>Complex " . $this->test_input($complex) . " already exists! Do you want to edit the complex? </div>");
							}
						}
						elseif ($this->test_input($complex) <> "" && !$complexExists) {
						$this->sqlInsert("CREATE TABLE IF NOT EXISTS " . $this->test_input($complex) . "_complex ( 
																													`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
																													`bld_id` INT(20) NOT NULL, 
																													`tbl_row` INT(20) NOT NULL, 
																													`tbl_col` INT(20) NOT NULL, 
																													`trl_code` text(30), 
																													`time` timestamp ON UPDATE CURRENT_TIMESTAMP NOT NULL) 
																													CHARACTER SET utf8 COLLATE utf8_general_ci;
										CREATE TABLE IF NOT EXISTS " . $this->test_input($complex) . "_doors ( 
																													`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
																													`bld_id` INT(20) , door varchar(20), 
																													`buildingNumber` VARCHAR( 60 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci,
																													`status` tinyint(1) , 
																													`notes` text(300), 
																													`only48` tinyint(10)) CHARACTER SET utf8 COLLATE utf8_general_ci,
																													`bld_side` tinyint(1); 
										CREATE TABLE IF NOT EXISTS " . $this->test_input($complex) . "_trailermoves ( 
																														id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
																														`trl_name` varchar(100) , 
																														`trl_status` varchar(30), 
																														`trl_code` VARCHAR( 60 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci,
																														`bld_dst` VARCHAR(60) CHARACTER SET utf8_general_ci , 
																														`bld_door_dst` VARCHAR(20), 
																														`bld_dst` VARCHAR(60) CHARACTER SET utf8_general_ci ,
																														`bld_door_src` VARCHAR(20) ,
																														`time` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
																														`notes` text(300),
																														`user_full_name` text(60) CHARACTER SET utf8 COLLATE utf8_general_ci,
																														`truck_driver` text(60) CHARACTER SET utf8 COLLATE utf8_general_ci) CHARACTER SET utf8 COLLATE utf8_general_ci; 
										CREATE TABLE IF NOT EXISTS " . $this->test_input($complex) . "_buildings ( 
																													`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
																													`bld_id` INT(20) , 
																													`doors` varchar(20)) CHARACTER SET utf8 COLLATE utf8_general_ci; 
										CREATE TABLE IF NOT EXISTS " . $this->test_input($complex) . "_lots ( 
																													`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
																													`lot_id` INT(20) , 
																													`lot_name` varchar(80),
																													`lots` int(11)) CHARACTER SET utf8 COLLATE utf8_general_ci;
										CREATE TABLE IF NOT EXISTS " . $this->test_input($complex) . "_drivers ( 
																													`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
																													`employee_number` text(20) CHARACTER SET utf8 COLLATE utf8_general_ci , 
																													`driver_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci,
																													`shift` int(11)) CHARACTER SET utf8 COLLATE utf8_general_ci; 																													
										INSERT INTO `complexes` (`name`) VALUES ('" . $this->test_input($complex) . "');
										INSERT INTO `" . $this->test_input($complex) . "_complex` (`bld_id`, `tbl_row`, `tbl_col`, `trl_code`) VALUES ('0', '1', '0', 'cl');
										INSERT INTO `" . $this->test_input($complex) . "_buildings` (`bld_id`, `doors`) VALUES ('0', '1');
										INSERT INTO `" . $this->test_input($complex) . "_drivers` (`employee_number`, `driver_name`, `shift`) VALUES ('0', '', 0);
										INSERT INTO `" . $this->test_input($complex) . "_lots` (`lot_id`, `lot_name`, `lots`) VALUES ('1', 'All Trailers', '50');");
							//load insertDoors function								
							$this->insertDoors($building, $doors, $complex);
				}
					
		}
	catch(PDOException $e) {
    print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
	}
    }


    /**
     * Inserts Building number and doors into complex
     * @param array $building (building number)
     * @param array $doors (amount of doors per building)
     * @param string $complx (complex name)
     */
public function insertDoors($building, $doors, $complx) 
		{
						$query = $this->db->prepare("SELECT bld_id FROM `" . $this->test_input($complx) . "_doors` ORDER BY bld_id DESC LIMIT 1;");	
						$query->execute();
						// fetch() is the PDO method that gets result row
						 $this->lastDoor = $query->fetch();
							 if ($this->lastDoor) { $this->idValue = $this->lastDoor->bld_id;} else {$this->idValue = 0;};
				foreach ( $building as $key=>$buildings ) 
				{	
					//insert into complex_building building number and doors total
					$sml = $this->db->prepare("INSERT INTO " . $this->test_input($complx) . "_buildings (`bld_id`, `doors`) VALUES (:bld_id, :doors)");
					$sml->execute(array(":bld_id" => $this->test_input($buildings), ":doors" => $this->test_input($doors[$key])));
					
					try
					{
						$sql = $this->db->prepare("SELECT buildingNumber FROM `" . $this->test_input($complx) . "_doors` WHERE buildingNumber = :buildingNumber ORDER BY bld_id DESC LIMIT 1;");						
						$sql->execute(array('buildingNumber' => $this->test_input($buildings)));
						// fetch() is the PDO method that gets result row
						 $this->duplicateBuilding = $sql->rowCount();
						 
					    if ($this->duplicateBuilding >= 1) 
						{
							print_r("<div class='error'>Duplicate Building.</div>");
							$e = "Error duplicate";
						 }
						 else
						 {
							for ($door = 1; $door <= $doors[$key]; $door++) 
							{		

								$sth = $this->db->prepare("INSERT INTO " . $this->test_input($complx) . "_doors (`bld_id`, `door`, `buildingNumber`) VALUES (:bld_id, :door, :buildingNumber)");
								$sth->execute(array(":bld_id" => ($key + 1 + $this->idValue), ":door" => $this->test_input($door), ":buildingNumber" => $this->test_input($buildings)));
							}
						}
					}
					catch(PDOException $e) 
					{
						print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
					}
	
				}
				if(!isset($e))
				{
					print_r("<div class='success'>Successfully inserted Complex, Buildings, and Doors into the database.</div>");
				}
		}
		
// delete function can be revised a bit
public $limit = "";
public $andDoor = "";
		public function deleteDoors($bld, $drs, $complx) 
		{
				foreach ( $bld as $key=>$blds ) 
				{	
					try
					{
							if (!$drs[$key] || $drs[$key] == 0)
							{
								$sbl = $this->db->prepare("DELETE FROM `" .$this->test_input($complx). "_doors` WHERE `buildingNumber` = :bld_id;
																	   DELETE FROM `" .$this->test_input($complx). "_buildings` WHERE `bld_id` = :bld_id;");
								$sbl->execute(array(":bld_id" => $this->test_input($blds)));
								print_r("<div class='success'>Successfully deleted door " . $this->test_input($drs[$key]) . " from building " . $this->test_input($blds) . ".</div>");
							}
							else
							{
								$this->limit = "LIMIT 1";
								$this->andDoor = "door = '" . $drs[$key] . "' AND";
								$sbl = $this->db->prepare("DELETE FROM " .$this->test_input($complx). "_doors WHERE " . $this->andDoor . " buildingNumber = :bld_id " . $this->limit . ";
																	   UPDATE `" .$this->test_input($complx). "_buildings` SET doors = doors - 1 WHERE bld_id = :bld_id;");
								$sbl->execute(array(":bld_id" => $this->test_input($blds)));
								print_r("<div class='success'>Successfully deleted door " . $this->test_input($drs[$key]) . " from building " . $this->test_input($blds) . ".</div>");
							}
								
					}
					catch(PDOException $e) 
					{
					print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
					}
	
				}
		}

		public function addDoors($bld, $drs, $complx) 
		{
				foreach ($bld as $key=>$blds ) 
				{	
						try
						{
						$rws = $this->db->prepare("SELECT * FROM `" . $this->test_input($complx) . "_doors` WHERE door = :drs AND bld_id = :blds");
						$rws->execute(array(':blds' => $this->test_input($blds), ':drs' => $this->input($drs[0])));

						$rw = $this->db->prepare("SELECT `bld_id` FROM `" . $this->test_input($complx) . "_doors` WHERE buildingNumber = :blds LIMIT 1", $blds);
						$rw->prepare(array(":blds" => $this->test_input($blds)));
						
							if($rws->rowCount() <> 1)
							{
								$sth = $this->db->prepare("INSERT INTO `" . $this->test_input($complx) . "_doors` (bld_id, door, buildingNumber) VALUES (:id, :door, :bld_id);
														UPDATE `" . $this->test_input($complx) . "_buildings` SET doors = doors + 1 WHERE bld_id = :bld_id;");
								$sth->execute(array(":bld_id" => $this->test_input($blds), ":id" => $this->test_input($rw[0]), ":door" => $this->test_input($drs[$key]))); 					
								
								print_r("<div class='success'>Successfully  added " . $this->test_input($rw[0]) . " door " . $this->test_input($drs[$key]) . " from building " . $this->test_input($blds) . ".</div>");
							}
							else
							{
							print_r("<div class='caution'>Door " . $this->test_input($drs[$key]) . " already exists in building " . $this->test_input($blds) . ".</div>");
							}
						}
						catch(PDOException $e) 
						{
						print_r("<div class='error'>Error:  added " . $e->getMessage() . "</div>");
						}
	
				}
		}
	/**
     * Creates lots for complex
     * @param array $building (Lot number)
     * @param array $doors (amount of lot space)
     * @param string $complx (complex name)
	 * @param string $lotName (Lot name)
     */
		public function addLots($building, $doors, $complx) 
		{
						$query = $this->db->prepare("SELECT lot_id FROM `" . $this->test_input($complx) . "_lots` ORDER BY lot_id DESC LIMIT 1;");	
						$query->execute();
						// fetch() is the PDO method that gets result row
						 $this->lastLot = $query->fetch();
						$sth = $this->db->prepare("SELECT bld_id FROM `" . $this->test_input($complx) . "_doors` ORDER BY bld_id DESC LIMIT 1;");	
						$sth->execute();
						// fetch() is the PDO method that gets result row
						 $this->lastId = $sth->fetch();
							 if ($this->lastLot) { $this->idValue = $this->lastLot->lot_id;} else {$this->idValue = 0;};
				foreach ( $building as $key=>$buildings ) 
				{	
					//insert into complex_building building number and doors total
					$sal = $this->db->prepare("INSERT INTO " . $this->test_input($complx) . "_lots (`lot_id`, `lot_name`, `lots`) VALUES (:lot_id, :lot_name, :lots);");
					$sal->execute(array(":lot_id" => ($key + 1 + $this->idValue), ":lot_name" => $this->test_input($buildings), ":lots" => $this->test_input($doors[$key])));
					for ($door = 1; $door <= $doors[$key]; $door++) 
							{		

								$sth = $this->db->prepare("INSERT INTO " . $this->test_input($complx) . "_doors (`bld_id`, `door`, `buildingNumber`) VALUES (:bld_id, :door, :buildingNumber)");
								$sth->execute(array(":bld_id" => ($key + 2 + $this->lastId->bld_id), ":door" => $this->test_input($door), ":buildingNumber" => $this->test_input($buildings)));
							}
	
				}
				if(!isset($e))
				{
					print_r("<div class='success'>Successfully inserted lot(s) into the database.</div>");
				}
		}
}
