<?php

/**
* @link https://pipiscrew.com
* @copyright Copyright (c) 2020 PipisCrew
*/

	require_once('general.php');
	
	date_default_timezone_set('UTC');

	$db = new dbase();
	$db->connect();
	
	$no_rows = $db->getScalar('select count(*) from feeds', null);

?>

<html>

<head>
<title>Most Wanted News! <?= $no_rows ?> </title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description"  content="PipisCrew your feeds roll on your wall" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="robots" content="noindex">
<link href="bootstrap.css" rel="stylesheet">
		 
<style>
	body {
		background-color: #222;
		color: #999;
	}
	a, a:hover, a:visited {
		text-decoration: none;
		color: #999;
		font-family: "Open Sans",sans-serif;
		font-size: 38px;
		font-weight: 700;
	    line-height: 40px;
		word-break: break-all;
	}
</style>

</head>

<body>

<div style='float:right; width: 70px;'>
 <div style='position: fixed'>
	<a style="word-break: normal;line-height: 20px;font-size: x-small" target='_blank' href="stats.php">statistics</a>
	<a style="word-break: normal;line-height: 20px;font-size: x-small" target='_blank' href="./archive">archive</a>
	<a style="word-break: normal;line-height: 20px;font-size: x-small" href="https://www.wolfgangfaust.com/project/paper-hn/" target="_blank">Hacker News</a>
	<a style="word-break: normal;line-height: 20px;font-size: x-small" href="https://unim.press/#dataisbeautiful" target="_blank">Reddit News</a>
 </div>
</div>

<div class="container-fluid">

<?php

$template = "<a href='##url##' target='_blank'>##title##</a>
				<p>##subtitle##</p>";


if (isset($_GET['d'])){
	$days_back = intval($_GET['d']);

	if ($days_back > 6)
	{
		echo 'Sorry max value is 6';
		exit;
	}
	
	$past_day = strtotime(date('Y-m-d')."UTC -{$days_back} days");
}
else
	$past_day = date('Y-m-d', strtotime('-1 days'));



$providerWhere = '';
if (isset($_GET['p'])) {
	
	if ($_GET['p'] == 'exclusive') {
		$exclusive = $db->getSet('select keyword from exclusive_keywords where isactive = 1', null);
		$exclusive_where = "'".$db->getCSV($exclusive, 'keyword','|')."'";
		
		$providerWhere = " and feed_title RLIKE  $exclusive_where ";
	}
	else {
		$providerWhereValidation = intval($_GET['p']);
		$providerWhere = ' and feeds.feed_provider_id = ' . $providerWhereValidation;
	}
}
else {
	$providerWhere = ' and provider_visible = 1 ';
}

$feeds = $db->getSet("select feed_title, feed_url, CONCAT(provider_headline,' - ', feed_date) as subtitle from feeds 
						left join providers on providers.provider_id = feeds.feed_provider_id 
						where feed_date >= '{$past_day}' " . $providerWhere . ' order by feed_id desc', null); // feed_date desc

	
foreach ($feeds as $value){
	$a = str_replace('##url##', $value['feed_url'], $template);
	$a = str_replace('##title##', $value['feed_title'], $a);
	$a = str_replace('##subtitle##', $value['subtitle'], $a);

	echo $a;
}


?>

</div>

<script>
	document.title += ' / <?=sizeof($feeds)?>'
</script>

</body>
</html>