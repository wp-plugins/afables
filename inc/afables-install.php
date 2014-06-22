<?php 

//register_deactivation_hook(__FILE__, array('RssRobot', 'deactivate'));

function afables_activate(){
	
	// AFABLES_CACHE_FOLDER
	
	if(!file_exists(AFABLES_CACHE_FOLDER)){
		// creamos el directorio
		if(mkdir(AFABLES_CACHE_FOLDER,0777))
			echo 'Directorio creado';
		else echo 'Error';
		
	}
	
}

?>