jQuery(document).ready(function($){
	
	var active = 0; // starts at zero
	
	var list = [];
	var filter = '';
	
	$.each(afablesObject, function( index, value ) {
		list[index] =  $(value+' ul.afables-result');
		list[index].children(value +' .afables-result li'+filter).eq('0').siblings().hide(); // Hide all except first list element
	});
	
	$.each(list, function( index, value ) {
		console.log(index);
	});	
	

	$.each(afablesObject, function( index, value ) {
		
		$(value+' .afables-wrapper-widget input[name=type]').bind('click', function(){
			filter[index] = $(value+' .afables-wrapper-widget input[name=type]:checked').val();
			list[index].children(value+' .afables-result li'+filter[index]).eq('0').siblings().hide();
			active = 0;
		});
		
		$(value+' .afables-next').bind('click', function(event) {
			event.preventDefault();
			active = active == list[index].children(value+' .afables-result li'+filter[index]).length-1 ? 0 : active + 1;
		});

		$(value+' .afables-previous').bind('click', function(event) {
			event.preventDefault();
			active = active == 0 ? list[index].children(value+' .afables-result li'+filter[index]).length-1 : active - 1;
		});
		

		
		$(value+' .afables-previous,'+ value +' .afables-next,'+ value +' .afables-wrapper-widget input[name=type]').bind('click', function() {
			getActive(index,value).show().siblings().hide();
		});
		
		$(value +" .afables-wrapper-widget a[rel='afable']").click(function () {
	      	var caracteristicas = "titlebar=no,toolbar=no,location=no,status=no,menubar=no,height=500,width=1000,scrollTo,resizable=1,scrollbars=1,location=0";
	      		nueva=window.open(this.href, 'Afable Single', caracteristicas);
	      	return false;
		});

		$(value +" .afables-wrapper-widget button.view-details").click(function () {
			url = $(this).attr('rel');
	      	var caracteristicas = "titlebar=no,toolbar=no,location=no,status=no,menubar=no,height=500,width=1000,scrollTo,resizable=1,scrollbars=1,location=0";
	      		nueva=window.open(url, 'Afable Single', caracteristicas);
	      	return false;
		});
		
	});
	
	
	var getActive = function(index,value) {
		return list[index].children(value+' .afables-result li'+filter).eq(active);
	};
	
	


	
});