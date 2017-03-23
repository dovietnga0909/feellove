<?php if(!empty($msg)):?>
<div class="alert alert-success" style="margin-bottom: 20px">
	<?=$msg?>
</div>
<?php endif;?>
<p class="text-muted">Enter the link to clear cache otherwise it will clear cache of landing pages</p>
<form role="form" name="frm" method="post">
    <div class="row">
        <div class="form-group col-xs-6">
            <input type="text" class="form-control" name="cache_link" placeholder="Enter the link">
        </div>
    	<button type="submit" class="btn btn-danger" name="action" value="clear_cache">
    		<i class="fa fa-clock-o"></i> Clear Cache
    	</button>
    </div>
</form>