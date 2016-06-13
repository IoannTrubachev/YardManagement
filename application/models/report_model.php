<?php

/**
 * ReportModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class ReportModel
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
     * Search for trailer with specific date
     * @return array an array with several objects (the results)
     */
    public function getResults($trl_code, $date, $date_last)
    {
        $sql = "SELECT * FROM " .Session::get('user_location'). "_trailermoves WHERE (trl_code = :trl_code OR trl_name = :trl_code) AND time >= :date AND time <= :date_last" ;
        $query = $this->db->prepare($sql);
        $query->execute(array(":trl_code" => $trl_code, ":date" => $date, ":date_last" => $date_last));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

	 /**
     * Search by date only
     * @return array an array with several objects (the results)
     */
    public function getDateResults($date, $date_last)
    {
        $sql = "SELECT * FROM " .Session::get('user_location'). "_trailermoves WHERE time >= :date AND time <= :date_last" ;
        $query = $this->db->prepare($sql);
        $query->execute(array(":date" => $date, ":date_last" => $date_last));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }
	 /**
     * Search by date only
     * @return array an array with several objects (the results)
     */
    public function getNameResults($trl_code)
    {
        $sql = "SELECT * FROM " .Session::get('user_location'). "_trailermoves WHERE trl_code = :trl_code" ;
        $query = $this->db->prepare($sql);
        $query->execute(array(":trl_code" => $trl_code));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }
    /**
     * Get all results
     * @param int $note_id id of the specific note
     * @return object a single object (the result)
     */
   public function getAllResults()
    {
        $sql = $sql = "SELECT * FROM " .Session::get('user_location'). "_trailermoves LIMIT 100;";
        $query = $this->db->prepare($sql);
        $query->execute();

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
		
	
    }


}
