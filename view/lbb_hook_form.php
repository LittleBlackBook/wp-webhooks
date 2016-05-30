<div class="wp_hook_container">
	<div class="tab-container">
		<a href="?page=lbbch-options" class="hookClick tabs <?php if($tab == ''){echo 'active';}?>">Hooks</a>
		<a href="?page=lbbch-options&amp;tab=hook-logs" class="hookClick tabs <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
	</div>
	<div class="hookForm">
		<form class="hook-form">
			<input type="hidden" name="action" value="lbbhc_hit_url">
			<div>
			  <label>Applied On</label>
				<input type="checkbox" name="applied_on[]" value="Publish">Publish
				<input type="checkbox" name="applied_on[]" value="inherit ">Inherit  
				<input type="checkbox" name="applied_on[]" value="pending">Pending 
				<input type="checkbox" name="applied_on[]" value="private">Private 
				<input type="checkbox" name="applied_on[]" value="future">Future 
				<input type="checkbox" name="applied_on[]" value="draft">Draft 
				<input type="checkbox" name="applied_on[]" value="trash">Trash 
			</div>
			<select name="hook_for" class="form-control">
				<option value="""">Hook For</option>
				<option value="post">Post</option>
				<option value="page">Page</option>
			</select>
			<select name="method" class="form-control">
				<option value="GET">GET</option>
				<option value="POST">POST</option>
				<option value="DELETE">DELET</option>
				<option value="PUT">PUT</option>
			</select>
			<input type="text" name="url" placeholder="URL" style="width:600px;">
			<button type="button" class="sendHooksRequest">Send</button>
			<br />
			<div class="urlParams">
				<table class="appendUrlDiv">
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
					<tr>
						<td><input style="width:300px;" type="text" name='header[key][]' placeholder="Key" class="headerkey"></td>
						<td>
							<input style="width:300px;" type="text" name='header[value][]' placeholder="Value" class="headervalue">
						</td>
					</tr>
				</table>
			</div>
			<div class="bodyParams">
				<input type="radio" name="formdata_type" checked value="form-data" onclick="jQuery('.form-data').show();jQuery('.raw-data').hide();">Form Data
				<input type="radio" name="formdata_type" value="raw-data" onclick="jQuery('.form-data').hide();jQuery('.raw-data').show();">Raw Data
				<table class="appendBodyDiv form-data">
					<tr>
						<td><input style="width:300px;" type="text" name="body[key][]" placeholder="Key" class="bodykey"></td>
						<td>
							<input style="width:300px;" type="text" name="body[value][]" placeholder="Value" class="bodykey">
							<input type="checkbox" name='body[is_array][]' value="1" class="is_array">Is array
						</td>
					</tr>
				</table>
				<table class="appendBodyDiv raw-data" style="display:none">
					<tr>
						<td><textarea class="raw-form-data-area" name="body-raw-value" rows="10" cols="70"></textarea>
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
				<td>Header :<br><textarea class="headerResponse" rows="10" cols="70"></textarea></td>
				<td>Body :<br><textarea class="bodyResponse" rows="10" cols="70"></textarea></td>
			</tr>
		</table>
	</div>
</div>