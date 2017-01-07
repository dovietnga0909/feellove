<?php
if (isset($hotel))
{
    $cat = 'hotels';
    $obj_id = $hotel['id'];
}
elseif (isset($cruise))
{
    $cat = 'cruises';
    $obj_id = $cruise['id'];
}
elseif (isset($tour))
{
    $cat = 'tours';
    $obj_id = $tour['id'];
}
?>

<ul class="nav nav-tabs mg-bottom-20">
	<li class="active"><a href="<?=site_url('/'.$cat.'/contracts/'.$obj_id)?>"><?=lang('contract_mnu_contract_list')?></a></li>
	<li><a href="<?=site_url('/'.$cat.'/contract_upload/'.$obj_id)?>"><?=lang('contract_mnu_contract_upload')?></a></li>
</ul>

<table class="table">
	<thead>
		<tr>
			<th class="text-center" width="5%">#</th>
			<th nowrap="nowrap" width="40%"><?=lang('contract_field_name')?></th>
			<th class="text-center"><?=lang('contract_field_upload_date')?></th>
			<th class="text-center"><?=lang('contract_field_author')?></th>
			<th><?=lang('contract_field_size')?></th>
			<th class="text-center" width="10%"><?=lang('field_action')?></th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($files)):?>
			<?php foreach ($files as $k => $file):?>
			<tr>
    			<td class="v-middle col-action" align="center"><?=$k + 1?></td>
    			<td class="v-middle" align="left">
    			     <span id="file_<?=$file['id']?>">
        				    <?php if(is_contract_readable($file['name'])):?>
        					<a data-src="<?=site_url('/'.$cat.'/contract_view/'.$file['id'])?>" 
        					   data-toggle="modal" data-target="#viewModal" title="<?=$file['description']?>"
    					       class="read_file contract_detail" href="javascript:void(0)"><?=$file['name']?></a>
        					<?php else:?>
        					   <span class="contract_detail" title="<?=$file['description']?>"><?=$file['name']?></span>
        					<?php endif;?>
    			     </span>
    		         <input id="file_ed_<?=$file['id']?>" type="text" value="<?=$file['name']?>" class="form-control editor_field hide" data-name="<?=$file['name']?>" data-id="<?=$file['id']?>">
    			     <textarea id="desc_ed_<?=$file['id']?>" rows="3" class="form-control desc_field hide" data-desc="<?=$file['description']?>" data-id="<?=$file['id']?>"><?=$file['description']?></textarea>
    			</td>
    			<td class="text-center"><?=date('j/n/Y h:i A', strtotime($file['date_created']))?></td>
    			<td class="text-center"><?=$file['last_modified_by']?></td>
    			<td><?=get_contract_size($file['size'])?></td>
    			<td class="col-action v-middle" nowrap="nowrap">
    			     <a href="javascript:void(0)" class="rn_file" data-id="<?=$file['id']?>">
    			         <span class="fa fa-edit"></span>
    			     </a>
    			     <a href="javascript:void(0)" class="desc_file" data-id="<?=$file['id']?>">
    			         <span class="fa fa-info-circle"></span>
    			     </a> 
    			     <a href="<?=site_url('/'.$cat.'/contract_download/'.$file['id'])?>">
    			         <span class="fa fa-download"></span>
    			     </a> 
    			     <a href="<?=site_url('/'.$cat.'/contract_delete/'.$file['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')">
    				    <span class="fa fa-times"></span>
    			     </a>
    			</td>
    		</tr>
			<?php endforeach;?>
		<?php else:?>
			<tr>
			<td colspan="8" class="error text-center">
					<?php if(empty($search_criteria)):?>
						<div class="alert alert-warning"><?=lang('no_contract_created')?></div>
					<?php else:?>
						<div class="alert alert-warning"><?=lang('no_contract_found')?></div>
					<?php endif;?>
				</td>
		</tr>
		<?php endif;?>
	</tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog map-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<span class="fa fa-times"></span>
				</button>
				<h4 class="modal-title bpv-color-title" id="viewModalLabel"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 480px"></iframe>
			</div>

			<div class="modal-footer">
				<div class="row">
					<div class="col-xs-12">
						<button type="button" class="btn btn-primary" data-dismiss="modal"><?=lang('btn_close')?></button>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Saving ...</h4>
			</div>
			<div class="modal-body center-block" style="height: 150px">
				<p>Please wait</p>
				<div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0"
						aria-valuemax="100" style="width: 45%">
						<span class="sr-only">45% Complete</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $( '.contract_detail' ).tooltip({
    	placement : 'bottom',
    	template: '<div class="tooltip" role="tooltip"><div class="gb_7"></div><div class="gb_6"></div><div class="tooltip-inner"></div></div>'
    });
	
    $( '.rn_file' ).click(function() {
        
        var id = $(this).attr('data-id');
        
    	$( '#file_'+id).addClass('hide');
    	$( '#file_ed_'+id).removeClass('hide');
    	$( '#file_ed_'+id).focus();
    });

    $( '.desc_file' ).click(function() {
        
        var id = $(this).attr('data-id');
   
    	$( '#desc_ed_'+id).removeClass('hide');
    	$( '#desc_ed_'+id).focus();
    });
    
    $( '.read_file' ).click(function() {
    	$('#viewModalLabel').html($(this).html());

    	var src = $(this).attr('data-src');
 
        $("#viewModal iframe").attr({'src':src,});
    });

    $( '.editor_field' ).blur(function() {

    	var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');

        if($.trim($(this).val()) != name) 
        {
        	$('#loadingModal').modal('show');
        	
        	$.ajax({
        		url: "/admin/contracts/rename/",
        		type: "POST",
        		data: {
        			'id': id,
        			'name': $(this).val(),
        		},
        		success:function(value) {
        			$('#loadingModal').modal('hide');
        			location.reload(true); 
        		},
        		error:function(var1, var2, var3){
        			// do nothing
        			$('#loadingModal').modal('hide');
        		}
        	});
        } else {
        	$( '#file_'+id).removeClass('hide');
        	$( '#file_ed_'+id).addClass('hide');
        }
    });

    $( '.desc_field' ).blur(function() {

    	var id = $(this).attr('data-id');
        var desc = $(this).attr('data-desc');

        if($.trim($(this).val()) != desc) 
        {
        	$('#loadingModal').modal('show');
        	
        	$.ajax({
        		url: "/admin/contracts/update/",
        		type: "POST",
        		data: {
        			'id': id,
        			'desc': $(this).val(),
        		},
        		success:function(value) {
        			$('#loadingModal').modal('hide');
        			location.reload(true); 
        		},
        		error:function(var1, var2, var3){
        			// do nothing
        			$('#loadingModal').modal('hide');
        		}
        	});
        } else {
        	$( '#desc_ed_'+id).addClass('hide');
        }
    });
</script>