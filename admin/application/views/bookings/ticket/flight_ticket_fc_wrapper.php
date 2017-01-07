<form method="post" name="tform_frm">
<?php if(!empty($send_message)):?>
	<div class="alert <?=$send_message['type'] == 1 ? 'alert-success':'alert-danger'?> alert-dismissable">
	  <?=$send_message['message']?>
	</div>
<?php endif;?>

<div id="tform_content">	
	<?=$tform_content?>
</div>
<br>
<div class="row" id="function_area">
		
		<div class="col-xs-2" style="padding:0">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="confirmed" value="1" id="confirmed" onclick="pnr_confirmed()">
					<?=lang('tf_ticket_confirmed')?>
				</label>
			</div>
		</div>
		
		
		<div class="col-xs-2">
			<button type="button" class="btn btn-primary btn-lg" onclick="print_ticket_form();">
				<span class="glyphicon glyphicon-print"></span>
				<?=lang('tf_btn_print')?>
			</button>
		</div>
		
		<div class="col-xs-2">
			<button type="submit" class="btn btn-success btn-lg" name="action" value="download">
				<span class="glyphicon glyphicon-save"></span>
				<?=lang('tf_btn_download')?>
			</button>
		</div>
		
		<div class="col-xs-3 col-xs-offset-1">
			<input type="text" readonly="readonly" name="email" id="email" class="form-control" value="<?=$contact['email']?>"> 
			<a target="blank" href="/admin/customers/edit/<?=$contact['id']?>"><?=lang('bo_c_edit')?></a>
		</div>
		
		<div class="col-xs-2">
			<button type="submit" class="btn btn-info btn-lg" name="action" value="email" onclick="return send_ticket_email()">
				<span class="glyphicon glyphicon-envelope"></span>
				<?=lang('tf_btn_email')?>
			</button>
		</div>

</div>

<script>
	function send_ticket_email(){
		var email = $('#email').val();
		return confirm("Give Mr.Khuyen $10 to send ticket to " + email + '?');
	}

	function print_ticket_form(){
		var print_area = $('#tform_content');
		Popup($(print_area).html());
	}

	function pnr_confirmed(){
		if($("#confirmed").is(':checked')){
			$('.pnr-status').text('<?=lang('tf_status_confirm')?>');
        } else {
			//alert('go here');
        	$('.pnr-status').each(function(){

        		var pnr_status = $(this).attr('pnr-status');

        		
            	$(this).text(pnr_status);
				
            });
            
			
        }
	}
	

    function Popup(data) 
    {
        var mywindow = window.open('', 'Ticket Form', 'height=auto,width=auto');
        mywindow.document.write('<html><head><title>Ticket Form</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
	
        mywindow.focus();
        mywindow.print();
        mywindow.close();

        return true;
    }
</script>

</form>