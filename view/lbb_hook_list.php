<div class="wp_hook_container">
  <div class="tab-container">
		<a href="?page=lbbch-options" class="hookClick tabs <?php if($tab == ''){echo 'active';}?>">Hooks</a>
		<a href="?page=lbbch-options&amp;tab=hook-logs" class="hookClick tabs <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
	</div>
	<div class="addNew">
	<?php $statusArray = array("Inactive","Active"); ?>
	<a href="?page=lbbch-options&amp;tab=hook-form" class="hookClick addNewButton 
	<?php if($tab == 'hook-form'){echo 'active';}?>">
	  Add New Hook</a>
  </div>
	<div>
	<table>
		<tr>
			<th>Hook For</th>
			<th>Call Type</th>
			<th>URL</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
		<?php foreach($data as $key => $val){?>
			<tr>
				<td><?php echo strtoupper($val["hook_for"]); ?></td>
				<td><?php echo strtoupper($val["call_type"]); ?></td>
				<td><?php echo $val["url"]; ?></td>
				<td><?php echo $statusArray[$val["status"]]; ?></td>
				<td>
					<a href="?page=lbbch-options&amp;tab=edit-hook-form&id=<?php echo $val["id"];?>">Edit</a>
					<a href="?page=lbbch-options&amp;tab=delete-hook&id=<?php echo $val["id"];?>">Delete</a>
				</td>
			</tr>
		<?php } ?>
	</table>
	<?php
	  echo paginate_links( array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => ceil($total / $per_page),
        'current' => $page
    ));
	?>
	</div>
</div>