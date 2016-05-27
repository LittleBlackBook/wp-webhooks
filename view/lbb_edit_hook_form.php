<div class="wp_hook_container">
	<div class="tab-container">
		<a href="?page=lbbch-options" class="hookClick tabs <?php if($tab == ''){echo 'active';}?>">Hooks</a>
		<a href="?page=lbbch-options&amp;tab=hook-logs" class="hookClick tabs <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
	</div>
	<div class="hookForm">

		<form class="hook-form">
			<input type="hidden" name="action" value="lbbhc_hit_url">
			<input type="hidden" name="id" value="<?php echo $result['id'] ?>">
			<select name="hook_for" class="form-control">
				<option value="">Hook For</option>
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
			<button type="button" class="sendHooksRequest">Send</button>
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
				<a style="cursor:pointer" class="tabs bodyTab" 
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
		</form>
	</div>
	<div class="" style="">
		<table class="appendHeaderResponse">
			<tr>
				<td>Header :<br><textarea class="headerResponse" rows="10" cols="70"><?php echo $response["header"]; ?></textarea></td>
				<td>Body :<br><textarea class="bodyResponse" rows="10" cols="70"><?php echo $response["body"]; ?></textarea></td>
			</tr>
		</table>
	</div>
</div>
