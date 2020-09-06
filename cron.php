<?php

/**
* @link https://pipiscrew.com
* @copyright Copyright (c) 2020 PipisCrew
*/

date_default_timezone_set('UTC');

// set timeout to 5 min
set_time_limit(300);

// make sure to keep alive the script when a client disconnect.
ignore_user_abort(true);

require_once('general.php');
require_once('SimplePie.php');

$db = new dbase();
$db->connect();

$no_rows = $db->getScalar('select count(*) from feeds', null);

$providers = $db->getSet('select * from providers where provider_enabled=1 order by provider_order', null);

$skip6daysBack = strtotime(date('Y-m-d').'UTC -6 days');

foreach ($providers as $provider) {

	// [benchmark] hold the start time
	$time_start = microtime(true);
	
	$provider_id = $provider['provider_id'];

	$feed = new SimplePie();

	$feed->set_feed_url($provider['provider_url']);

	//disable cacbe
	$feed->enable_cache(false);

	//here reads the feed
	$success = $feed->init();

	// [benchmark] hold end time
	$time_end = microtime(true);
	
	if (!$success)
	{
		$db->executeSQL('INSERT INTO provider_logs (provider_id, reason, date_rec) VALUES (?,?,?)', array($provider_id, 'simplepie no success', date('Y-m-d H:i:s')));
		continue;
	}

	$feed->handle_content_type();
	
	$feed_items = $feed->get_items();
	
	if (sizeof($feed_items)==0) {
		$db->executeSQL('INSERT INTO provider_logs (provider_id, reason, date_rec) VALUES (?,?,?)', array($provider_id, 'rss item count is 0', date('Y-m-d H:i:s')));
		continue;
	}
	
	$benchmarkSaved = false;
	
	foreach ($feed_items as $item) {

		//feed_item date validation
		$pubDate = $item->get_date('Y-m-d H:i:s'); //https://github.com/simplepie/simplepie/issues/499#issue-201935650
		$feedItemDatePosted = strtotime($pubDate);
		
		if ($feedItemDatePosted < $skip6daysBack)
			continue;
		else if (!$benchmarkSaved) {

			$execution_time = ($time_end - $time_start) / 60;
			$db->executeSQL('update providers set benchmark = ?, provider_last_run = ? where provider_id = ?', array($execution_time, date('Y-m-d H:i:s'), $provider_id));
			
			$benchmarkSaved = true;
		}
		//feed_item date validation
		
		$title = $item->get_title();
		
		if (strlen($title) > 255)
			$title = substr($title, 0, 255);
			
		$url = $item->get_permalink();
		$feed_item_hash = md5($title);

		$duplicate = $db->getScalar('select count(feed_id) from feeds where feed_hash=?', array($feed_item_hash) );
		
		if ($duplicate!=0)
			continue;
		
		
		$db->executeSQL('INSERT INTO feeds (feed_provider_id, feed_title, feed_url, feed_date, feed_hash) VALUES 
						(:feed_provider_id, :feed_title, :feed_url, :feed_date, :feed_hash)',
						array(':feed_provider_id' => $provider_id , 
								':feed_title' => $title, 
								':feed_url' => $url, 
								':feed_date' => $pubDate, 
								':feed_hash' => $feed_item_hash));
	}	
}


?>