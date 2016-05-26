<a href="?page=lbbch-options" class="hookClick <?php if($tab == ''){echo 'active';}?>">Hooks</a>
<a href="?page=lbbch-options&amp;tab=hook-logs" class="hookClick <?php if($tab == 'hook-logs'){echo 'active';}?>">Logs</a>
<div style="margin-top:50px;" class="hookList">
</div>

<div class="hookForm">

<form class="hook-form">
	  <input type="hidden" name="action" value="lbbhc_hit_url">
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
		<input type="text" name="url" style="width:600px;">
		<button type="button" class="sendHooksRequest">Send</button>
		<br />
		<div class="urlParams">
			<table class="appendUrlDiv">
				<tr>
					<td><input style="width:300px;" type="text" name='urlparams[key][]' placeholder="Key" class="urlkey"></td>
					<td>
					  <input style="width:300px;" type="text" name='urlparams[value][]' placeholder="Value" class="urlvalue">
				    <input type="checkbox" name='urlparams[is_array][]' value="1" class="is_array">Is array
					</td>
				</tr>
			</table>
		</div>
		<br />
		<a style="cursor:pointer" onclick="jQuery('.headerParams').show();jQuery('.bodyParams').hide();">Headers</a>
		<a style="cursor:pointer" onclick="jQuery('.headerParams').hide();jQuery('.bodyParams').show();">Body</a>
		<div class="headerParams" style="display:none">
			<table class="appendHeaderDiv">
				<tr>
					<td><input style="width:300px;" type="text" name='header[key][]' placeholder="Key" class="headerkey"></td>
					<td>
					  <input style="width:300px;" type="text" name='header[value][]' placeholder="Value" class="headervalue">
						<input type="checkbox" name='header[is_array][]' value="1" class="is_array">Is array
				  </td>
				</tr>
			</table>
		</div>
		<div class="bodyParams" style="display:none">
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
					<td><textarea class="raw-form-data-area" name="body-raw-value" rows="4" cols="50"></textarea></td>
				</tr>
			</table>
		</div>
	</form>
</div>
<a style="cursor:pointer" onclick="jQuery('.headerResponse').show();jQuery('.bodyResponse').hide();">Headers</a>
<a style="cursor:pointer" onclick="jQuery('.headerResponse').hide();jQuery('.bodyResponse').show();">Body</a>
<div class="headerResponse" style="">
	<table class="appendHeaderResponse">
		<tr>
			<td><textarea class="headerResponse" rows="4" cols="50"></textarea></td>
		</tr>
	</table>
</div>
<div class="bodyResponse">
	<table class="appendBodyResponse">
		<tr>
			<td><textarea class="bodyResponse" rows="10" cols="70"></textarea></td>
		</tr>
	</table>
</div>

</div>
