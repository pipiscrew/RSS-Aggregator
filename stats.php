<title>Most Wanted News - Statistics!</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description"  content="PipisCrew your feeds roll on your wall" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="robots" content="noindex">
<link href="bootstrap.css" rel="stylesheet">

<style type="text/css">

	body {
		font-family: verdana,arial,sans-serif;
		font-size: 11px;
		background-color: #222;
		color: #999;
	}
	a, a:visited, a:hover, a:active {
		color: inherit;
			
	}
	table.gridtable {
		font-family: verdana,arial,sans-serif;
		font-size: 11px;
		color: inherit;
		border-width: 1px;
		border-color: #c0c0c0;
		border-collapse: collapse;		
	}
	table.gridtable th {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #c0c0c0;
	}
	table.gridtable td {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #c0c0c0;
	}
</style>


<div class="container-fluid">


<?php

/**
* @link https://pipiscrew.com
* @copyright Copyright (c) 2020 PipisCrew
*/

date_default_timezone_set('UTC');

require_once('general.php');

$db = new dbase();
$db->connect();

$count = 0;

$base = 'https://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . "/?d=6&p=";


$date_range = $db->getSet('select min(feed_date) as min, max(feed_date) as max from feeds', null);


$result = $db->getSet('SELECT feeds.feed_provider_id, providers.provider_headline, providers.provider_visible, count(*) as Items FROM `feeds` left join providers on providers.provider_id = feeds.feed_provider_id group by provider_headline,feeds.feed_provider_id order by Items desc', null);

if ($date_range) {
	echo 'Feed items posted '.$date_range[0]['min'].' - '.$date_range[0]['max']; // . date('Y-m-d', strtotime("-6 days")) . ' - ' . date('Y-m-d');
	echo '<br><br>';
}

$eye_icon = '<svg width="1.3em" height="1.3em" viewBox="0 0 16 16" class="bi bi-eye-slash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
  <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299l.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
  <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709z"/>
  <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
  </svg>';


echo '<table class="gridtable">';

foreach ($result as $row) {
	echo '<tr>';
	
	if ($row['provider_visible'] == 0)
		echo "<td>$eye_icon&nbsp;<a target='_blank' href='$base{$row['feed_provider_id']}'>".$row['provider_headline'].'</a></td>';
	else 
		echo "<td><a target='_blank' href='$base{$row['feed_provider_id']}'>".$row['provider_headline'].'</a></td>';
	
	echo '<td>'.$row['Items'].'</td>';
	echo '</tr>';
	
	$count += intval($row['Items']);
}

echo '<tr>';
echo '<td style="text-align:right">Total</td>';
echo '<td>'.$count.'</td>';
echo '</tr>';


echo '</table>';

?>

</div>
