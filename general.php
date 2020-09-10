<?php

/**
* @link https://pipiscrew.com
* @copyright Copyright (c) 2020 PipisCrew
*/

class dbase{
	private $db;

	function connect() {
		$mysql_hostname = "localhost";
		$mysql_user = "root";
		$mysql_password = "password";
		$mysql_database = "rss"; 
		 
		$this->db = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_database", $mysql_user, $mysql_password, 
	  array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
	}
	
	function getScalar($sql, $params) {
		if ($stmt = $this->db -> prepare($sql)) {
	 
			$stmt->execute($params);
	 
			return $stmt->fetchColumn();
		} else
			return 0;
	}
	
	function getSet($sql, $params) {
		if ($stmt = $this->db -> prepare($sql)) {

			$stmt->execute($params);
	 
		  return $stmt->fetchAll();
		} else
			return 0;
	}
	
	function executeSQL($sql, $params) {
		if ($stmt = $this->db -> prepare($sql)) {
	 
			$stmt->execute($params);
	 
			return $stmt->rowCount();
		} else
			return false;
	}
	
	function getConnection()
	{
		return $this->db;
	}
	
	function getCSV($set, $fieldName, $delimiter)
	{
		$arr = array();

		foreach ( $set as $row ) 
			$arr[] = $row[$fieldName];
		
		return implode($delimiter, $arr);
	}

	function escape_str($value)
	{   //src - https://stackoverflow.com/a/1162502
		$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
		$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
		return str_replace($search, $replace, $value);
	}
}