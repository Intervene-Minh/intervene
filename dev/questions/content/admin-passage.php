<?php
	/*
    // Pagination
    $per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
    $paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
    $query = mysql_query("SELECT `id` FROM `passages` q");		# Count total of records
    $count = (int) mysql_num_rows($query);		# Total of records
    $total = (int) ceil($count / $per_page);	# Total of pages
    $start = (int) ($paged - 1) * $per_page;	# Start of records
    $limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

	$childs = mysql_query("SELECT * FROM `passages` q ORDER BY `date_created` DESC" . $limit);
	*/

	$passages = mysql_query("SELECT * FROM passages ORDER BY date_created DESC");
	$numPassages = mysql_num_rows($passages);
?>


<div id="folder_wrap" class="content_wrap">
    <div class="ct_heading clear">
		<h3>Passages (<span id="numPassages">0</span> found)</h3>
		<ul>
			<li><a href="single-passage.php?action=new"><i class="fa fa-plus-circle"></i></a></li>
			<li><a href="javascript: void(0);" id="edit-passage"><i class="fa fa-pencil-square-o"></i></a></li>
			<?php if( isGlobalAdmin() ) : ?>
				<li><a href="javascript: void(0);" class="remove_items" data-type="passage"><i class="fa fa-trash"></i></a></li>
			<?php endif; ?>
		</ul>
	</div> <!-- /.ct_heading -->
	<table id="passagesTable" class="table table-striped cell-border hover" style="width: 100%">
		<thead>
			<th></th>
			<!--<th style="width: 40px;">&nbsp;</th>-->
			<th>Title</th>
			<th> </th>
		</thead>
		<tbody>
			<?php
			if( $numPassages > 0 ) {
				$idx = 1;
				$jsPassages = array();
				while( $passage = mysql_fetch_assoc($passages) ) {	
					$jsPassages[] = $passage;					
			?>
			<!--<tr>
				<td>
				<input type="checkbox" name="passages[]" class="edit_items" value="<?php echo $item['id']; ?>" title="Edit Passage (<?php echo $item['title']; ?>)" />
				</td>					
				<td class="title-link">
					<a href="javascript: void(0);" class="toggle-detail">
						<?php echo $passage['title']; ?>
					</a>
				</td>
				
			</tr>-->									
			<?php
				$idx++;
				}
			}
			?>
		</tbody>
	</table>
	<script src="js/dt.js"></script>
	<script type="text/javascript">
		/* Formatting function for details row - modify as you need */
		function format ( d ) {
			// `d` is the original data object for the row
			return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
				'<tr>'+
					'<td>'+d.content+'</td>'+					
				'</tr>'+
			'</table>';
		}
		$(document).ready(function () { 
			var table = $('#passagesTable').DataTable( {
				"data": <? echo json_encode($jsPassages); ?>,
				paging: false,
				"columns": [
					{
						"className": 'actions',
						"data": null,
						"render": function(data, type, row, meta){
							if(type === 'display'){
								data = '<input type="checkbox" name="passages[]" class="edit_items" value="'+row.id+'" title="Edit Passage ('+row.title+')" />';
							}
							return data;
						}
					},
					/*{
						"className":      'details-control',
						"orderable":      false,
						"data":           null,
						"defaultContent": ''
					},*/
					{ 
						"className": 'title-link',
						"data": "title",
						"render": function(data, type, row, meta){
							if(type === 'display'){
								data = '<a href="javascript:void(0);">' + data + '</a>';
							}
							return data;
						}

					}, 
					{	"className": 'tools',
						"orderable":  false,
						"data": "id",
						"render": function(data, type, row, meta){
							//if (type === 'display'){
								data = '<a href="single-passage.php?passage=' + data + '"><i class="fa fa-pencil-square-o"></i></a>'
								return data;
							//}
						}
					}
				]
			});
			// Add event listener for opening and closing details
			$('#passagesTable tbody').on('click', 'td.title-link, td.details-control', function () {
				var tr = $(this).closest('tr');
				var row =  table.row( tr );
		
				if ( row.child.isShown() ) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
				}
				else {
					// Open this row
					row.child( format(row.data()) ).show();
					tr.addClass('shown');
				}
			} );
			$('#numPassages').text(<? echo $idx ?>)
		});
	</script>
	<style>
		.dataTables_filter {
			padding-left: 10px;
			padding-top: 10px;
		}
		td.details-control {
			background: url('js/details_open.png') no-repeat center center;
			cursor: pointer;
		}
		tr.shown td.details-control {
			background: url('js/details_close.png') no-repeat center center;
		}
		.details-control {
			width: 40px;
		}
	</style>
	<!-- /.ct_display -->	
</div> <!-- /#folder_wrap -->