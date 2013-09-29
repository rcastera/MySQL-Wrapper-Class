<?php
/**
 * Copyright (c) 2010 Richard Castera
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without li`ation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace rcastera\Database\Mysql;

class Mysql
{
    /**
     * Link id to current connection.
     *
     * @var integer
     */
    private $linkId;

    /**
     * Result from the last query executed.
     *
     * @var integer
     */
    private $queryResult;

    /**
     * First word in a query (ex: SELECT, INSERT, UPDATE, DELETE).
     *
     * @var string
     */
    private $queryType;

    /**
     * Last insert id.
     *
     * @var integer
     */
    private $lastInsertId;

    /**
     * Constructor.
     *
     * @param string $host - host to connect to.
     * @param string $database - database to connect to.
     * @param string $username - username of the db to connect to.
     * @param string $password - password of the db to connect to.
     * @param boolean $persistent - persistent connection or not.
     */
    public function __construct($host = 'localhost', $database = '', $username = '', $password = '', $persistent = false)
    {
        if (empty($database) && empty($username) && empty($password)) {
            throw new \Exception('Missing parameter values to establish connection.');
        }

        if (! $this->connect($host, $database, $username, $password, $persistent)) {
            throw new \Exception('Could not establish a connection to Mysql.');
        }
    }

    /**
     * Destructor - Disconnects from the database.
     */
    public function __destruct()
    {
        if ($this->linkId) {
            mysql_close($this->linkId);
        }
        unset($this);
    }

    /**
     * Establishes a connection to the database specified.
     *
     * @param string $host - host to connect to.
     * @param string $database - database to connect to.
     * @param string $username - username of the db to connect to.
     * @param string $password - password of the db to connect to.
     * @param boolean $persistent - persistent connection or not.
     *
     * @return boolean
     */
    private function connect($host, $database, $username, $password, $persistant)
    {
        if (empty($this->linkId)) {
            if ($persistant) {
                $this->linkId = mysql_pconnect($host, $username, $password, false);
            } else {
                $this->linkId = mysql_connect($host, $username, $password, false);
            }

            if (! is_resource($this->linkId)) {
                return false;
            }

            // If we couldn't select the database, return false.
            if (! $this->selectDb($database)) {
                throw new \Exception('Could not connect to database.');
                return false;
            } else { // Connection was a success.
                return true;
            }
        } else { // Assume we already have a connection.
            return true;
        }
    }

    /**
     * Selects the database.
     *
     * @param string $database - database to connect to.
     *
     * @return boolean
     */
    private function selectDb($database)
    {
        if (! mysql_select_db($database, $this->linkId)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Retrieves the last error.
     *
     * @return string message from the last MySQL function, or empty string if no error occurred.
     */
    public function getError()
    {
        return mysql_error($this->linkId);
    }

    /**
     * Executes a command on the database.
     *
     * @param string $sql - query to run.
     *
     * @return this.
     */
    public function executeQuery($sql = '')
    {
        // Check to see that the parameters are not empty.
        if (! empty($sql)) {
            $this->runQuery($sql);
            return $this;
        } else {
            throw new \Exception('You need to provide a query.');
        }
    }

    /**
     * Executes a sql query.
     *
     * @param string $query - sql statement.
     *
     * @return boolean
     */
    private function runQuery($query = null)
    {
        if (! is_null($query)) {
            // Determine the query type. (SELECT, UPDATE, INSERT, DELETE etc.)
            $this->queryType = $this->getQueryType($query);

            if (! $this->queryResult = mysql_query($query, $this->linkId)) {
                throw new \Exception('Error in sql query.');
            }

            // Check if the query is an insert. If it is, get the id.
            if ($this->queryType == 'INSERT') {
                $this->lastInsertId = mysql_insert_id();
            }
        }
    }

    /**
     * Gets the last insert id.
     *
     * @return integer id generated for an AUTO_INCREMENT column or false.
     */
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    /**
     * Free result memory.
     *
     * @return boolean
     */
    private function freeResult()
    {
        return mysql_free_result($this->queryResult);
    }

    /**
     * To determine the query type used in the query. ex: SELECT, INSERT.
     * In order to run the getAffectedRows(); function below we need to
     * determine the query type.
     *
     * @param string $query - sql statement.
     *
     * @return string
     */
    private function getQueryType($query = '')
    {
        $query = explode(' ', $query);
        return strtoupper($query[0]);
    }

    /**
     * Verifies that the last query executed successfully.
     *
     * @return boolean
     */
    public function querySucceeded()
    {
        if (! $this->queryResult) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Gets the number of rows affected by the last query executed.
     *
     * @return integer
     */
    public function getAffectedRows()
    {
        if ($this->querySucceeded()) {
            // Retrieves the number of rows from a result set. This command is only valid
            // for statements like SELECT or SHOW that return an actual result set.
            if (($this->queryType == 'SELECT') || ($this->queryType == 'SHOW')) {
                return mysql_num_rows($this->queryResult);
            } else {
                // To retrieve the number of rows affected by a INSERT, UPDATE,
                // REPLACE or DELETE query, use mysql_affected_rows().
                return mysql_affected_rows();
            }
        } else {
            return 0;
        }
    }

    /**
     * Verifies that the current INSERT sql call ran successfully.
     *
     * @return integer
     */
    public function wasInserted()
    {
        if ($this->queryType == 'INSERT' && $this->querySucceeded()) {
            return $this->getLastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Verifies that the current UPDATE sql call ran successfully.
     *
     * @return boolean
     */
    public function wasUpdated()
    {
        if ($this->queryType == 'UPDATE' && $this->querySucceeded()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifies that the current DELETE sql call ran successfully.
     *
     * @return boolean
     */
    public function wasDeleted()
    {
        if ($this->queryType == 'DELETE' && $this->querySucceeded()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the result as an array.
     *
     * @return array
     */
    public function asArray()
    {
        // If the last query ran was unsuccessfull, then return false.
        if (! $this->queryResult) {
            return false;
        } else {
            if (! $this->getAffectedRows() == 0) {
                $rows = array();

                while ($row = mysql_fetch_assoc($this->queryResult)) {
                    array_push($rows, $row);
                }

                $this->freeResult();
                return $rows;
            } else {
                return false;
            }
        }
    }

    /**
     * Return the result as an object.
     *
     * @return array
     */
    public function asObject()
    {
        // If the last query ran was unsuccessfull, then return false.
        if (! $this->queryResult) {
            return false;
        } else {
            if (! $this->getAffectedRows() == 0) {
                $rows = array();

                while ($row = mysql_fetch_object($this->queryResult)) {
                    array_push($rows, $row);
                }

                $this->freeResult();
                return $rows;
            } else {
                return false;
            }
        }
    }
}
