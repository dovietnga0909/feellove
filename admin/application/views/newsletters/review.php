<div class="modal-dialog" onload="get_button();">
    <div class="modal-content" style="width:720px">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title" id="myModalLabel"><?=$name;?></h4>
      	</div>
	   	<div class="modal-body">
	       	<?=$content;?>
	    </div>
    	<div class="modal-footer">
    		<div class="customer_type" style="float:left"> Customer Type : 
    		<?php
    			$customer_array		= $this->config->item('customer_type');
    			
	    		foreach($customer_array as $key =>$value){
	    			if(is_bit_value_contain($customer_type, $key)){
	    				echo lang($value).' ';
	    			}
	    		}
    		?>
    		</div>
	    	<button class="hidden" type="button" id="send" class="btn btn-primary" onclick='send_email(<?=$id?>)'><?=lang('btn-send')?></button>
	   		
	    	<button class="hidden" type="button" id="stop" class="btn btn-primary" onclick='stop_email(<?=$id?>)'><?=lang('btn-stop')?></button>
    		
	    	<button class="hidden" type="button" id="resend" class="btn btn-primary" onclick='resend_email(<?=$id?>)'> <?=lang('btn-resend')?></button>
	    	
    		<p class="hidden" id="finish"><?=lang('finish')?></p>
	    	
    	</div>
    </div>
</div>
<script>
	get_button();

	function get_button(){

		var status	= <?=$status?>;
		
		if(status == <?=STATUS_NEW?>){
			$('#send').show();
			$('#send').removeClass('hidden');
		}
		if(status == <?=STATUS_SENDING?>){
			$('#send').hide();
			$('#stop').removeClass('hidden');
		}
		if(status == <?=STATUS_STOP?>){
			$('#stop').hide();
			$('#send').hide();
			$('#resend').removeClass('hidden');
		}
		if(status == <?=STATUS_SENT?>){
			$('#stop').hide();
			$('#send').hide();
			$('#resend').hide();
			$('#finish').removeClass('hidden');
		}
	}
	

	function send_email(id){
		
		var is_send= confirm_delete('<?=lang('confirm_send')?>');
		
		if(is_send){
			$('#send').hide();
			$.ajax({
				url: "send-email",
				type: "POST",
				data: {
					"id": id,
				},
				success:function(value){
					$('#stop').removeClass('hidden');
					$('#stop').show()
				},
				error:function(var1, var2, var3){
					// do nothing
				}
			});
			
			window.setTimeout(function() { window.location.href = '<?=site_url('newsletters/')?>'; }, 1000);
		}
	}

	function resend_email(id){
		
		var is_resend = confirm_delete('<?=lang('confirm_resend')?>');
		if(is_resend){
			$('#resend').hide();
			$('#send').hide();
			$.ajax({
				url: "resend-email",
				type: "POST",
				data: {
					"id": id,
				},
				success:function(value){
					$('#stop').removeClass('hidden');
					$('#stop').show();
				},
				error:function(var1, var2, var3){
					// do nothing
				}
			});

			window.setTimeout(function() { window.location.href = '<?=site_url('newsletters/')?>'; }, 1000);
		}
	}

	function stop_email(id){
		
		var is_stop = confirm_delete('Are you sure you want to stop newsletter !');
		if(is_stop){
			$('#stop').hide();
			$('#resend').hide();
			$('#send').hide();
			$.ajax({
				url: "newsletter-status",
				type: "POST",
				data: {
					"id": id,
				},
				success:function(value){
					if(value){
						$('#resend').removeClass('hidden');
						$('#resend').show();
					}
				},
				error:function(var1, var2, var3){
					// do nothing
				}
			});

			window.setTimeout(function() { window.location.href = '<?=site_url('newsletters/')?>'; }, 1000);
		}
	}
</script>