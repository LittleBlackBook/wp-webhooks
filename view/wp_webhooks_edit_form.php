<div class="wp_hook_container">
	<div class="tab-container">
		<a href="?page=wp-webhooks-options" class="hookClick tabs <?php if($tab == ''){echo 'active';}?>">Hooks</a>
		<a href="?page=wp-webhooks-options&amp;tab=hook-logs" class="hookClick tabs <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
	</div>
	<div class="hookForm">
	  <div class="hook-form-message"></div>
		<form class="hook-form">
			<input type="hidden" name="action" value="wp_webhooks_hit_url">
			<input type="hidden" name="id" value="<?php echo $result['id'] ?>">
			<div class="applied_div">
			  <?php $applied = explode(",",trim($result["applied_on"]));?>
			  <label>Applied On: </label>
				<input type="checkbox" value="all" class="applied_on"><span>all</span>
				<input <?php if(in_array("publish",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="publish"><span>Publish</span>
				<input <?php if(in_array("inherit",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="inherit"><span>Inherit</span>
				<input <?php if(in_array("pending",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="pending"><span>Pending</span>
				<input <?php if(in_array("private",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="private"><span>Private</span>
				<input <?php if(in_array("future",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="future"><span>Future</span>
				<input <?php if(in_array("draft",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="draft"><span>Draft</span>
				<input <?php if(in_array("trash",$applied)){echo "checked";}?> type="checkbox" class="applied_on" name="applied_on[]" value="trash"><span>Trash</span>
			</div>
			<select name="hook_for" class="form-control">
				<option <?php if($result["hook_for"] == "post"){echo "selected";} ?> value="post">Post</option>
				<option <?php if($result["hook_for"] == "page"){echo "selected";} ?> value="page">Page</option>
			</select>
			<select name="method" class="form-control">
				<option <?php if($result["call_type"] == "GET"){echo "selected";} ?> value="GET">GET</option>
				<option <?php if($result["call_type"] == "POST"){echo "selected";} ?> value="POST">POST</option>
				<option <?php if($result["call_type"] == "DELETE"){echo "selected";} ?> value="DELETE">DELETE</option>
				<option <?php if($result["call_type"] == "PUT"){echo "selected";} ?> value="PUT">PUT</option>
			</select>
			<input type="text" value="<?php echo $result["url"]; ?>" name="url" style="width:600px;">
			<br />
			<?php
			$data     = json_decode(stripcslashes($result["data"]),true);
			$headers  = json_decode(stripcslashes($result["headers"]),true);
			$response = json_decode($result["response"],true);
			?>
			<div class="urlParams">
				<table class="appendUrlDiv">
					<?php if(!empty($data["get"])){
						foreach($data["get"] as $get_key => $getvalue){?>
							<tr>
								<td><input style="width:300px;" value="<?php echo $get_key ; ?>" type="text" name='urlparams[key][]' placeholder="Key" class="urlkey"></td>
								<td>
									<input style="width:300px;" value="<?php echo $getvalue ; ?>" type="text" name='urlparams[value][]' placeholder="Value" class="urlvalue">
								</td>
							</tr>
						<?php }
						}
					?>
					<tr>
						<td><input style="width:300px;" type="text" name='urlparams[key][]' placeholder="Key" class="urlkey"></td>
						<td>
							<input style="width:300px;" type="text" name='urlparams[value][]' placeholder="Value" class="urlvalue">
						</td>
					</tr>
				</table>
			</div>
			<br />
			<div class="tab-container">
				<a style="cursor:pointer" class="tabs headerTab" 
				onclick="jQuery('.headerParams').show();jQuery('.bodyParams').hide();jQuery('.bodyTab').removeClass('active');jQuery(this).addClass('active');"
				>Headers</a>
				<a style="cursor:pointer" class="tabs bodyTab active" 
				onclick="jQuery('.headerParams').hide();jQuery('.bodyParams').show();jQuery('.headerTab').removeClass('active');jQuery(this).addClass('active');">Body</a>
			</div>
			<div class="headerParams" style="display:none">
				<table class="appendHeaderDiv">
					<?php if(!empty($headers)){
						foreach($headers as $header){
							$headval = explode(":",$header);
						?>
							<tr>
								<td><input style="width:300px;" value="<?php echo $headval[0] ; ?>" type="text" name='header[key][]' placeholder="Key" class="headerkey"></td>
								<td>
									<input style="width:300px;" value="<?php echo $headval[1] ; ?>" type="text" name='header[value][]' placeholder="Value" class="headervalue">
								</td>
							</tr>
					<?php }
						}
					?>
					<tr>
						<td><input style="width:300px;" type="text" name='header[key][]' placeholder="Key" class="headerkey"></td>
						<td>
							<input style="width:300px;" type="text" name='header[value][]' placeholder="Value" class="headervalue">
						</td>
					</tr>
				</table>
			</div>
			<div class="bodyParams" >
				<input type="radio" name="formdata_type" checked value="form-data" onclick="jQuery('.form-data').show();jQuery('.raw-data').hide();">Form Data
				<input type="radio" name="formdata_type" value="raw-data" onclick="jQuery('.form-data').hide();jQuery('.raw-data').show();">Raw Data
				<table class="appendBodyDiv form-data" <?php if($data["form-data"] == "form-data"){}else{echo "style='display:none'";} ?>>
					<?php if(!empty($data["post"]) && $data["form-data"] == "form-data"){
						foreach($data["post"] as $post_key => $postvalue){
							if(is_array($postvalue)){
								foreach($postvalue as $val){
							?>
							<tr>
								<td><input style="width:300px;" value="<?php echo $post_key ; ?>" type="text" name="body[key][]" placeholder="Key" class="bodykey"></td>
								<td>
									<input style="width:300px;" value="<?php echo $val ; ?>" type="text" name="body[value][]" placeholder="Value" class="bodykey">
									<input type="checkbox" checked name='body[is_array][]' value="1" class="is_array">Is array
								</td>
							</tr>
						<?php }}else{ ?>
							<tr>
								<td><input style="width:300px;" value="<?php echo $post_key ; ?>" type="text" name="body[key][]" placeholder="Key" class="bodykey"></td>
								<td>
									<input style="width:300px;" value="<?php echo $postvalue ; ?>" type="text" name="body[value][]" placeholder="Value" class="bodykey">
									<input type="checkbox" name='body[is_array][]' value="1" class="is_array">Is array
								</td>
							</tr>
						<?php }}
						}
					?>
					<tr>
						<td><input style="width:300px;" type="text" name="body[key][]" placeholder="Key" class="bodykey"></td>
						<td>
							<input style="width:300px;" type="text" name="body[value][]" placeholder="Value" class="bodykey">
							<input type="checkbox" name='body[is_array][]' value="1" class="is_array">Is array
						</td>
					</tr>
				</table>
				<table class="appendBodyDiv raw-data" <?php if($data["form-data"] == "row-data"){}else{echo "style='display:none'";} ?>>
					<tr>
						<td>
						  <td><textarea class="raw-form-data-area" name="body-raw-value" rows="10" cols="70"><?php echo $result["data"]; ?></textarea>
						<button type="button" class="validateJson">Validate</button>
						<span class="Validateresult"></span>
						</td>
					</tr>
				</table>
			</div>
			<button type="button" class="addNewButton sendHooksRequest">Save
			  <span class="loader-image">
			    <img class="" src="<?php echo plugins_url( '../images/ajax-loading.gif', __FILE__ ) ?>">
				</span>
			</button>
		</form>
		<div class="hook-form-message"></div>
	</div>
	<?php /*
	<div class="" style="">
		<table class="appendHeaderResponse">
			<tr>
				<td>Header :<br><textarea class="headerResponse" rows="10" cols="70"><?php echo $response["header"]; ?></textarea></td>
				<td>Body :<br><textarea class="bodyResponse" rows="10" cols="70"><?php echo $response["body"]; ?></textarea></td>
			</tr>
		</table>
	</div>
	*/ ?>
</div>
<?php include("wp_webhooks_notes.php"); ?>
