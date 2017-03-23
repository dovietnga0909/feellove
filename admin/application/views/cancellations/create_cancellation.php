
<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<form class="form-horizontal" role="form" method="post">
	<input type="hidden" value="save" name="action">

  <div class="form-group">
    <label for="name" class="col-sm-2 control-label"><?=lang('can_field_name')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="name" placeholder="<?=lang('cancellation_name')?>..." name="name" value="<?=set_value('name')?>">
      <?=form_error('name')?>
    </div>
  </div>
  
  <div class="form-group">
    <label for=can_service_type class="col-sm-2 control-label"><?=lang('can_service_type')?> <?=mark_required()?></label>
   
    <div class="col-sm-6">
	    <div class="checkbox-inline">
			<label>
				<input type="checkbox" name="service_type[]" value="<?=HOTEL?>" <?=set_checkbox('service_type')?>><?=lang('hotel')?>
			</label>
		</div>
		<div class="checkbox-inline">
			<label>
				<input type="checkbox" name="service_type[]" value="<?=CRUISE?>" <?=set_checkbox('service_type')?>><?=lang('cruise')?>
			</label>
		</div>
	    <div class="checkbox-inline">
			<label>
				<input type="checkbox" name="service_type[]" value="<?=TOUR?>" <?=set_checkbox('service_type')?>><?=lang('tour')?>
			</label>
		</div>
	</div>
  </div>
  
  <div class="form-group">
    <label for="fit" class="col-sm-2 control-label">
    	<?=lang('can_field_fit')?> <?=mark_required()?>
    </label>
    <div class="col-sm-2">
      
     	<select class="form-control" id="fit" name="fit">
		  <option value=""><?=lang('please_select')?></option>
		  <?php foreach ($fit_nr as $value):?>
		  		<option value="<?=$value?>" <?=set_select('fit',$value)?>><?=$value?></option>
		  <?php endforeach;?>		
		</select>
		<?=form_error('fit')?>
      
    </div>
  </div>
  
  <div class="form-group">
    <label for="fit_cutoff" class="col-sm-2 control-label"><?=lang('can_field_fit_cutoff')?> <?=mark_required()?></label>
    <div class="col-sm-2">      
      <select class="form-control" id="fit_cutoff" name="fit_cutoff">
		  <option value=""><?=lang('please_select')?></option>
		  <?php foreach ($fit_cutoff as $value):?>
		  		<option value="<?=$value?>" <?=set_select('fit_cutoff', $value)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select> 
	  <?=form_error('fit_cutoff')?>     
    </div>
  </div>
  
  <div class="form-group">
    <label for="git_cutoff" class="col-sm-2 control-label"><?=lang('can_field_git_cutoff')?> <?=mark_required()?></label>
    <div class="col-sm-2">
      
      <select class="form-control" id="git_cutoff" name="git_cutoff">
		  <option value=""><?=lang('please_select')?></option>
		  <?php foreach ($git_cutoff as $value):?>
		  		<option value="<?=$value?>" <?=set_select('git_cutoff',$value)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select>
      <?=form_error('git_cutoff')?> 
    </div>
  </div>
  
  <div class="form-group">
    <label for="content" class="col-sm-2 control-label"><?=lang('can_field_content')?> <?=mark_required()?></label>
    <div class="col-sm-8">
      <textarea class="form-control rich-text" rows="7" name="content" id="content" placeholder="<?=lang('cancellation_content')?>..."><?=set_value('content')?></textarea>
      <?=form_error('content')?>
    </div>
  </div>
 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">
      	<span class="fa fa-download"></span>	
		<?=lang('btn_save')?>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url()?>cancellations/" role="button"><?=lang('btn_cancel')?></a>
    </div>
  </div>
</form>

<script type="text/javascript">
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
	});
</script>
