<?php if(empty($ad)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url()?>advertises/" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>

<?php if(isset($save_status) && $save_status === FALSE):?>
	<div class="alert alert-danger">
		<?=lang('fail_to_save')?>
	</div>
<?php endif;?>

<?php if(!empty($uploaded_errors)):?>
	
	<?=$uploaded_errors?>
	
<?php endif;?>

<form role="form" method="post" enctype="multipart/form-data" class="form-horizontal">

  	<div class="form-group">
    	<label for="name" class="col-sm-2 control-label"><?=lang('ad_field_ad_name')?></label>
    	<div class="col-sm-6">
      		<label id="name" class="control-label"><?=$ad['name']?></label>
	    </div>
	</div>
	
	<div class="form-group">
    	<label for="name" class="col-sm-2 control-label"><?=lang('ad_field_select_photo')?></label>
    	<div class="col-sm-6">
      		<input type="file" name="photos[]" multiple="multiple" id="photo"/>
	    </div>
	</div>
	
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-primary" name="submit_action" value="<?=ACTION_UPLOAD?>">
      	<span class="fa fa-upload"></span>	
		<?=lang('btn_upload')?>
      </button>
    </div>
  </div>
  
  <hr>
 	
  <?php if(empty($ad['photos'])):?>
  	<p class="text-info"><?=lang('no_photo_uploaded')?></p>
  <?php else:?>
 	<p class="text-info"><?=lang('assign_photo_to_page')?>:</p>
 	<div class="row">
 	<?php foreach ($ad['photos'] as $photo):?>
 	
 	
	  <div class="col-xs-4">
	    <div class="thumbnail" style="position:relative;">
	    	<a href="<?=site_url('/advertises/delete-photo/'.$ad['id'].'/'.$photo['id'])?>/" 
				onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="delete_photo_btn">				
				<span class="fa fa-times"></span>			
			</a>
			
	      <img src="<?=get_static_resources("/images/advertises/".$photo['name'])?>" alt="<?=$photo['name']?>">
	      <div><center><?=$photo['width'].' * '.$photo['height'].' px'?></center></div>
	      <div class="caption">
	        
	        <div class="form-group">
	        	<div class="col-xs-12">
		    	<select class="form-control" name="status_<?=$photo['id']?>">
	      			<option value="<?=STATUS_ACTIVE?>" <?=set_select('status_'.$photo['id'], STATUS_ACTIVE, STATUS_ACTIVE == $photo['status'])?>><?=lang('active')?></option>
	      			<option value="<?=STATUS_INACTIVE?>" <?=set_select('status_'.$photo['id'], STATUS_INACTIVE, STATUS_INACTIVE == $photo['status'])?>><?=lang('inactive')?></option>
	      		</select>
	      		</div>
			</div>
			
			<div class="form-group">
	        	<div class="col-xs-12">
		    	<select class="form-control" name="version_<?=$photo['id']?>">
	      			<option value="<?=STATUS_INACTIVE?>" <?=set_select('version_'.$photo['id'], STATUS_INACTIVE, STATUS_INACTIVE == $photo['version'])?>><?=lang('ad_photo_desktop')?></option>
	      			<option value="<?=STATUS_ACTIVE?>" <?=set_select('version_'.$photo['id'], STATUS_ACTIVE, STATUS_ACTIVE == $photo['version'])?>><?=lang('ad_photo_mobile')?></option>
	      		</select>
	      		</div>
			</div>
			
			<div class="form-group">
				
			    <div class="col-xs-12">
			    	<p class="text-info"><?=lang('ad_field_display_on')?>:</p>
			      	<?php foreach ($ad_pages as $key=>$value):?>
			      		
			      		<?php if(is_bit_value_contain($ad['display_on'], $key)):?>
			      			
			      			<?php $checked = is_bit_value_contain($photo['display_on'], $key)?>
			      			
					    	<div class="checkbox">
					    		<label>			    		
					  			<input type="checkbox" value="<?=$key?>" <?=set_checkbox($photo['id'].'_display_on_[]',$key, $checked)?> name="<?=$photo['id']?>_display_on[]" > <?=$value?>
					  			</label>
							</div>
					
						<?php endif;?>
					<?php endforeach;?>
					
			    </div>
			 </div>
			 	     
	      </div>
	    </div>
	  </div>
	
	
 	<?php endforeach;?>
 	
 	</div>
 	
 	<div class="form-group">
	    <div class="col-sm-6">
	      <button type="submit" class="btn btn-primary btn-lg" name="submit_action" value="<?=ACTION_SAVE?>">
	      	<span class="fa fa-download"></span>	
			<?=lang('btn_save')?>
	      </button>
	      <a class="btn btn-default mg-left-10 btn-lg" href="<?=site_url('advertises')?>/photo/<?=$ad['id']?>/" role="button"><?=lang('btn_cancel')?></a>
	    </div>
	  </div>
 	 	
  <?php endif;?>
 	
 	

</form>

<?php endif;?>