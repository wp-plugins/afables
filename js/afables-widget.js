jQuery(document).ready(function($){
	
	var active = 0; // starts at zero
	var list = $('ul.afables-result');
	
	var filter = '';
	
	list.children('.afables-result li').eq('0').siblings().hide(); // Hide all except first list element

	$('.afables-wrapper-widget input[name=type]').bind('click', function(){
		filter = $('.afables-wrapper-widget input[name=type]:checked').val();
		//list = $('ul.afables-result li.'+filter);
		//list.children('.afables-result li.'+filter).eq('0').siblings().hide();
		
	});
	
	$('.afables-next').bind('click', function(event) {
		event.preventDefault();
		active = active == list.children('.afables-result li').length-1 ? 0 : active + 1;
	});

	$('.afables-previous').bind('click', function(event) {
		event.preventDefault();
		active = active == 0 ? list.children('.afables-result li').length-1 : active - 1;
	});

	var getActivebyFilter = function() {
		filter = $('.afables-wrapper-widget input[name=type]:checked').val();
		return list.children('.afables-result li.'+filter).eq(active);
	};
	
	var getActive = function() {
		return list.children('.afables-result li').eq(active);
	};

	$('.afables-previous,.afables-next,.afables-wrapper-widget input[name=type]').bind('click', function() {
		if (filter == '')	getActive().show().siblings().hide();
		else getActivebyFilter().show().siblings().hide();
		//getActive().fadeIn().siblings().fadeOut();
	});
	
	$(".afables-wrapper-widget a[rel='afable']").click(function () {
      	var caracteristicas = "height=500,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
      		nueva=window.open(this.href, 'Afable Single', caracteristicas);
      	return false;
	});

	$(".afables-wrapper-widget button.view-details").click(function () {
		url = $(this).attr('rel');
      	var caracteristicas = "height=500,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
      		nueva=window.open(url, 'Afable Single', caracteristicas);
      	return false;
	});
	
});