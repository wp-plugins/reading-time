<?php
/*
Plugin Name: Reading Time
Plugin URI: http://www.whiletrue.it
Description: Shows the suggested reading time of web content. To use it inside a post, create a custom field named "readingtime" and give it the number of seconds suggested (e.g. 150).
Author: WhileTrue
Version: 1.0.0
Author URI: http://www.whiletrue.it
*/

/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

add_filter('the_content', 'reading_time');

add_action('admin_menu', 'reading_time_menu');

function reading_time_menu() {
	add_options_page('Reading Time Options', 'Reading Time', 'manage_options', 'my-unique-identifier', 'reading_time_options');
}



function reading_time ($content) {
		// WORKS ONLY ON SINGLE POST
		if (!is_single()) {
			return $content;
		}
		$reading_time_options = explode('|||',get_option('reading_time'));

		$tempo = get_post_custom_values('readingtime');
		if (!is_numeric($tempo[0])) {
			$tempo[0] = round(str_word_count(strip_tags($content))*60/$reading_time_options[1]);
			if (!is_numeric($tempo[0])) {
				return $content;
			}
		}
		$default_reading_time = $tempo[0];

		$barcolor = $reading_time_options[2];
		if ($barcolor=='') $barcolor = 'red';
		
		$text = $reading_time_options[0];
		if ($text=='') $text = 'I think you will spend SSSS seconds reading this post';
		$text = str_replace('SSSS', $default_reading_time, $text);

		wp_enqueue_script('jquery');
		$post_id = get_the_ID();
		$out = '
			<style>
				.readingtime_border { border:1px solid black;width:250px;height:10px; }
				.readingtime_bar { background-color:'.$barcolor.';width:0px;height:10px; }
			</style>
			
			<p>'.$text.'</p>
			<p>
				<div class="readingtime_border">
					<div id="readingtime_bar_in_'.$post_id.'" class="readingtime_bar"></div>
				</div>
			</p>
		
			<script type="text/javascript">
			jQuery("#readingtime_bar_in_'.$post_id.'").animate({
			    width: "100%"
			}, '.$default_reading_time.'*1000);
			</script>
			';
		
	return $out.$content;
}


function reading_time_options () {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'reading_time_default_time';
    $hidden_field_name = 'reading_time_submit_hidden';
    $data_field_name = 'reading_time_default_time';

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST['reading_time_text']) or isset($_POST['reading_time_speed']) or isset($_POST['reading_time_bar_color'])) {

        update_option( 'reading_time', 
			$_POST['reading_time_text'].'|||'.$_POST['reading_time_speed'].'|||'.$_POST['reading_time_bar_color'] );

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

    // settings form
	$reading_time_options = explode('|||',get_option('reading_time'));
    ?>
	
<div class="wrap">
	<h2><?php echo __( 'Reading Time', 'menu-test' ); ?></h2>
	<form name="form1" method="post" action="">

	<table>
	<tr><td valign="top"><?php _e("Text:", 'menu-test' ); ?></td>
	<td><input type="text" name="reading_time_text" value="<?php echo $reading_time_options[0]; ?>" size="100"><br />
	<?php _e("Use 'SSSS' as a placeholder for seconds, e.g. 'The estimated reading time for this post is SSSS seconds'", 'menu-test' ); ?>
	<br /></td></tr>

	<tr><td valign="top"><?php _e("Speed:", 'menu-test' ); ?></td>
	<td><input type="text" name="reading_time_speed" value="<?php echo $reading_time_options[1]; ?>" size="20"><br />
	<?php _e("E.g. 250 for fast readers, 150 for slow readers", 'menu-test' ); ?>
	<br /></td></tr>

	<tr><td valign="top"><?php _e("Bar color:", 'menu-test' ); ?></td>
	<td><input type="text" name="reading_time_bar_color" value="<?php echo $reading_time_options[2]; ?>" size="20"><br/>
	<?php _e("E.g. 'blue', '#006699'", 'menu-test' ); ?>
	<br /></td></tr>

	</table>
	<hr />
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>

	</form>
</div>

<?php
 
}
