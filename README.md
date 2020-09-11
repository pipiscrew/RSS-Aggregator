# RSS-Aggregator

Is a minimal PHP application (15 files) designed to parse and display feeds. Contains a cron job and a simple responsive frontend.

![1503](https://user-images.githubusercontent.com/3852762/92325863-89531a80-f056-11ea-9d48-5c2d20dd6f14.jpg)

For feed parsing uses [simplepie monolithic v1.5.5](https://github.com/simplepie/simplepie), responsiveness by bootstrap.

The main feed page displays articles for yesterday & today. User is able to see older articles by providing `?d=6` parameter to URL, which translated six days back.

The feed URLs have to setup directly to MySQL at **providers** table. When an error occurs on parsing (cron.php) written on **providers_log** table.

To start work with :

- create the tables (create_tables.sql)
- add a cron job as `php -q /home/public_html/test/cron.php` or execute it manually

In addition there are two more pages :

- The **statistics** page (stats.php) where displays the total records for each feed. When a feed name clicked display the articles posted till six days ago for the current feed.

- Tne **archive** page where user can search the feeds stored (without date limitation). Is accessible by browsing at `/archive` folder. The grid used with server pagination is (an old version) of wenzhixin - bootstrap-table.

When the **providers.provider_enabled** set to `0`, feed is disabled (will not get parsed).

The **providers.provider_headline** is what written on article subtitle (index.php).

The **providers.benchmark** is the (last) time made to download and parse the feed (cron.php).

The **providers.provider_order** is the order used for parsing mechanism (cron.php). The application to avoid duplicates, on each feed article has the **feeds.feed_hash** field which is the md5 hash of the title.

When the **providers.provider_visible** set to `0`, the feed continuing to get parsed but is not displayed to main feed page (index.php). The only way to access the articles for this feed is by `archive` or `statistics` page (it will have the `eye` icon).

At `index.php` items sorted by `id`, because some feeds providing a date without timestamp or is not UTC.

If the feed url you trying, is unable to get parsed by simplepie, is because of curl SSL error, add this line to `SimplePie.php` : 7209
```
curl_setopt($fp, CURLOPT_SSL_VERIFYPEER, false);
```

If you getting record insert error, use `utf8mb4` 'character set' at dbase, or use `General.escape_str` function at insertion (cron.php).

> feature/exclusive :

Added a new table **exclusive_keywords**, this will be a feed in **statistics** page (stats.php) called `exclusive`. When this clicked, any feed title contains any of the `exclusive_keywords.keywords` text, will appear. The existence of this is to filter the important feed articles.

After all, if is not enough, try 
- [miniflux](https://github.com/denfil/miniflux-php)
- [Tiny Tiny RSS](https://tt-rss.org/)
- [InBefore](https://codecanyon.net/item/inbefore-news-aggregator-search-engine-youtube-downloader/24809255) (paid)


# This project uses the following 3rd-party dependencies :
- [simplepie](https://simplepie.org/)<br>
- [wenzhixin/bootstrap-table](https://github.com/wenzhixin/bootstrap-table)<br>
- [bootstrap](https://getbootstrap.com/)<br>


## This project is no longer maintained
Copyright (c) 2020 [PipisCrew](http://pipiscrew.com)

Licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).
