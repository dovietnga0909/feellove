<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
	<input type="hidden" value="save" name="action">
	
	<div class="form-group">
	    <label for="content" class="col-xs-2 control-label"><?=lang('content')?></label>
	    <div class="col-xs-9">
	      	<textarea class="form-control rich-text" rows="30" name="content" id="content"><?=set_value("content", isset($pro) ? $pro["content"]:"")?></textarea>
	      	<?=form_error('content')?>
	    </div>
	</div>
  	<br>
  	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-6">
	      	<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-6">
			      	<button type="submit" class="btn btn-primary">      		
						<?=lang('btn_save')?>&nbsp;
						<span class="fa fa-download"></span>
			     	</button>
			      
			      	<?php if(isset($view_mode)):?>
			      	<a class="btn btn-primary" href="<?=site_url('newsletters/edit/'.$pro['id'])?>" role="button"><span class="fa fa-edit"></span> <?=lang('btn_edit')?></a>
			      	<?php endif;?>
			      	<a class="btn btn-default mg-left-10" href="<?=site_url('newsletters/')?>" role="button"><?=lang('btn_cancel')?></a>
			    </div>
			 </div>
	    </div>
  	</div>
</form>

<script>
tinymce.init({
	selector: "textarea.rich-text",
	menubar: false,
	theme: "modern",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor code",
    image_advtab: true,
    relative_urls: false,
    convert_urls : false,
});
</script>