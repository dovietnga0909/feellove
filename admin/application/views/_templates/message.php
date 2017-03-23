<div class="alert alert-success alert-dismissable bpt-message" id="bpt_message">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <?=$message?>
</div>
<script>
	//self remove after 3 seconds 
	setTimeout(function(){
		$("#bpt_message").remove(); 
	}, 5000);
</script>