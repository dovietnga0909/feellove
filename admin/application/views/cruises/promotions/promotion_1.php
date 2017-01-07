
<?=$pro_step?>

<form class="form-horizontal" role="form" method="post">
	
   <input type="hidden" value="next" name="action">
	
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label"><?=lang('pro_field_name')?> <?=mark_required()?></label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="name" placeholder="<?=lang('pro_field_name')?>..." name="name" value="<?=set_value('name', isset($pro)?$pro['name']:'')?>">
      <?=form_error('name')?>
    </div>
  </div>
 
  
  <div class="form-group">
    <label for="promotion_type" class="col-sm-2 control-label"><?=lang('pro_field_type')?> <?=mark_required()?></label>
    <div class="col-sm-3">
      
      <select class="form-control" id="promotion_type" name="promotion_type">
		  <option value=""><?=lang('please_select')?></option>
		  <?php foreach ($promotion_types as $key => $value):?>
		  	<option value="<?=$key?>" <?=set_select('promotion_type',$key, isset($pro) && $pro['promotion_type'] == $key)?>><?=$value?></option>
		  <?php endforeach;?>
	  </select>
	  
      <?=form_error('promotion_type')?>
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="show_on_web" class="col-sm-2 control-label"><?=lang('pro_field_show_on_web')?> <?=mark_required()?></label>
    <div class="col-sm-3">
      
      <select class="form-control" id="show_on_web" name="show_on_web">
		  <option value=""><?=lang('please_select')?></option>
		  <option value="1" <?=set_select('show_on_web',1, isset($pro) && $pro['show_on_web'] == 1)?>><?=lang('yes')?></option>
		  <option value="0" <?=set_select('show_on_web',0, isset($pro) && $pro['show_on_web'] == 0)?>><?=lang('no')?></option>
		  
	  </select>
	  
      <?=form_error('show_on_web')?>
    </div>
  </div>

  
  <div class="form-group">
    <label for="description" class="col-sm-2 control-label"><?=lang('pro_field_offer')?></label>
    <div class="col-sm-6">
      <textarea class="form-control rich-text" rows="3" name="offer" id="description" placeholder="<?=lang('pro_field_offer')?>..."><?=set_value('offer', isset($pro)?$pro['offer']:'')?></textarea>
      <?=form_error('offer')?>
    </div>
  </div>
  <br>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary">      		
		<?=lang('btn_next')?>&nbsp;
		<span class="fa fa-arrow-right"></span>
      </button>
      <a class="btn btn-default mg-left-10" href="<?=site_url('cruises/promotions/'.$cruise_id.'/')?>" role="button"><?=lang('btn_cancel')?></a>
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
});
</script>