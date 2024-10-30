<?php
/*
Plugin Name: CCR Event
Plugin URI: http://codexcoder.com
Description: Upcoming Event Plugin
Author: CodexCoder Team
Author URI: http://codexcoder.com
Version: 1.0.0
*/

// Event Class
class CCR_Upcoming_Event {

	// Load primary
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array($this, 'CCR_Event_Script') );
		add_action( 'init', array($this, 'CCR_Event_Custom_Post_Type') );
		add_action( 'admin_head', array($this,'CCR_Event_Dashboard_icon') );
		add_shortcode( 'ccr-event', array($this,'CCR_event_shortcode') );

		// Use shortcodes in text widgets.
		add_filter('widget_text', 'do_shortcode');

		//meta box
		add_action( 'add_meta_boxes', array( $this, 'CCR_Add_Meta_Box' ) );

		// save event meta box
		add_action( 'save_post', array( $this, 'save' ) );
		
		//include template page
		add_filter( 'template_include', array( $this, 'include_single_event_template' ) );
	}

	// css and js file
	public function CCR_Event_Script() {
		if(!is_admin()) {
			wp_enqueue_style( 'style.css', plugins_url( 'css/style.css', __FILE__ ));
		}

	}

	// custom post type
	public function CCR_Event_Custom_Post_Type(){
		$labels = array(
			'name'                => _x( 'Event', 'codexcoder' ),
			'singular_name'       => _x( 'Event', 'codexcoder' ),
			'menu_name'           => __( 'Events', 'codexcoder' ),
			'parent_item_colon'   => __( 'Parent Events:', 'codexcoder' ),
			'all_items'           => __( 'All Events', 'codexcoder' ),
			'view_item'           => __( 'View Event', 'codexcoder' ),
			'add_new_item'        => __( 'Add New Event', 'codexcoder' ),
			'add_new'             => __( 'New Event', 'codexcoder' ),
			'edit_item'           => __( 'Edit Event', 'codexcoder' ),
			'update_item'         => __( 'Update Event', 'codexcoder' ),
			'search_items'        => __( 'Search Events', 'codexcoder' ),
			'not_found'           => __( 'No Events found', 'codexcoder' ),
			'not_found_in_trash'  => __( 'No Events found in Trash', 'codexcoder' ),
			);
		$args = array(
			'label'               => __( 'event', 'codexcoder' ),
			'description'         => __( 'Codex Coder events Post Type', 'codexcoder' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			);
		register_post_type( 'event', $args );

	}


	// Add event icon in dashboard
	function CCR_Event_Dashboard_icon(){
		?>
		<style>
			/*event Dashboard Icons*/
			#adminmenu .menu-icon-event div.wp-menu-image:before {
				content: "\f145";
			}
		</style>
		<?php
	}


	//short code
	public function CCR_event_shortcode() {
		$ccr_event_query = new WP_Query('post_type=event');
		if($ccr_event_query -> have_posts()) { while($ccr_event_query -> have_posts()) { $ccr_event_query -> the_post() ?>
		<div id="ccr-events">
			<div class="ccr-event-content">
				<div class="ccr-event-date">
					<span class="ccr-day"><?php echo date( 'd', strtotime( get_the_date() ) ); ?></span>
					<span class="ccr-month-year"><?php echo date( 'F j', strtotime( get_the_date() ) ); ?></span>
				</div>
				<div class="ccr-content">
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>					
					<?php the_excerpt(); ?>

				</div>
			</div>
		</div>
		<?php } } 
		wp_reset_postdata();  		
	}

	//Adds the meta box container.
	public function CCR_Add_Meta_Box() {
		add_meta_box('meta_id',__( 'Event Setting ', 'Title'),array( $this, 'render_meta_box_content' ), 'event' ,'advanced','high');

	}
	//meta date
	function render_meta_box_content($post) {
		$value = get_post_meta( $post->ID, 'ccr_event_date', true );
		$location = get_post_meta( $post->ID, 'ccr_event_location', true );
		$gate = get_post_meta( $post->ID, 'ccr_event_gate', true );
		$register = get_post_meta( $post->ID, 'ccr_event_register_link', true );
		?>
		<p>
			<input class="widefat" type="text" name="ccr_event_date" value="<?php echo esc_attr($value); ?>" placeholder="Date" />
		</p>
		<p>
			<input class="widefat" type="text" name="ccr_event_gate" value="<?php echo esc_attr($gate); ?>" placeholder="Gate Open" />
		</p>
		<p>
			<input class="widefat" type="text" name="ccr_event_location" value="<?php echo esc_attr($location); ?>" placeholder="Location" />
		</p>
		<p>
			<input class="widefat" type="text" name="ccr_event_register_link" value="<?php echo esc_attr($register); ?>" placeholder="Registration Link" />
		</p>
		<?php }

		// Save the content
		public function save( $post_id ) {
			if(isset($_POST['ccr_event_date'])){
				update_post_meta($post_id,'ccr_event_date',  strip_tags($_POST['ccr_event_date']));
			}
			if(isset($_POST['ccr_event_location'])){
				update_post_meta($post_id,'ccr_event_location',  strip_tags($_POST['ccr_event_location']));
			}
			if(isset($_POST['ccr_event_gate'])){
				update_post_meta($post_id,'ccr_event_gate',  strip_tags($_POST['ccr_event_gate']));
			}
			if(isset($_POST['ccr_event_register_link'])){
				update_post_meta($post_id,'ccr_event_register_link',  strip_tags($_POST['ccr_event_register_link']));
			}
		}	


		
		// Include single event template
		public	function include_single_event_template( $template_path ) {
			if ( get_post_type() == 'event' ) {
				if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
					if ( $theme_file = locate_template( array ( 'single-event.php' ) ) ) {
						$template_path = $theme_file;
					} else {
						$template_path = plugin_dir_path( __FILE__ ) . '/single-event.php';
					}
				}
			}
			return $template_path;
		}
	}

	$obj = new CCR_Upcoming_Event;



	// Creating the widget 
	class ccr_event_widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'ccr_event_widget', // Base ID of your widget
				'Ccr Event Widget', // Widget name will appear in UI 
				array( 'description' => __( 'Upcoming Event Widget ', 'ccr_event_widget_domain' ), ) 
				);
		}

		// This is where the action happens
		public function widget( $args, $instance ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			$count  = apply_filters( 'widget_count',$instance['count'] );
			// before and after widget arguments are defined by themes
			echo $args['before_widget'];
			if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];

			// This is where you run the code and display the output
			$ccr_event_query = new WP_Query(array( 'post_type' => 'event','posts_per_page'=>$count)); 

			if($ccr_event_query -> have_posts()) { while($ccr_event_query -> have_posts()) { $ccr_event_query -> the_post() ?>

			<div id="ccr-events">
				<div class="ccr-event-content">
					<div class="ccr-event-date">
						<span class="ccr-day"><?php echo date( 'd', strtotime( get_the_date() ) ); ?></span>
						<span class="ccr-month-year"><?php echo date( 'F j', strtotime( get_the_date() ) ); ?></span>
					</div>
					<div class="ccr-content">
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>					
					</div>
				</div>
			</div>
			<div class="clear"></div>

			<?php } } 
			wp_reset_postdata();  	
			echo $args['after_widget'];
		}

		// Widget Backend 
		public function form( $instance ) {
			$title = isset( $instance[ 'title' ] ) ? $instance['title'] : 'Event Title' ;
			$count  = isset($instance[ 'count' ]) ? $instance[ 'count' ] : 3;
		// Widget admin form
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_name( 'count' ); ?>"><?php _e( 'Number of posts:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
			</p>
			<?php 
		}

		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
			return $instance;
		}
	} 		

	// Register and load the widget
	function wpb_load_widget() {
		register_widget( 'ccr_event_widget' );
	}

	add_action( 'widgets_init', 'wpb_load_widget' );