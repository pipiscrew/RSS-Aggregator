CREATE TABLE `feeds` (
  `feed_id` int(11) NOT NULL,
  `feed_provider_id` int(11) NOT NULL,
  `feed_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `feed_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `feed_date` datetime DEFAULT NULL,
  `feed_hash` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `providers` (
  `provider_id` int(11) NOT NULL,
  `provider_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `provider_enabled` int(11) DEFAULT NULL,
  `provider_visible` int(11) DEFAULT NULL,
  `provider_once_per_day` int(11) DEFAULT NULL,
  `provider_last_run` datetime DEFAULT NULL,
  `provider_headline` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `benchmark` float(5,4) NOT NULL,
  `provider_order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `provider_logs` (
  `provider_log_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `reason` varchar(50) NOT NULL,
  `date_rec` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `feeds`
  ADD PRIMARY KEY (`feed_id`);

ALTER TABLE `providers`
  ADD PRIMARY KEY (`provider_id`);

ALTER TABLE `provider_logs`
  ADD PRIMARY KEY (`provider_log_id`);

ALTER TABLE `feeds`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `providers`
  MODIFY `provider_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `provider_logs`
  MODIFY `provider_log_id` int(11) NOT NULL AUTO_INCREMENT;
 
COMMIT;

INSERT INTO `providers` (`provider_id`, `provider_url`, `provider_enabled`, `provider_visible`, `provider_once_per_day`, `provider_last_run`, `provider_headline`, `benchmark`, `provider_order`) VALUES
(1, 'http://feeds.bbci.co.uk/news/world/europe/rss.xml	', 1, 1, 0, '2020-08-16 08:21:58', 'BBC', 0.0000, 5);

COMMIT;