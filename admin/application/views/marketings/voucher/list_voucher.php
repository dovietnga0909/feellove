<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  	<b><?=lang('list_of_vouchers')?></b>
  	
  	<?php if(!empty($search_criteria)):?>
  		(<?=lang('search_filter_applied')?>)
  	<?php endif;?>
  	
  	<a class="btn btn-primary btn-xs pull-right" href="<?=site_url()?>marketings/create-voucher/" role="button">
  		<span class="fa fa-arrow-circle-right"></span>
  		<?=lang('create_voucher')?>
  	</a>
  </div>
  
	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th><?=lang('voucher_field_code')?></th>
				<th><?=lang('voucher_field_amount')?></th>
				<th><?=lang('voucher_field_customer')?></th>
				<th><?=lang('voucher_field_delivered')?></th>
				<th><?=lang('voucher_field_status')?></th>
				<th><?=lang('voucher_field_used_by')?></th>
				<th><?=lang('field_expired_date')?></th>
				<th><?=lang('field_action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($vouchers)):?>
			
				<tr>
					<td colspan="9" align="center">
						<?php if(empty($search_criteria)):?>
							<div class="alert alert-warning"><?=lang('no_voucher_created')?></div>
						<?php else:?>
							<div class="alert alert-warning"><?=lang('no_voucher_found')?></div>
						<?php endif;?>
					</td>
				</tr>
				
			<?php else:?>
				<?php foreach ($vouchers as $key=>$value):?>
					<tr>
						<td><?=$offset + $key + 1?></td>
						<td><a href="<?=site_url('marketings/edit-voucher/'.$value['id'])?>"><?=$value['code']?></a></td>
						
						<td><?=number_format($value['amount'])?></td>
						
						<td><a target="blank_" href="<?=site_url('customers/edit/'.$value['customer_id'])?>"><?=$value['customer_name']?></a></td>
						
						<td><?=$value['delivered'] == STATUS_ACTIVE? lang('yes') : lang('no')?></td>
						
						<td><?=$voucher_status[$value['status']]?></td>
						
						<td><a target="blank_" href="<?=site_url('customers/edit/'.$value['customer_used_id'])?>"><?=$value['customer_used_name']?></a></td>
						
						<td><?=bpv_format_date($value['expired_date'], DATE_FORMAT)?></td>
								
						<?php $privilege = get_right(DATA_MARKETING, $value['user_created_id'])?>
						
						<td>
							<div class="bs-example">
						    <!-- Button HTML (to Trigger Modal) -->
						    <ul class="list-inline">
							    <li><a href="#" onclick="show_voucher_log(<?=$value['id']?>)" class="glyphicon glyphicon-eye-open" role="button" data-toggle="modal"></a></li>
							    
							
							    <li><a href="<?=site_url('marketings/edit-voucher/'.$value['id'])?>">
									<span class="fa fa-edit"></span>
								</a></li>
								<?php if($privilege == FULL_PRIVILEGE):?>
								<li><a href="<?=site_url('marketings/delete-voucher/'.$value['id'])?>" onclick="return confirm_delete('<?=lang('confirm_delete')?>')" class="mg-left-10">
									<span class="fa fa-times"></span>
								</a></li>
							</ul>
							<?php endif;?>
						</div>
							
							
						</td>
					</tr>
				<?php endforeach;?>
				
			<?php endif;?>
			
		</tbody>
	</table>
</div>

<!-- Modal HTML -->
    <div id="voucher_log_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Lịch sử chỉnh sửa</h4>
                </div>
                <div class="modal-body">
                	
                	<div id="v_loading">
                		<!-- HTML loading hear -->
                		
                		Loading....
                	</div>
                	
                	<div id="v_log_content">
                	
                	</div>
                	
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
						    
<div class="clearfix">
	<?=$paging_info['paging_links']?>
	<p class="paging-txt pull-right">
		<?=$paging_info['paging_text']?>
	</p>
</div>
<script type="text/javascript">

function show_voucher_log(id){

	$('#v_log_content').hide();
	
	$('#v_loading').show();
	
	$("#voucher_log_modal").modal('show');
	
	
	$.ajax({
		url: "<?=site_url('marketings/show-log-voucher')?>/",
		type: "POST",
		data: {							 
			v_id: id
		},
		success:function(value){
			
			$('#v_loading').hide();

			$('#v_log_content').html(value);

			$('#v_log_content').show();
		}
	});

	
}

</script>


