<?php ?>
<script>
jQuery(document).ready(function($){
	
	var active = 0; // starts at zero
	var list = $('#<?php echo $this->id; ?> ul.afables-result');
	
	var filter = '';

	
	list.children('#<?php echo $this->id; ?> .afables-result li'+filter).eq('0').siblings().hide(); // Hide all except first list element
	
	$('#<?php echo $this->id; ?> .afables-wrapper-widget input[name=type]').bind('click', function(){
		filter = '.'+$('#<?php echo $this->id; ?> .afables-wrapper-widget input[name=type]:checked').val();
		list.children('#<?php echo $this->id; ?> .afables-result li'+filter).eq('0').siblings().hide();
		active = 0;
	});
	
	$('#<?php echo $this->id; ?> .afables-next').bind('click', function(event) {
		event.preventDefault();
		active = active == list.children('#<?php echo $this->id; ?> .afables-result li'+filter).length-1 ? 0 : active + 1;
	});

	$('#<?php echo $this->id; ?> .afables-previous').bind('click', function(event) {
		event.preventDefault();
		active = active == 0 ? list.children('#<?php echo $this->id; ?> .afables-result li'+filter).length-1 : active - 1;

	});

	var getActive = function() {
		return list.children('#<?php echo $this->id; ?> .afables-result li'+filter).eq(active);
	};

	$('#<?php echo $this->id; ?> .afables-previous,#<?php echo $this->id; ?> .afables-next,#<?php echo $this->id; ?> .afables-wrapper-widget input[name=type]').bind('click', function() {
		getActive().show().siblings().hide();
	});
	
	$("#<?php echo $this->id; ?> .afables-wrapper-widget a[rel='afable']").click(function () {
      	var caracteristicas = "titlebar=no,toolbar=no,location=no,status=no,menubar=no,height=500,width=1000,scrollTo,resizable=1,scrollbars=1,location=0";
      		nueva=window.open(this.href, 'Afable Single', caracteristicas);
      	return false;
	});

	$("#<?php echo $this->id; ?> .afables-wrapper-widget button.view-details").click(function () {
		url = $(this).attr('rel');
      	var caracteristicas = "titlebar=no,toolbar=no,location=no,status=no,menubar=no,height=500,width=1000,scrollTo,resizable=1,scrollbars=1,location=0";
      		nueva=window.open(url, 'Afable Single', caracteristicas);
      	return false;
	});
	
});
</script>