<div class="wp_hook_container">
	<div class="tab-container">
		<a href="?page=wp-webhooks-options" class="hookClick tabs <?php if($tab == ''){echo 'active';}?>">Hooks</a>
		<a href="?page=wp-webhooks-options&amp;tab=hook-logs" class="hookClick tabs <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
	</div>
	<table class="wp_hooks_table">
		<tr>
			<th>Hook Id</th>
			<th>Post Id</th>
			<th>Post Type</th>
			<th>Status</th>
			<th>Date</th>
		</tr>
		<?php 
		if(!empty($data)){
		foreach($data as $key => $val){?>
			<tr>
				<td><?php echo $val["hook_id"]; ?></td>
				<td><?php echo $val["post_id"]; ?></td>
				<td><?php echo $val["post_type"]; ?></td>
				<td><?php echo response_codes($val["response_code"]); ?></td>
				<td><?php echo $val["date_added"]; ?></td>
			</tr>
		<?php }}else{ ?>
		  <tr><td colspan="5">No Logs Found.</td></tr>
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