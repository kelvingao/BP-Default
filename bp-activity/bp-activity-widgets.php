<?php

/* Register widgets for blogs component */
function bp_activity_register_widgets() {
	global $current_blog;
	
	/* Only allow these widgets on the main site blog */
	if ( (int)$current_blog->blog_id == 1 ) {

		/* Site Wide Activity Widget */
		register_sidebar_widget( __('Site Wide Activity', 'buddypress'), 'bp_activity_widget_sitewide_activity');
		register_widget_control( __('Site Wide Activity', 'buddypress'), 'bp_activity_widget_sitewide_activity_control' );
		
	}
}
add_action( 'plugins_loaded', 'bp_activity_register_widgets' );


function bp_activity_widget_sitewide_activity($args) {
	global $current_blog;
	
    extract($args);
	$options = get_blog_option( $current_blog->blog_id, 'bp_activity_widget_sitewide_activity' );
?>
	<?php echo $before_widget; ?>
	<?php echo $before_title
		. $widget_name 
		. $after_title; ?>

	<?php if ( (int)$current_blog->blog_id == 1 ) : ?>
		<?php $activity = BP_Activity_Activity::get_sitewide_activity( $options['max_items'] ) ?>
		
		<?php if ( $activity ) : ?>
			<ul id="site-wide-stream" class="activity-list">
			<?php foreach( $activity as $item ) : ?>
				<li class="<?php echo $item['component_name'] ?>">
					<?php echo bp_activity_content_filter( $item['content'], $item['date_recorded'], false );?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<div class="widget-error">
				<?php _e('There has been no recent site activity.', 'buddypress') ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php echo $after_widget; ?>
<?php
}

function bp_activity_widget_sitewide_activity_control() {
	global $current_blog;
	
	$options = $newoptions = get_blog_option( $current_blog->blog_id, 'bp_activity_widget_sitewide_activity');

	if ( $_POST['bp-activity-widget-sitewide-submit'] ) {
		$newoptions['max_items'] = strip_tags( stripslashes( $_POST['bp-activity-widget-sitewide-items-max'] ) );
	}
	
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_blog_option( $current_blog->blog_id, 'bp_activity_widget_sitewide_activity', $options );
	}

	$max_items = attribute_escape( $options['max_items'] );
?>
		<p><label for="bp-activity-widget-sitewide-items-max"><?php _e('Max Number of Items:', 'buddypress'); ?> <input class="widefat" id="bp-activity-widget-sitewide-items-max" name="bp-activity-widget-sitewide-items-max" type="text" value="<?php echo $max_items; ?>" style="width: 30%" /></label></p>
		<input type="hidden" id="bp-activity-widget-sitewide-submit" name="bp-activity-widget-sitewide-submit" value="1" />
<?php
}

?>