<?php

get_header(); ?>
<?php

if(have_posts()) { while(have_posts()) { the_post() ?>
<div id="ccr-events">
	<div class="ccr-event-content">
		<div class="ccr-event-date">
			<span class="ccr-day"><?php echo date( 'd', strtotime( get_the_date() ) ); ?></span>
			<span class="ccr-month-year"><?php echo date( 'F j', strtotime( get_the_date() ) ); ?></span>
		</div>
		<div class="ccr-content">
			<?php the_post_thumbnail(); ?>
			<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>					
			<?php the_content(); ?>
			<p><strong>Date: </strong> <?php echo get_post_meta( $post->ID, 'ccr_event_date', true ); ?></p>
			<p><strong>Gate Open: </strong> <?php echo get_post_meta( $post->ID, 'ccr_event_gate', true ); ?></p>
			<p><strong>Location: </strong> <?php echo get_post_meta( $post->ID, 'ccr_event_location', true );	?></p>
			<p><a href="<?php echo get_post_meta( $post->ID, 'ccr_event_register_link', true ); ?>" class="ccr-register-link">Register Now!</a></p>
		</div>
	</div>
</div>
<?php } } 
?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>