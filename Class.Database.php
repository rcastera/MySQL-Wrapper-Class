<?php
/**
 * MySQL Wrapper Class in PHP5
 * @author Richard Castera
 * @link http://www.richardcastera.com/projects/mysql-wrapper-class-in-php5
 * @see http://php.net/manual/en/book.mysql.php
 * @license GNU LESSER GENERAL Public LICENSE
 */
 
class Database {
  /**
   * The link id to current connection.
   * @var Variant
   */ 
  private $linkId = NULL;
  
  /**
   * The result from the last query executed.
   * @var Variant
   */
  private $queryResult;
  
  /**
   * The first word in a query (ex: SELECT, INSERT, UPDATE, DELETE).
   * @var String
   */
  private $queryType;
  
  /**
   * The last insert id.
   * @var Integer
   */
  private $lastInsertId;
  
  /**
   * Constructor.
   * @param String $host - The host to connect to.
   * @param String $database - The database to connect to.
   * @param String $username - The username of the db to connect to.
   * @param String $password - The password of the db to connect to.
   * @param Boolean $persistent - Is this a persistent connection or not.
   */ 
  public function __construct($host = 'localhost', $database = '', $username = '', $password = '', $persistent = FALSE) {
    if (empty($database) && empty($username) && empty($password)) {
      trigger_error('Invalid parameter values to establish connection.', E_USER_ERROR);
    }
    else {
      if (!$this->connect($host, $database, $username, $password, $persistent)) {
        trigger_error('Could not establish a connection.', E_USER_ERROR);
      }
    }
  }

  /**
   * Destructor - Disconnects from the database.
   */ 
  public function __destruct() {
    if ($this->linkId) {
      mysql_close($this->linkId);
    }
    unset($this);
  }

  /**
   * Establishes a connection to the database specified.
   * @param String $host - The host to connect to.
   * @param String $database - The database to connect to.
   * @param String $username - The username of the db to connect to.
   * @param String $password - The password of the db to connect to.
   * @param Boolean $persistent - Is this a persistent connection or not.
   * @return Boolean True if connected, False if not.
   */  
  private function connect($host, $database, $username, $password, $persistant) {
    if (is_null($this->linkId)) {
      if ($persistant) {
        $this->linkId = mysql_pconnect($host, $username, $password, FALSE);
      }
      else { 
        $this->linkId = mysql_connect($host, $username, $password, FALSE);
      }

      // If there was an error establishing a connection, return false.
      if (!is_resource($this->linkId))  {
        return FALSE;
      } 

      // If we couldn't select the database, return false.
      if (!$this->selectDb($database)) {
        trigger_error('Could not connect to database.', E_USER_ERROR);
        return FALSE;
      } 
      // Connection was a success.
      else {
        return TRUE;
      }
    }
    else {
      // Assume we already have a connection.
      return TRUE;
    }
  }

  /**
   * Selects the database.
   * @param String $database - The database to connect to.
   * @return Boolean True for success, False if not.
   */ 
  private function selectDb($database) {
    // If there was an error selecting the database, return false.  
    if (!mysql_select_db($database, $this->linkId)) {  
      return FALSE;
    }
    else {
      return TRUE;
    }
  }
  
  /**
   * Retrieves the last error.
   * @return String The error text from the last MySQL function, or empty string if no error occurred. 
   */ 
  public function getError() {
    return mysql_error($this->linkId);
  }

  /**
   * Executes a command on the database.
   * @param String $sql - the query to run.
   * @return Mixed If True returns an array of rows. False if no rows.
   */ 
  public function executeQuery($sql = '') {
    // Check to see that the parameters are not empty.
    if (!empty($sql)) {
  
      // Execute the query.
      $this->runQuery($sql);

      return $this;
    }
    // Parameters are empty.
    else {
      trigger_error('You need to provide a query.', E_USER_ERROR);
    }
  }

  /**
   * Executes a sql query.
   * @param String $query - The sql statement.
   * @return Boolean True for success, False if not.
   */ 
  private function runQuery($query = NULL) {
    // Check to see if the sql statement variable is set. 
    if (!is_null($query)) {
      // Determine the query type. (SELECT, UPDATE, INSERT, DELETE etc.)
      $this->queryType = $this->getQueryType($query);
      
      if (!$this->queryResult = mysql_query($query, $this->linkId)) {
        trigger_error('Error in sql query.', E_USER_ERROR);
      }
  
      // Check if the query is an insert. If it is, get the id.
      if ($this->queryType == 'INSERT') {
        $this->lastInsertId = mysql_insert_id();
      }
    }  
  }

  /**
   * Gets the last insert id.
   * @return Variant The ID generated for an AUTO_INCREMENT column or False.
   */ 
  public function getLastInsertId() {
    return $this->lastInsertId;   
  }

  /**
   * Free result memory.
   * @return Returns True on success or False on failure.
   */ 
  private function freeResult() {
    return mysql_free_result($this->queryResult);   
  }

  /**
   * To determine the query type used in the query. ex: SELECT, INSERT. In order to run the getAffectedRows(); function below we need to determine the query type.
   * @param String $query - The sql statement.
   * @return String The first word in the query.
   */ 
  private function getQueryType($query = '') {
    $query = explode(' ', $query);
    return strtoupper($query[0]);    
  }

  /**
   * Verifies that the last query executed successfully.
   * @return True if the last query executed, False if not.
   */ 
  public function querySucceeded() {
    if (!$this->queryResult) {
      return FALSE;
    }
    else {
      return TRUE;
    } 
  }

  /**
   * Gets the number of rows affected by the last query executed.
   * @return Integer - The number of rows affected.
   */ 
  public function getAffectedRows() {
    if ($this->querySucceeded()) {
      // Retrieves the number of rows from a result set. This command is only valid for statements like SELECT or SHOW that return an actual result set. 
      if (($this->queryType == 'SELECT') || ($this->queryType == 'SHOW')) {
        return mysql_num_rows($this->queryResult);
      }
      else {
        // To retrieve the number of rows affected by a INSERT, UPDATE, REPLACE or DELETE query, use mysql_affected_rows().
        return mysql_affected_rows();
      }
    }
    else {
      return 0; 
    }
  }

  /**
   * Verifies that the current INSERT sql call ran successfully.
   * @return Mixed The last insert id if successful, false if not.
   */
  public function wasInserted() {
    if ($this->queryType == 'INSERT' && $this->querySucceeded()) {
      return $this->getLastInsertId();
    }
    else {
      return FALSE;
    }
  }

  /**
   * Verifies that the current UPDATE sql call ran successfully.
   * @return Boolean True if successful, false if not.
   */
  public function wasUpdated() {
    if ($this->queryType == 'UPDATE' && $this->querySucceeded()) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
  
  /**
   * Verifies that the current DELETE sql call ran successfully.
   * @return Boolean True if successful, false if not.
   */
  public function wasDeleted() {
    if ($this->queryType == 'DELETE' && $this->querySucceeded()) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Return the result as an array.
   * @return Mixed An array of rows if succcessful, false if not.
   */
  public function asArray() {
    // If the last query ran was unsuccessfull, then return false.
    if (!$this->queryResult) { 
      return FALSE;
    }
    else {
      if (!$this->getAffectedRows() == 0) {
        $rows = array();
  
        while ($row = mysql_fetch_assoc($this->queryResult)) {
          array_push($rows, $row); 
        }
  
        $this->freeResult();
        return $rows;
      }
      else {
        return FALSE; 
      }
    }
  }

  /**
   * Return the result as an object.
   * @return Mixed An array of object rows if succcessful, false if not.
   */
  public function asObject() {
    // If the last query ran was unsuccessfull, then return false.
    if (!$this->queryResult) { 
      return FALSE;
    }
    else {
      if (!$this->getAffectedRows() == 0) {
        $rows = array();
  
        while ($row = mysql_fetch_object($this->queryResult)) {
          array_push($rows, $row); 
        }
  
        $this->freeResult();
        return $rows;
      }
      else {
        return FALSE; 
      }
    } 
  }
}
