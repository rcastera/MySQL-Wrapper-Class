<?php
/**
 * @uses        MySQL Wrapper Class in PHP5
 * @author      Richard Castera
 * @link        http://www.richardcastera.com/projects/mysql-wrapper-class-in-php5
 * @date        8/21/2008
 * @version     0.06
 * @copyright   Richard Castera 2010 Copyright 
 * @access      Public
 * @see         http://php.net/manual/en/book.mysql.php
 * @license     GNU LESSER GENERAL Public LICENSE
 */
 
class Database {


	/**
   * @uses    The link id to current connection.
   * @access  Private
   * @var     Variant
   */ 
	private $linkId = NULL;
 	
  
  /**
   * @uses    The result from the last query executed.
   * @access  Private
   * @var     Variant
   */
  private $queryResult;
 	
  
  /**
   * @uses    The first word in a query (ex: SELECT, INSERT, UPDATE, DELETE).
   * @access  Private
   * @var     String
   */
  private $queryType;
 	
  
  /**
   * @uses    The last insert id.
   * @access  Private
   * @var     Integer
   */
  private $lastInsertId;
 	
 	 	
    
    
    
	/**
   * @uses    Constructor.
   * @access	Public
   * @param   String $host - The host to connect to.
   * @param   String $database - The database to connect to.
   * @param   String $username - The username of the db to connect to.
   * @param   String $password - The password of the db to connect to.
   * @param   Boolean $persistent - Is this a persistent connection or not.
   * @return  None.
   */ 
	public function __construct($host = 'localhost', $database = '', $username = '', $password = '', $persistent = FALSE) {
		if(empty($database) && empty($username) && empty($password)) {
		  throw new Exception('Invalid parameter values to establish connection.');
		}
    else {
      $this->connect($host, $database = '', $username, $password, $persistent);
    }
	}
	

  /**
   * @uses		Destructor - Disconnects from the database.
   * @access	Public
   * @param	  None.
   * @return  None.
   */ 
  public function __destruct() {
    if($this->linkId) {
      mysql_close($this->linkId);
    }
    unset($this);
  }


  /**
   * @uses		Connects to the database specified.
   * @access	Public
   * @param   String $host - The host to connect to.
   * @param   String $database - The database to connect to.
   * @param   String $username - The username of the db to connect to.
   * @param   String $password - The password of the db to connect to.
   * @param   Boolean $persistent - Is this a persistent connection or not.
   * @return  True if connected, False if not.
   */  
  private function connect($host, $database, $username, $password, $persistant) {
    if(is_null($this->linkId)) {
      if($persistant) {
        $this->linkId = mysql_pconnect($host, $username, $password, FALSE);
      }
      else { 
        $this->linkId = mysql_connect($host, $username, $password, FALSE);
      }

      // If there was an error establishing a connection, return false.
      if(!is_resource($this->linkId))  {
        return FALSE;
      } 

      // If we couldn't select the database, return false.
      if(!$this->selectDb()) {
        return FALSE;
      } 
      // Connection was a success.
      else {
        return TRUE;
      }
    }
    else {
      return;
    }
  }


  /**
   * @uses		Selects the database.
   * @access	Private
   * @param	  None.
   * @return  True for success, False if not.
   */ 
  private function selectDb() {
    // If there was an error selecting the database, return false.	
    if(!mysql_selectDb(DB_NAME, $this->linkId)) {	
      return FALSE;
    }
    else {
      return TRUE;
    }
  }
  
  
  /**
   * @uses		Retrieves the last error.
   * @access	Public
   * @param	  None.
   * @return  String - Returns the error text from the last MySQL function, or empty string if no error occurred. 
   */ 
  public function getError() {
    return mysql_error($this->linkId);
  }


  /**
   * @uses		Executes a command on the database.
   * @access	Public
   * @param	  String $strQuery - the query to run.
   * @return  If True returns an array of rows. False if no rows.
   */ 
  public function executeQuery($strQuery = '') {
    // Check to see that the parameters are not empty.
    if(!empty($strQuery)) {
  
      // Execute the query.
      $this->runQuery($strQuery);
    
      // Check if the query succeeded.
      if($this->querySucceeded()) {
    
        //If the query returned 0 rows, the query returned nothing.
        if($this->getAffectedRows() == 0) {
          return FALSE;
        }
        else { // If the query returned a value greater than 0. Return the rows.
    
          // Which query was run.
          switch($this->getQueryType()) {
            case 'INSERT':
              $intId = $this->getLastInsertId();
              return $intId;
              break;
      
            case 'UPDATE':
              return TRUE;
              break;
      
            case 'REPLACE':
              return TRUE;
              break;
      
            case 'DELETE':
              return TRUE;
              break;
      
            default:
              // Get the rows.
              return $this->getRows();
              break;
          }
        }
      }
      // The query failed.
      else {
        return FALSE;
      }
    }
    // Parameters are empty.
    else {
      return FALSE;
    }
  }


  /**
   * @uses		Executes a sql query.
   * @access	Public
   * @param	  String $strSqlStatement - The sql statement.
   * @return  True for success, False if not.
   */ 
  private function runQuery($strSqlStatement = NULL) {
    // Check to see if the sql statement variable is set. 
    if(!is_null($strSqlStatement)) {
      // Determine the query type. (SELECT, UPDATE, INSERT, DELETE etc.)
      $this->queryType = $this->queryType($strSqlStatement);
  
      // Run the query.
      $this->queryResult = mysql_query($strSqlStatement, $this->linkId);
  
      // Check if the query is an insert. If it is, get the id.
      if($this->queryType == 'INSERT') {
        $this->lastInsertId = mysql_insert_id();
      }
  
      // Return the query result.
      return $this->queryResult;
    } 
    else {				
      return FALSE;
    }	  
  }


  /**
   * @uses		Gets the last insert id.
   * @access	Public
   * @param	  None.
   * @return  Variant - The ID generated for an AUTO_INCREMENT column or False.
   */ 
  public function getLastInsertId() {
    return $this->lastInsertId;	  
  }


  /**
   * @uses		Free result memory.
   * @access	Public
   * @param	  None.
   * @return  Returns True on success or False on failure. .
   */ 
  private function freeResult() {
    return mysql_free_result($this->queryResult);	  
  }


  /**
   * @uses		To determine the query type used in the query. ex: SELECT, INSERT. In order to run the getAffectedRows(); function below we need to determine the query type.
   * @access	Private
   * @param	  String $strSqlStatement - The sql statement.
   * @return  String - The first word in the query.
   */ 
  private function queryType($strSqlStatement = '') {
    $arrQuery = explode(' ', $strSqlStatement);
    return strtoupper($arrQuery[0]);	  
  }


  /**
   * @uses		Gets the query type.
   * @access	Public
   * @param	  None.
   * @return  The query type.
   */ 
  public function getQueryType() {
    return $this->queryType;	  
  }


  /**
   * @uses		Verifies that the last query executed successfully.
   * @access	Public
   * @param	  None.
   * @return  True if the last query executed, False if not.
   */ 
  public function querySucceeded() {
    if(!$this->queryResult) {
      return FALSE;
    }
    else {
      return TRUE;
    } 
  }


  /**
   * @uses		Gets the number of rows affected by the last query executed.
   * @access	Public
   * @param	  None.
   * @return  Integer - The number of rows affected
   */ 
  public function getAffectedRows() {
    if($this->querySucceeded()) {
      // Retrieves the number of rows from a result set. This command is only valid for statements like SELECT or SHOW that return an actual result set. 
      if(($this->queryType == 'SELECT') || ($this->queryType == 'SHOW')) {
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
   * @uses		Gets all rows based by the last query executed.
   * @access	Public
   * @param	  None.
   * @return  Array - An array of rows based on the last query executed.
   */ 
  public function getRows() {
    // If the last query ran was unsuccessfull, then return false.
    if(!$this->queryResult) {	
      return FALSE;
    }
    else {
      if(!$this->getAffectedRows() == 0) {
        $arrRows = array();
  
        while($row = mysql_fetch_assoc($this->queryResult)) {
          array_push($arrRows, $row); 
        }
  
        $this->freeResult();
        return $arrRows;
      }
      else {
        return FALSE;	
      }
    } 
  }
}
?>