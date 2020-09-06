<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>
			Most Wanted News! [archive]
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-table.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-table.min.js"></script>
				
		<style>
			/*bootstrap-table selected row*/
			.fixed-table-container tbody tr.selected td	{ background-color: #B0BED9; }	
		</style>

		<script>

			$(function ()
				{
					$('#feeds_tbl').bootstrapTable();
					
					var $search = $('.fixed-table-toolbar .search input');
					$search.attr('placeholder', 'use comma for keywords');

				}); //jQ ends
				
			    function urlFormatter(value, row) {
			    	
					if (value && value!="null")
					{
						var url = row.feed_url;
						
						if (url.length > 120)
							url = url.substring(0,120);
							
						var s = "<a href='" + row.feed_url + "' target='_blank'>" + url + "</a>";
						return s;
					}
					else 
						return "";
				}
		
				//bootstrap-table
				function queryParamsFEEDS(params)
				{
					var q = {
						"limit": params.limit,
						"offset": params.offset,
						"search": params.search,
						"name": params.sort,
						"order": params.order
					};
 
					return q;
				}
		</script>

	</head>
	<body>
		<!--<div class="container">-->
		
			<table id="feeds_tbl"
	           data-toggle="table"
	           data-striped=true
	           data-url="pagination.php"
	           data-search="true"
	           data-show-refresh="true"	          
	           data-pagination="true"
	           data-page-size="20"
	           data-sort-name="feed_date"
	           data-sort-order="desc"
	           data-side-pagination="server"
	           data-query-params="queryParamsFEEDS">

				<thead>
					<tr>
				
						<th data-field="feed_title" data-sortable="true">
							feed_title
						</th>
						
						<th data-field="feed_url" data-formatter="urlFormatter" data-sortable="true">
							feed_url
						</th>
						
						<th data-field="feed_date" data-sortable="true">
							feed_date
						</th>	

					</tr>
				</thead>
			</table	>
		<!--</div>-->

	</body>
</html>