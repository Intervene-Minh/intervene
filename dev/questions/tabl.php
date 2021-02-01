<!DOCTYPE html>
<html>
<head>
<title>Responsive and Sortable Tables</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700|Roboto:300,400,400i,700" rel="stylesheet">
<script src="https://use.fontawesome.com/47886b77a3.js"></script>
<style type="text/css">
	*, *:before, *:after {
	-webkit-box-sizing: border-box;
	   -moz-box-sizing: border-box;
			box-sizing: border-box;
}
html {
	font-size: 1.125em; /*18px*/
	width: 100%;
	height: 100%;
}
body {
	font-family: 'Roboto', sans-serif;
	background: #f5f5f5;
	color: #333;
	-webkit-font-smoothing: antialiased;
	line-height: 1.42857143;
  padding:1em;
}
small {color:#808080;}

button {
	bottom:1px;
  cursor:pointer;
  margin-right:8px;
  position:relative;
  padding:4px 11px;
  border:1px solid #0085a6;
  background:none;
  border-radius:3px;
  color:#0085a6;
  font-size:1em;
	transition:all .3s ease-in-out;
}
button:hover {background:#0085a6; color:#fff;}

/* Tables */
	/* Responsive scroll-y table */
.table-responsive {min-height:.01%;	overflow-x:auto;}
@media screen and (max-width: 801px) {
	.table-responsive {width:100%; overflow-y:hidden; -ms-overflow-style:-ms-autohiding-scrollbar;}
}
	/* Default table */
table {
	border-collapse:collapse;
	border-spacing:0;
	-webkit-box-shadow:0px 7px 6px -6px rgba(0,0,0,.28);
	   -moz-box-shadow:0px 7px 6px -6px rgba(0,0,0,.28);
			    box-shadow:0px 7px 6px -6px rgba(0,0,0,.28);
	margin-bottom:40px;
	margin-top:.5em;	
	width:100%; 
	max-width:100%;
}
table thead tr {border-bottom:3px solid #0085a6; color:#000;}
table tfoot tr {border-top:3px solid #0085a6;}
table thead th, table tfoot th {
	background-color:#fff;
	color:#000;
	font-size:.83333em;
	line-height:1.8;
	padding: 15px 14px 13px 14px;
	position:relative;
	text-align:left;
	text-transform:uppercase;	
}
table tbody tr {background-color:#fff;}
table tbody tr:hover {background-color:#eee; color:#000;}
table th, table td {
	border:1px solid #bfbfbf;
	padding:10px 14px;
	position:relative;
	vertical-align:middle;
}
caption {font-size:1.111em; font-weight:300; padding:10px 0;}

@media (max-width:1024px) {
	table {font-size: .944444em;}
}
@media (max-width:767px) {
	table {font-size: 1em;}
}

 /* Responsive table full */
@media (max-width: 767px) {
	.table-responsive-full {box-shadow:none;}
	.table-responsive-full thead tr, 
	.table-responsive-full tfoot tr {display:none;}
	.table-responsive-full tbody tr {
		-webkit-box-shadow:0px 7px 6px -6px rgba(0,0,0,.28);
		   -moz-box-shadow:0px 7px 6px -6px rgba(0,0,0,.28);
				    box-shadow:0px 7px 6px -6px rgba(0,0,0,.28);
		margin-bottom:20px;
	}
	.table-responsive-full tbody tr:last-child {margin-bottom:0;}
	.table-responsive-full tr,
	.table-responsive-full td {display:block;}
	.table-responsive-full td {
		background-color:#fff;
		border-top:none;
		position:relative;
		padding-left:50%;
	}
	.table-responsive-full td:hover {background-color:#eee; color:#000;}
	.table-responsive-full td:hover:before {color:hsl(0, 0%, 40%);}
	
	.table-responsive-full td:first-child {
		border-top:1px solid #bfbfbf;
		border-bottom: 3px solid #0085a6;
		border-radius: 4px 4px 0 0;
		color: #000;
		font-size: 1.11111em;
		font-weight: bold;
	}
	.table-responsive-full td:before {
		content: attr(data-label);
		display: inline-block;
		color: hsl(0, 0%, 60%);
		font-size: 14px;
		font-weight: normal;
		margin-left: -100%;
		text-transform: uppercase;
		width: 100%;
		white-space:nowrap;
	}
}
@media (max-width: 360px) {
	.table-responsive-full td {padding-left:14px;}
	.table-responsive-full td:before {display:block; margin-bottom:.5em; margin-left:0;}
}
	/* Sort table */
.sort-table-arrows {float:right; transition:.3s ease;}
.sort-table-arrows button {margin:0; padding:4px 8px;}
.sort-table th.title, .sort-table th.composer {width:20% !important;}
.sort-table th.lyrics, .sort-table th.arranger, .sort-table th.set, .sort-table th.info {width:15% !important;}
.sort-table .title {font-weight: bold;}
.sort-table .title small {font-weight:normal;}

@media (max-width:1024px) {
	.sort-table th,.sort-table-arrows {text-align:center;}
	.sort-table-arrows {float:none; padding:8px 0 0; position:relative; right:0px;}
	.sort-table-arrows button {bottom:0;}
}
@media (max-width:767px) {
	.sort-table thead tr {border-bottom:none; display:block; margin:10px 0; text-align:center;}
	.sort-table thead tr th.arranger {display:none;}
	.sort-table th {
		border-bottom:1px solid #bfbfbf;
		border-radius:4px;
		display:inline-block;
		font-size:.75em;
		line-height:1;
		margin:3px 0;
		padding:10px;
	}
	.sort-table th.title, .sort-table th.composer, .sort-table th.lyrics, .sort-table th.set, .sort-table th.info {width: 100px !important;}
	.sort-table td.title:before {display:none;}
	.sort-table td.title {letter-spacing:.03em; padding-left:14px;}
}

</style>
<script type="text/javascript">
		function sort(ascending, columnClassName, tableId) {
		var tbody = document.getElementById(tableId).getElementsByTagName(
				"tbody")[0];
		var rows = tbody.getElementsByTagName("tr");
		var unsorted = true;
		while (unsorted) {
			unsorted = false
			for (var r = 0; r < rows.length - 1; r++) {
				var row = rows[r];
				var nextRow = rows[r + 1];
				var value = row.getElementsByClassName(columnClassName)[0].innerHTML;
				var nextValue = nextRow.getElementsByClassName(columnClassName)[0].innerHTML;
				value = value.replace(',', ''); // in case a comma is used in float number
				nextValue = nextValue.replace(',', '');
				if (!isNaN(value)) {
					value = parseFloat(value);
					nextValue = parseFloat(nextValue);
				}
				if (ascending ? value > nextValue : value < nextValue) {
					tbody.insertBefore(nextRow, row);
					unsorted = true;
				}
			}
		}
	};
</script>

</head>
<body>



<h2>Table - responsive (data-label) full & Sortable</h2>
<table id="content-table3" class="table-responsive-full sort-table">
	<thead>
		<tr>
			<th class="title">Title
				<div class="sort-table-arrows">
					<a href="javascript:sort(true, 'title', 'content-table3');"><button class="button" title=""><i class="fa fa-chevron-down"></i></button></a>
					<a href="javascript:sort(false, 'title', 'content-table3');"><button class="button" title=""><i class="fa fa-chevron-up"></i></button></a>
				</div>
			</th>
			<th class="composer">Composer
				<div class="sort-table-arrows">
					<a href="javascript:sort(true, 'composer', 'content-table3');"><button class="button" title=""><i class="fa fa-chevron-down"></i></button></a>
					<a href="javascript:sort(false, 'composer', 'content-table3');"><button class="button" title=""><i class="fa fa-chevron-up"></i></button></a>
				</div>
			</th>
			<th class="lyrics">Lyrics
				<div class="sort-table-arrows">
					<a href="javascript:sort(true, 'lyrics', 'content-table3');"><button class="button" ><i class="fa fa-chevron-down"></i></button></a>
					<a href="javascript:sort(false, 'lyrics', 'content-table3');"><button class="button" ><i class="fa fa-chevron-up"></i></button></a>
				</div>
			</th>
			<th class="set">Set
				<div class="sort-table-arrows">
					<a href="javascript:sort(true, 'set', 'content-table3');"><button class="button" title="Αύξουσα ταξινόμηση"><i class="fa fa-chevron-down"></i></button></a>
					<a href="javascript:sort(false, 'set', 'content-table3');"><button class="button" title="Φθίνουσα ταξινόμηση"><i class="fa fa-chevron-up"></i></button></a>
				</div>
			</th>
			<th class="arranger">Arranger / Editing</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-label="Title" class="title" class="title">Come in and stay a while</td>
			<td data-label="Composer" class="composer">Polay, Rhonda</td>
			<td data-label="Lyrics" class="lyrics">Polay, Rhonda</td>
			<td data-label="Set" class="set">SSAATTB a-cappella</td>
			<td data-label="Arranger">Sarandakos, S. &amp; Legakis, M. / Legakis, M. (Ed.)</td>
		</tr>
		<tr>
			<td data-label="Title" class="title">Eleanor Rigby</td>
			<td data-label="Composer" class="composer">Lennon, J. | McCartney, P.</td>
			<td data-label="Lyrics" class="lyrics">Lennon, J. | McCartney, P.</td>
			<td data-label="Set" class="set"><span>SSATTBB a-cappella</span></td>
			<td data-label="Arranger">Hare, N. &amp; Legakis, M., / Legakis, M. (Ed.)</td>
		</tr>
		<tr>
			<td data-label="Title" class="title">In memoriam<br><small>(from "Les Choristes")</small></td>
			<td data-label="Composer" class="composer">Coulais, B.</td>
			<td data-label="Lyrics" class="lyrics">Barratier, C.</td>
			<td data-label="Set" class="set"><span>SSAATTBB, Pno.</span></td>
			<td data-label="Arranger">Legakis, M.</td>
		</tr>
		<tr>
			<td data-label="Title" class="title">Lover Man</td>
			<td data-label="Composer" class="composer">Davies, J. | Ramirez, R. | Sherman, J.</td>
			<td data-label="Lyrics" class="lyrics">Davies, J. | Ramirez, R. | Sherman, J.</td>
			<td data-label="Set" class="set"><span>SATB, Pno.</span></td>
			<td data-label="Arranger">Legakis, M.</td>
		</tr>
		<tr>
			<td data-label="Title" class="title">Lueur d'été<br><small>(from "Les Choristes")</small></td>
			<td data-label="Composer" class="composer">Coulais, B. </td>
			<td data-label="Lyrics" class="lyrics">Barratier, C.</td>
			<td data-label="Set" class="set"><span>SSAATTBB, Pno.</span></td>
			<td data-label="Arranger">Legakis, M.</td>
		</tr>
		<tr>
			<td data-label="Title" class="title">Stairway to Paradise<br><small>(from "George White's Scandals of 1922")</small></td>
			<td data-label="Composer" class="composer">Gershwin, G. | Francis, A. | De Sylva B.G.</td>
			<td data-label="Lyrics" class="lyrics">Gershwin, I.</td>
			<td data-label="Set" class="set"><span>SATTBB, Pno.</span></td>
			<td data-label="Arranger">Legakis, M.</td>
		</tr>
		<tr>
			<td data-label="Title" class="title">Who will buy?<br><small>(from "Oliver")</small></td>
			<td data-label="Composer" class="composer">Burt, Lionel</td>
			<td data-label="Lyrics" class="lyrics">Burt, Lionel</td>
			<td data-label="Set" class="set"><span>SSAATTBB, Pno.</span></td>
			<td data-label="Arranger">Legakis, M.</td>
		</tr>
	</tbody>
</table>


</body>
</html>
