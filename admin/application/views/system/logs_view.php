<form class="form-inline" role="form" name="frm" method="post">
	<div class="form-group" id="group_start_date">
		<div class="input-append date input-group">
		<input type="text" class="form-control" id="log_date" name="log_date" value="<?=set_value('log_date')?>" placeholder="<?=DATE_FORMAT_CALLENDAR?>...">
		<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		</div>
	</div>
	
	<div class="form-group">
		<input type="text" class="form-control" name="keyword" value="<?=set_value('keyword')?>" placeholder="Keyword">
	</div>
	
	<button type="submit" class="btn btn-primary">View Log</button>
	
	<p><?=form_error('log_date')?></p>
</form>

<br>

<div id="log_content" style="width: 100%;">
<?php if(isset($content)) echo $content;?>
</div>
<script>
$('#group_start_date .input-append.date.input-group').datepicker({
    format: "<?=DATE_FORMAT_CALLENDAR?>",
    autoclose: true,
    todayHighlight: true
});
</script>