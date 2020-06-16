<?php
/**
 * Handles all database operations
 */
class Database {

	/**
	 * The database connection link
	 *
	 * @var PDO
	 */
	private $_link;

	/**
	 * Initialises the class object and sets up a new
	 * database connection using the PDO object
	 *
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $db
	 */
	public function __construct(string $host, string $user, string $password, string $db)
	{
		try {
			// Setup connection parameters and options
			$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
			$opt = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false
			];

			// Set a new connection to the $_link private property
			$this->_link = new PDO($dsn, $user, $password, $opt);
		} catch(Exception $e) {
			die(new CustomException($e));
		}
	}
	
	/**
	 * Executes the provided query and returns the result
	 *
	 * @param string $query
	 * @return array
	 */
	public function query(string $query): array
	{
		try {
			// Setup the statement with the provided string
			$statement = $this->_link->prepare($query);

			// Execute the query
			$statement->execute();

			// Return the execution result
			return $statement->fetchAll();
		} catch(Exception $e) {
			die(new CustomException($e));
		}
	}

	/**
	 * Inserts the provided values on the corresponding
	 * associative array keys fields and returns the
	 * last inserted ID of an auto-incremented field in
	 * the table
	 *
	 * @param string $table
	 * @param array $values
	 * @return int
	 */
	public function insert(string $table, array $values): int
	{
		try {
			// Setup the query string
			$query  = "INSERT INTO $table (" . implode(", ", array_keys($values)) . ")";
			$query .= " VALUES (:" . implode(", :", array_keys($values)) . ")";

			// Prepare the statement and execute using the provided values
			$statement = $this->_link->prepare($query)->execute($values);

			// Return the last insert ID for the row
			return $this->_link->lastInsertId();
		} catch(Exception $e) {
			die(new CustomException($e));
		}
	}

	/**
	 * Updates the provided values on the corresponding
	 * associative array keys fields and returns the
	 * amount of rows that where affected in the table
	 *
	 * @param string $table
	 * @param array $values
	 * @param string $where
	 * @return integer
	 */
	public function update(string $table, array $values, string $where = ''): int
	{
		try {
			// Setup the query string
			$query  = "UPDATE $table SET ";
			
			// Iterate through the values and append their keys to the query
			foreach($values as $field => $value) {
				$query .= $field . " = :" . $field . ", ";
			}

			// Remove the last comma and space from the query
			$query = substr($query, 0, -2);

			// Append the where string to the query if provided
			if(!empty($where)) $query .= " WHERE " . $where;

			// Prepare the statement with the query
			$statement = $this->_link->prepare($query);

			// Execute the statement using the provided values
			$statement->execute($values);

			// Return the number of rows that where updated
			return $statement->rowCount();
		} catch(Exception $e) {
			die(new CustomException($e));
		}
	}

	/**
	 * Deletes every row on the provied table
	 * that satisfies the provided where clause
	 * and returns the number of rows affected
	 *
	 * @param string $table
	 * @param string $where
	 * @return integer
	 */
	public function delete(string $table, string $where = ''): int
	{
		try {
			// Setup the query string
			$query  = "DELETE FROM $table ";

			// Append the where string to the query if provided
			if(!empty($where)) $query .= "WHERE " . $where;

			// Prepare the statement with the query
			$statement = $this->_link->prepare($query);

			// Execute the statement
			$statement->execute();

			// Return the number of rows that where updated
			return $statement->rowCount();
		} catch(Exception $e) {
			die(new CustomException($e));
		}
	}

	/**
	 * Returns the provided string in quotes
	 *
	 * @param string $value
	 * @return string
	 */
	public function quote(string $value): string
	{
		return $this->_link->quote(trim($value));
	}
}