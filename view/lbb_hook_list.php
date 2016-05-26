<div class="wp_hook_container">
<a href="?page=lbbch-options" class="hookClick <?php if($tab == ''){echo 'active';}?>">Hooks</a>
<a href="?page=lbbch-options&amp;tab=hook-logs" class="hookClick <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
<?php $statusArray = array("Inactive","Active"); ?>
<a href="?page=lbbch-options&amp;tab=hook-form" class="hookClick <?php if($tab == 'hook-form'){echo 'active';}?>">Add New Hook</a>
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
</div>