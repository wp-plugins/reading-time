<?php
/*
Plugin Name: Reading Time
Plugin URI: http://www.whiletrue.it
Description: Shows the suggested reading time of web content. To use it inside a post, create a custom field named "readingtime" and give it the number of seconds suggested (e.g. 150).
Author: WhileTrue
Version: 1.2.1
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
	add_options_page('Reading Time Options', 'Reading Time', 'manage_options', 'reading_time_options', 'reading_time_options');
}



function reading_time ($content) {
		// WORKS ONLY ON SINGLE POST
		if (!is_single()) {
			return $content;
		}
		$reading_time_options = explode('|||',get_option('reading_time'));

		// FIRST TRY TO GET ESTIMATED TIME FROM CUSTOM VALUES		
		$tempo = get_post_custom_values('readingtime');
		if (!is_numeric($tempo[0])) {
			// CALCULATE ESTIMATED TIME		
			$tempo[0] = round(str_word_count(strip_tags($content))*60/$reading_time_options[1]);
			if (!is_numeric($tempo[0])) {
				return $content;
			}
		}
		$default_reading_time = $tempo[0];

		// COMPOSE TEXT MESSAGE
		$text = $reading_time_options[0];
		if ($text=='') $text = 'I think you will spend SSSS seconds reading this post';
		$text = str_replace('SSSS', $default_reading_time, $text);
		
		$out = '<p>'.stripslashes($text).'</p>';
		
		// CHECK AGAINST 'NO' FOR BACKWARD COMPATIBILITY
		if ($reading_time_options[4]!='no') {	
			// DISPLAY PROGRESS BAR

			wp_enqueue_script('jquery');
			$post_id = get_the_ID();
	
			$barcolor = $reading_time_options[2];
			if ($barcolor=='') $barcolor = 'red';

			$out .= '
				<style>
					.readingtime_border { border:1px solid black;width:250px;height:10px; }
					.readingtime_bar { background-color:'.$barcolor.';width:0px;height:10px; }
				</style>
				
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
		}
		
	if ($reading_time_options[3]=='below') {
		return $content.$out;
	} else {
		return $out.$content;
	}
}


function reading_time_options () {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // See if the user has posted us some information
    if( isset($_POST['reading_time_text']) or isset($_POST['reading_time_speed']) 
	or isset($_POST['reading_time_bar_color']) or isset($_POST['reading_time_position'])) {

        update_option( 'reading_time', 
			esc_html($_POST['reading_time_text'].'|||'.$_POST['reading_time_speed'].'|||'
			.$_POST['reading_time_bar_color'].'|||'.$_POST['reading_time_position'].'|||'.$_POST['reading_time_bar_display']));

		// Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

	}
	
	//GET STORED VALUES
	$option_string = get_option('reading_time');

	if ($option_string===false) {
		//OPTION NOT IN DATABASE, SO WE INSERT DEFAULT VALUES
		add_option('reading_time', 
			'The estimated reading time for this post is SSSS seconds'.'|||'.'200'.'|||'
			.'blue'.'|||'.'above'.'|||'.'yes');
		$option_string = get_option('reading_time');
	}

	$reading_time_options = explode('|||',$option_string);

	$sel_above = ($reading_time_options[3]=='above') ? 'selected="selected"' : '';
	$sel_below = ($reading_time_options[3]=='below') ? 'selected="selected"' : '';

	$sel_bar_yes = ($reading_time_options[4]=='yes') ? 'selected="selected"' : '';
	$sel_bar_no  = ($reading_time_options[4]=='no')  ? 'selected="selected"' : '';

    // SETTINGS FORM
    ?>
	
<div class="wrap">
	<h2><?php echo __( 'Reading Time', 'menu-test' ); ?></h2>
	<form name="form1" method="post" action="">

	<table>
	<tr><td valign="top"><?php _e("Text", 'menu-test' ); ?>:</td>
	<td><input type="text" name="reading_time_text" value="<?php echo stripslashes($reading_time_options[0]); ?>" size="100"><br />
	<span class="description"><?php _e("Use 'SSSS' as a placeholder for seconds, e.g. 'The estimated reading time for this post is SSSS seconds'", 'menu-test' ); ?></span><br />
	<br /></td></tr>

	<tr><td valign="top"><?php _e("Speed", 'menu-test' ); ?>:</td>
	<td><input type="text" name="reading_time_speed" value="<?php echo absint($reading_time_options[1]); ?>" size="20"><br />
	<span class="description"><?php _e("E.g. 250 for fast readers, 150 for slow readers", 'menu-test' ); ?></span><br />
	<br /></td></tr>

	<tr><td valign="top"><?php _e("Progress bar color", 'menu-test' ); ?>:</td>
	<td><input type="text" name="reading_time_bar_color" value="<?php echo $reading_time_options[2]; ?>" size="20"><br />
	<span class="description"><?php _e("E.g. 'blue', '#006699'", 'menu-test' ); ?></span><br />
	<br /></td></tr>

	<tr><td valign="top"><?php _e("Position", 'menu-test' ); ?>:</td>
	<td><select name="reading_time_position">
		<option value="above" <?php echo $sel_above; ?> > <?php _e('above the post', 'menu-test' ); ?></option>
		<option value="below" <?php echo $sel_below; ?> > <?php _e('below the post', 'menu-test' ); ?></option>
		</select><br /> 
	<br /></td></tr>

	<tr><td valign="top"><?php _e("Progress bar display", 'menu-test' ); ?>:</td>
	<td><select name="reading_time_bar_display">
		<option value="yes" <?php echo $sel_bar_yes; ?> > <?php _e('yes', 'menu-test' ); ?></option>
		<option value="no"  <?php echo $sel_bar_no; ?> > <?php _e('no',  'menu-test' ); ?></option>
		</select><br /> 
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
