<?php 

//register_deactivation_hook(__FILE__, array('RssRobot', 'deactivate'));

function afables_activate(){
	
	$notices= get_option('afables_admin_notices', array());
	
	// AFABLES_CACHE_FOLDER
	if(!file_exists(AFABLES_CACHE_FOLDER)){
		// creamos el directorio
		if(mkdir(AFABLES_CACHE_FOLDER,0777)){
			$notices[]= "<div class='updated'><p>Directorio cache creado con éxito.</p></div>";
			
		}else{
			$notices[]= "<div class='error'><p>No se pudo crear el directorio <strong>".AFABLES_CACHE_FOLDER."</strong>.</p><p>Necesitara crearlo manualmente para el correcto funcionamiento del plugin.</p></div>";
		}
		
	}
	
	update_option('afables_admin_notices', $notices);
}


add_action ('admin_notices' , 'afables_notices');

function afables_notices(){
	
	if ($notices = get_option('afables_admin_notices')) {
		foreach ($notices as $notice) {
			//echo "<div class='updated'><p>$notice</p></div>";
			echo $notice;
		}
		delete_option('afables_admin_notices');
	}
	
}
?>