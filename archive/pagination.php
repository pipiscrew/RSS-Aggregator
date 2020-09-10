<?php

/**
* @link https://pipiscrew.com
* @copyright Copyright (c) 2020 PipisCrew
*/

date_default_timezone_set('UTC');

require_once ('../general.php');
	
$table_columns = array(
'feed_title',
'feed_url',
'feed_date'
);


if (!is_numeric($_GET['limit']) || !is_numeric($_GET['offset']))
{
	echo 'error';
	exit;
}

$limit = $_GET['limit'];
$offset= $_GET['offset'];


$db = new dbase();

$db->connect();

//when user search for something
$search = null;

if (isset($_GET['search']))
	$search = $db->escape_str($_GET['search']);


$sql='select feed_title, feed_url, feed_date from feeds ';
$count_query_sql = 'select count(*) from feeds ';


//////////////////////////////////////WHEN SEARCH TEXT SPECIFIED
if (isset($search) && !empty($search))
{
	$search_arr = explode(',', $search);
	
	if (sizeof($search_arr)==1){
		$sql.= ' where feed_title like :searchTerm or feed_url like :searchTerm';
		$count_query_sql.= ' where feed_title like :searchTerm or feed_url like :searchTerm';
	} else {
		$sql.= ' where (feed_title like :searchTerm and feed_title like :searchTerm2 and feed_title like :searchTerm3) or (feed_url like :searchTerm and feed_url like :searchTerm2 and feed_url like :searchTerm3)';
		$count_query_sql.= ' where (feed_title like :searchTerm and feed_title like :searchTerm2 and feed_title like :searchTerm3) or (feed_url like :searchTerm and feed_url like :searchTerm2 and feed_url like :searchTerm3)';
	}

}

//////////////////////////////////////WHEN SORT COLUMN NAME SPECIFIED
if (isset($_GET['name']) && isset($_GET['order'])) {
	$ordercol= $db->escape_str($_GET['name']);
	$orderby= $db->escape_str($_GET['order']);

	if ($orderby=='asc' || $orderby=='desc') {

		//validation, if col provided exists
		$key = array_search($ordercol, $table_columns);

		$order=$table_columns[$key];

		$sql.= " order by {$order} {$orderby}";
	}
}


//////////////////////////////////////PREPARE
$stmt = $db->getConnection()->prepare($sql.' limit :offset,:limit');


//////////////////////////////////////WHEN SEARCH TEXT SPECIFIED *BIND*
if (isset($search) && !empty($search)) {
	if (sizeof($search_arr)==1){
		$stmt->bindValue(':searchTerm', '%'.$search_arr[0].'%');
	} else {
		$stmt->bindValue(':searchTerm', '%'.$search_arr[0].'%');
		$stmt->bindValue(':searchTerm2', '%'.$search_arr[1].'%');
		$stmt->bindValue(':searchTerm3', '%'.$search_arr[2].'%');
	}
}


//////////////////////////////////////PAGINATION SETTINGS
$stmt->bindValue(':offset' , intval($offset), PDO::PARAM_INT);
$stmt->bindValue(':limit' , intval($limit), PDO::PARAM_INT);

	
//////////////////////////////////////FETCH ROWS
$stmt->execute();
$rows = $stmt->fetchAll();


//////////////////////////////////////COUNT TOTAL 
if (isset($search) && !empty($search)) {
	if (sizeof($search_arr)==1){
		$count_recs = $db->getScalar($count_query_sql, array(':searchTerm' => '%'.$search_arr[0].'%'));
	} else {
		$count_recs = $db->getScalar($count_query_sql, array(':searchTerm' => '%'.$search_arr[0].'%', ':searchTerm2' => '%'.$search_arr[1].'%', ':searchTerm3' => '%'.$search_arr[2].'%'));
	}
}
else
{	
	$count_recs = $db->getScalar($count_query_sql, null);
}

//////////////////////////////////////JSON ENCODE
$arr = array('total'=> $count_recs,'rows' => $rows);

header('Content-Type: application/json', true);

echo json_encode($arr);

?>