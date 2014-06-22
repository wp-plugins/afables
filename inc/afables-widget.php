<?php 

/** Registramos el Widget **/
add_action( 'widgets_init', create_function( '', 'register_widget( "afables_widget" );' ) );

class Afables_Widget extends WP_Widget {

	public function __construct() {
		
		/** Cargamos el idioma **/
		afables_load_language(); 
		
		wp_enqueue_script( 'afables-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=true', '', AFABLES_PLUGIN_VERSION ,false);
		
		parent::__construct(
	 		'afables_widget', // Base ID
			__('Afables Widget','afables'), // Name
			array( 'description' => __( 'Display the Afables Widget.', 'afables' ) ) // Args
		);
	}

	public function widget( $args, $instance ) {

		//wp_enqueue_script('afables-jquery-ui','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array('jquery'),'',true);
		wp_enqueue_style( 'afables-widget-style', plugins_url( '../css/afables-widget.css' , __FILE__ ));
		if(AFABLES_DISPLAY_RATTING == 'font-awesome'):
		wp_enqueue_style( 'font-awesome', plugins_url( '../css/font-awesome.css' , __FILE__ ));
		endif;
		
		wp_enqueue_script('jquery');
		
		//wp_enqueue_script( 'afables-widget', plugins_url( '../js/afables-widget.js' , __FILE__ ), array('jquery'), AFABLES_PLUGIN_VERSION ,true );
		
		$before_texto = '<div class="afables-wrapper-widget">';
		$after_texto = '</div>';
		
		// outputs the content of the widget
		extract( $args );
		
		$title = $instance['title'];
		
		//$url = $instance['url'];
		//$place = $instance['place'];
		//$channel = $instance['channel'];
		$language = $this->afable_get_language();
		
		/** Preparamos la url del FEED **/
		$data =  array(
				'city' => $instance['place'],
				'channel' => ($instance['channel']!= 'none') ? $instance['channel'] : null,
				'type' => ($instance['type']!= 'all') ? $instance['type'] : null,
				'language' => $language
		);
		
		$params = http_build_query($data);
		$url = AFABLES_RSS.'?'.$params;
		$items = $this->load_feed( $url );
		
		
		$total = count($items);

		echo $before_widget;
		if (!empty($title)) :
			?><h3 class="widget-title"><?php echo $title; ?></h3><?php 
		endif; 
		?>
		<div class="afables-wrapper-widget">
			<h4 class="afable-title"><?php _e('Need a sitter home help?','afables');?></h4>
			<hr>
			
			<?php if($total > 0 ): # Si hay resultados ?>
			
				<?php if($instance['type']== 'all'):?>
				<div id="filter-type">
					<label><input type="radio" name="type" value="INDIVIDUAL"> <?php _e('Caregivers','afables');?></label>
					<label><input type="radio" name="type" value="COMPANY"> <?php _e('Home help companies','afables')?></label>
				</div>
				<?php endif;?>
			
				<p><?php echo sprintf(__('<strong>%d</strong> caregivers rated and recommended by other families in <strong>%s</strong>','afables'),$total, $instance['place'] ); ?></p>
			
				<?php if( $total > 1 ): # Si hay mas de uno mostramos el navegador ?>
					<p stly="text-align: center;"><a href="#" class="afables-next"><?php _e('Previous','afables');?></a> | <a href="#" class="afables-previous"><?php _e('Next','afables');?></a></p>
				<?php endif;?>
			
				<?php
				# mostramos los resultados  
				$this->display_items($items,$instance['hide_thumbnail'],$language);  
				?>
				
				<?php if (WP_DEBUG == true): ?>
					<a href="<?php echo $url; ?>" target="_blank">RSS</a> 
				<?php endif; ?>

			<?php else: #no hay resultados 
				$this->no_results();
			endif;?>
			<hr>
			
			<footer class="afables-footer">
			<?php $afables_home_help = '<a href="http://www.afables.com/" target="_blank" title="'.esc_attr(__('home help','afables')).'">'.__('home help','afables').'</a>';?>
				<?php echo sprintf(__('<strong>Social Recommender</strong> %s powered by Afables.','afables'), $afables_home_help); ?>
			</footer>

		
		</div><!-- .afables-wrapper-widget -->
		
		
		<?php include(plugin_dir_path( __FILE__ ).'../js/afables-widget-js.php'); ?>
		
		
		<?php 
		echo $after_widget;

	}

	public function form( $instance ) {
		
		// outputs the options form on admin
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else{
			$title = __( 'New title', 'afables' );
		}
		
		if ( isset( $instance[ 'city' ] ) ) {
			$city = $instance[ 'city' ];
		}else{
			$city = '';
		}
		
		if ( isset( $instance[ 'language' ] ) ) {
			$language = $instance[ 'language' ];
		}else{
			$language = 'es';
		}
		
		if ( isset( $instance[ 'type' ] ) ) {
			$type = $instance[ 'type' ];
		}else{
			$type = '';
		}

		if ( isset( $instance[ 'place' ] ) ) {
			$place = $instance[ 'place' ];
		}else{
			$place = '';
		}
		
		if ( isset( $instance[ 'hide_thumbnail' ] ) ) {
			$hide_thumbnail = $instance[ 'hide_thumbnail' ];
		}else{
			//$hide_thumbnail = 'show';
			$hide_thumbnail = false;
		}
		
		if ( isset( $instance[ 'channel' ] ) ) {
			$channel = $instance[ 'channel' ];
		}else{

		}
		
		if ( isset( $instance[ 'url' ] ) ) {
			$url = $instance[ 'url' ];
		}else{

		}
		
		
		if (WP_DEBUG == true) :
		
		endif;

		//<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=true"></script>
		//wp_enqueue_script('afables-maps');
		
		$selected = 'selected="selected"';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','afables' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'city' ); ?>"><?php _e( 'City:','afables' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" type="text" value="<?php echo esc_attr( $city ); ?>" />
			<input class="widefat" id="<?php echo $this->get_field_id( 'place' ); ?>" name="<?php echo $this->get_field_name( 'place' ); ?>" type="hidden" readonly value="<?php echo esc_attr( $place ); ?>" />
			<?php $this->load_google_map_autocomplete();?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Customer Type:','afables' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
				<option value="all" <?php echo ($type == 'all') ? $selected : null ?>><?php _e('All', 'afables');?></option>
				<option value="COMPANY" <?php echo ($type == 'COMPANY') ? $selected : null ?>><?php _e('Caregivers', 'afables'); ?></option>
				<option value="INDIVIDUAL" <?php echo ($type == 'INDIVIDUAL') ? $selected : null ?>><?php _e('Home help companies', 'afables');?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'channel' ); ?>"><?php _e( 'Channel:','afables' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'channel' ); ?>" name="<?php echo $this->get_field_name( 'channel' ); ?>">
				<?php $channels = $this->load_feed(AFABLES_RSS_CHANNEL);?>
				<option value="none" <?php echo ($channel == 'none') ? $selected : null ?>><?php _e('All', 'afables');?></option>
				
				<?php foreach ($channels as $key => $item): ?> 
				<option value="<?php echo $item->get_id();?>" <?php echo ($channel == $item->get_id()) ? $selected : null ?>><?php echo $item->get_title();?></option>
				<?php endforeach;?>
			</select>
		</p>
		
		<p><label><?php _e('Settings:','afables');?></p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'hide_thumbnail' ); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'hide_thumbnail' ); ?>" value="true" <?php echo ($hide_thumbnail == true) ? 'checked="checked"' : null ?>> 
				<?php _e( 'Hide thumbnails','afables' ); ?>
			</label> 
		</p>
		
		<?php 
		
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;		

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['city'] = strip_tags( $new_instance['city'] );
		$instance['place'] = strip_tags( $new_instance['place'] );
		$instance['channel'] = strip_tags( $new_instance['channel'] );		
		$instance['type'] = strip_tags( $new_instance['type'] );
		$instance['language'] = strip_tags( $new_instance['language'] );
		
		if(isset($new_instance['hide_thumbnail']))
			$instance['hide_thumbnail'] = strip_tags( $new_instance['hide_thumbnail'] );
		else 
			$instance['hide_thumbnail'] = false;

		/*
		$data =  array(
			'city' => $instance['place'],
			'channel' => ($instance['channel']!= 'none') ? $instance['channel'] : null,
			'type' => ($instance['type']!= 'all') ? $instance['type'] : null,
			'language' => $this->afable_get_language() 
			//'type' => 'INDIVIDUAL'
			);

		$params = http_build_query($data);
		$instance['url'] = AFABLES_RSS.'?'.$params;*/
		//$instance['url'] = AFABLES_RSS;
		//$instance['url'] =  'http://afablesyii.dev.ohayoweb.com/rss/rss?city=madrid&channel=facebook';
		
		if (WP_DEBUG == true) :
			# $instance['debug_entitat_id'] = strip_tags($new_instance['debug_entitat_id']);
			#update_option( 'entitat_id', $new_instance['debug_entitat_id'] );
		endif;
		
		return $instance;
	}


	public function load_feed($url){

		//include_once plugin_dir_path( __FILE__ ).'load_SimplePie.php';
		require ABSPATH . WPINC . '/class-simplepie.php';
		
		$channel_feed = new SimplePie();
		
		$channel_feed->cache_location = AFABLES_CACHE_FOLDER;
		$channel_feed->cache_duration = AFABLES_CACHE_DURATION;
		$channel_feed->set_useragent(AFABLES_USERAGENT);
		$channel_feed->set_feed_url($url);
		
		if(WP_DEBUG == true) $channel_feed->enable_cache = false; // Desactivamos la cache en debug
		
		$channel_feed->init();
		
		$channel_feed->handle_content_type();
		//$channel_feed->error();
		
		return $channel_feed->get_items();
		
	}
	
	public function display_items($items,$hide_thumbnail = true,$language = 'es'){

		#echo '<div class="scroll-container"><div class="scroll-content">';
		echo '<ul class="afables-result">';
		foreach ($items as $key => $item){
			$titulo = html_entity_decode($item->get_title(), ENT_QUOTES, 'UTF-8');
			$content = json_decode($item->get_description());

			$image = $item->get_item_tags('','image');
			
			#echo '<li class="'.strtolower($content->type).'">';
			echo '<li class="'.$content->type.'">';
			echo '<h5>'.$titulo.'</h5>';
			
			if(!$hide_thumbnail) echo '<div class="thumbnail"><img src="'.$image[0]['data'].'"></div>';
			
			echo '<div class="rate">';
			$this->display_ratting($content->rate); 
			
			echo '<span class="count"> ('.$content->count.')</span>';
			
			echo '</div>';
			echo '<p>'.$content->subtitle.'</p>';
			
			echo '<p>'.sprintf(__('Between <span class="price">%d</span> € and <span class="price">%d</span> €/hour','afables'),$content->minPrice, $content->maxPrice ).'</p>';
			
			#echo '<p>Entre <span class="price">'.$content->minPrice.'</span> € y <span class="price">'.$content->maxPrice.'</span> €/hora</p>';
			echo '<p>';
			$this->display_specialities($content->specialities);
			echo '</p>';
			
			//echo '<a href="'.$item->get_permalink().'" class="view-details" rel="afable" target="_blank">'.__('View details','afables').'</a>';
			echo '<p><button rel="'.$this->get_single_permalink($item->get_permalink()).'" class="view-details">'.__('View details','afables').'</button></p>';
			
			if(WP_DEBUG == true):
			echo '<!--<pre>'.print_r($content,1).'</pre>-->';
			endif;
			
			echo '</li>';
			
		}
		echo '</ul>';
		#echo '</div><div class="scroll-slider"></div></div>';
		
	}
	
	public function get_single_permalink($permalink){

		/** Preparamos la url de la ficha **/
		$blogname = get_bloginfo('name');
		if(empty($blogname)) $blogname = site_url();

		$data =  array(
				'utm_source' => $blogname,
				'utm_medium' => AFABLES_UTM_MEDIUM,
				'utm_campaign' => AFABLES_UTM_CAMPAING,
				'utm_content' => AFABLES_PLUGIN_VERSION,
		);
		
		$params = http_build_query($data);
		$url = $permalink.'?'.$params;
		//$params = '?utm_source=nombredelblog&utm_medium=widget&utm_campaign=widgetWordpress&utm_content=v1';

		return $url;

	}
	
	
	public function display_specialities($specialities){
		$array = get_object_vars($specialities);
		$properties = array_keys($array);
		
		echo implode($properties,' - ');
		
	}
	
	public function no_results(){
		//
		?>
		<div id="no-results">
			<p><i class="fa fa-exclamation-circle"></i> <strong><?php _e('No results!', 'afables');?></strong></p>
			<p><?php _e('We currently do not have carers published as matching your needs in this area.','afables');?></p>	
			<p><?php _e('New caregivers as they join us every day, call us or email us and we will help you in your quest for free (answer in less than 24 hours).','afables'); ?></p>
		</div>
		<?php 
	}
	
	public function display_ratting($value){

		/** fontawesome **/
		if(AFABLES_DISPLAY_RATTING == 'font-awesome'):
		
		$full = '<i class="fa fa-heart"></i>';
		$empty = '<i class="fa fa-heart-o"></i>';
		else:

		/** png **/
		$full = '<img src="'.plugins_url( '../img/heart-full.png' , __FILE__ ).'" height="21" width="21">';
		$empty = '<img src="'.plugins_url( '../img/heart-empty.png' , __FILE__ ).'" height="21" width="21">';

		endif;
		
		for ($i = 1; $i <= AFABLES_MAX_RATTING; $i++) {
			if($i<=$value) echo $full;
			else echo $empty;
		
		}

	}
	
	public function load_google_map_autocomplete(){
		
		?>
			
			<script>
		
			var input = document.getElementById("<?php echo $this->get_field_id( 'city' ); ?>");
			var options = {
			  types: ["geocode"],
			  componentRestrictions: {country: 'es'}
			};
		
			autocomplete = new google.maps.places.Autocomplete(input, options);
			google.maps.event.addListener(autocomplete, "place_changed", function() {
			  var place = autocomplete.getPlace();
			  
			  jQuery("#<?php echo $this->get_field_id( 'place' ); ?>").val(place.name);
			});
		
			</script> 
		<?php 

	}
	
	public function afable_get_language(){
		
		if(defined('ICL_LANGUAGE_CODE')){
			return ICL_LANGUAGE_CODE;
		}
		
		global $AFABLES_SUPPORTED_LANGUAGES;

		$language = substr(get_locale(), 0,2);
		
		if(in_array($language,$AFABLES_SUPPORTED_LANGUAGES)){
			return $language;
		}else{
			return 'es';
		}
		
	}
	
	
	public function slugify($text){

		if (empty($text)) return "aqui0";
		$characters = array(
				"Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u",
				"á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
				"à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"
		);
	
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		if (empty($text)) return "aqui";
	
		// trim
		$text = trim($text, '-');
		if (empty($text)) return "aqui1";
		// transliterate
		if (function_exists('iconv'))
		{
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		}
	
		if (empty($text)) return "aqui2";
	
		// lowercase
		$text = strtolower($text);
	
		//replace characters with accents
		$string = strtr($string, $characters);
	
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
	
	
		if (empty($text))
		{
			return 'n-a';
		}
	
		return $text;
	}
	
}


?>