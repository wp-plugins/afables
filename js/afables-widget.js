jQuery(document).ready(function($){
	
	var active = 0; // starts at zero
	
	var list = [];
	var filter = '';
	var newfilter = [];
	
	$.each(afablesObject, function( index, value ) {
		list[index] =  $(value+' ul.afables-result');
		list[index].children(value +' .afables-result li'+newfilter[index]).eq('0').siblings().hide(); // Hide all except first list element
		//console.log(list[index]);

		newfilter[index] = '';
		
		source = $(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').data('src');
		$(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').attr('src',source);
		
		$(value+' .filterby input[name=type_'+index+']').bind('click', function(){
			//console.log($(this).val());
			filter='.'+$(this).val();
			//console.log(filter);
			newfilter[index]='.'+$(this).val();

			//console.log(index);
		});
		
	});
	
	
	
	$.each(afablesObject, function( index, value ) {
		list[index] =  $(value+' ul.afables-result');
		list[index].children(value +' .afables-result li'+newfilter[index]).eq('0').siblings().hide(); // Hide all except first list element
	});
	
	$.each(list, function( index, value ) {
		console.log(index);
	});	
	

	$.each(afablesObject, function( index, value ) {
		
		
		$(value+' .afables-wrapper-widget input[name=type_'+index+']').bind('click', function(){
			filter[index] = $(value+' .afables-wrapper-widget input[name=type_'+index+']:checked').val();
			list[index].children(value+' .afables-result li'+newfilter[index]).eq('0').siblings().hide();
			active = 0;
		});
		
		$(value+' .afables-next').bind('click', function(event) {
			event.preventDefault();
			active = active == list[index].children(value+' .afables-result li'+newfilter[index]).length-1 ? 0 : active + 1;
			source = $(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').data('src');
			$(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').attr('src',source);
		});

		$(value+' .afables-previous').bind('click', function(event) {
			event.preventDefault();
			active = active == 0 ? list[index].children(value+' .afables-result li'+filter[index]).length-1 : active - 1;
			source = $(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').data('src');
			$(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').attr('src',source);
		});
		

		
		$(value+' .afables-previous,'+ value +' .afables-next,'+ value +' .afables-wrapper-widget input[name=type_'+index+']').bind('click', function() {
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
		source = $(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').data('src');
		$(value+' .afables-result li'+newfilter[index]).eq(active).find('.thumbnail > img').attr('src',source);
		//console.log(source);
		return list[index].children(value+' .afables-result li'+newfilter[index]).eq(active);
	};
	
	


	
});