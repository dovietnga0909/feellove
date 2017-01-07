<?php if(empty($user)):?>
	<div class="alert alert-warning">
		<?=lang('item_already_removed')?>
				
		<a class="btn btn-primary mg-left-10" href="<?=site_url('users')?>" role="button">
			<?=lang('btn_go_back')?>
			<span class="fa fa-arrow-right mg-left-10"></span>
		</a>
	</div>
<?php else:?>
	<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
		
		<div class="form-group">
			<label for="full_name" class="col-xs-3 control-label"><?=lang('users_field_full_name')?>:</label>
			<div class="col-xs-4" style="padding-top:7px">
				<?=$user['full_name']?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="hotline_name" class="col-xs-3 control-label"><?=lang('hotline_name')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="hotline_name" name="hotline_name" value="<?=set_value('hotline_name', $user['hotline_name'])?>">
				<?=form_error('hotline_name')?>
			</div>
		</div>
		<div class="form-group">
			<label for="hotline_number" class="col-xs-3 control-label"><?=lang('hotline_number')?>: <?=mark_required()?></label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="hotline_number" name="hotline_number" value="<?=set_value('hotline_number', $user['hotline_number'])?>">
				<?=form_error('hotline_number')?>
			</div>
		</div>
		
		
		<div class="form-group">
			<label for="display_on" class="col-xs-3 control-label"><?=lang('display_on')?>:</label>
			<div class="col-xs-6">
				
				<div class="checkbox-inline">
					<?php 
						$checked = is_bit_value_contain($user['display_on'], HOTEL);
						echo $checked;
					?>
					<label>
						<input type="checkbox" name="display_on[]" value="<?=HOTEL?>" <?=set_checkbox('display_on', HOTEL, $checked)?>><?=lang('hotel')?>
					</label>
					
				</div>
				
				<div class="checkbox-inline">
					<?php 
						$checked = is_bit_value_contain($user['display_on'], FLIGHT);
						echo $checked;
					?>
					<label>
						<input type="checkbox" name="display_on[]" value="<?=FLIGHT?>" <?=set_checkbox('display_on', FLIGHT, $checked)?>><?=lang('flight')?>
					</label>
					
				</div>
				
				<div class="checkbox-inline">
					<?php 
						$checked = is_bit_value_contain($user['display_on'], CRUISE);
						echo $checked;
					?>
					<label>
						<input type="checkbox" name="display_on[]" value="<?=CRUISE?>" <?=set_checkbox('display_on', CRUISE, $checked)?>><?=lang('cruise')?>
					</label>
					
				</div>
				
				<div class="checkbox-inline">
					<?php 
						$checked = is_bit_value_contain($user['display_on'], TOUR);
						echo $checked;
					?>
					<label>
						<input type="checkbox" name="display_on[]" value="<?=TOUR?>" <?=set_checkbox('display_on', TOUR, $checked)?>><?=lang('tour')?>
					</label>
					
				</div>
				
			</div>
		</div>
		
		
		<div class="form-group">
			<label for="yahoo_acc" class="col-xs-3 control-label"><?=lang('yahoo_acc')?>:</label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="yahoo_acc" name="yahoo_acc" value="<?=set_value('yahoo_acc', $user['yahoo_acc'])?>">
				<?=form_error('yahoo_acc')?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="skype_acc" class="col-xs-3 control-label"><?=lang('skype_acc')?>:</label>
			<div class="col-xs-4">
				<input type="text" class="form-control" id="skype_acc" name="skype_acc" value="<?=set_value('skype_acc', $user['skype_acc'])?>">
				<?=form_error('skype_acc')?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="avatar" class="col-xs-3 control-label"><?=lang('users_field_avatar')?>:</label>
			<div class="col-xs-4">
				<input type="file" name="avatar" size="30" />
				<?=$upload_error;?>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-xs-offset-3 col-xs-4">
				<img src="<?=get_static_resources("images/users/".$user['avatar']);?>"/>
			</div>
		</div>
		
		<div class="form-group">
		    <div class="col-xs-offset-3 col-xs-6">
		    	<button type="submit" class="btn btn-primary" name="action" value="<?=ACTION_SAVE?>">
					<span class="fa fa-download"></span>
					<?=lang('btn_save')?>
				</button>
				<a class="btn btn-default mg-left-10" href="<?=site_url('users')?>" role="button"><?=lang('btn_cancel')?></a>
		    </div>
		</div>
	</form>
<?php endif;?>