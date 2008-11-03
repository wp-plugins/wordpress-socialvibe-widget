<?php
/*
Plugin Name: SocialVibe
Version: 1
Plugin URI: http://www.seanbluestone.com/
Author: Sean Bluestone
Author URI: http://www.seanbluestone.com
Description: A quick and easy WordPress SocialVibe Plugin with some extra options.

Copyright 2008  Sean Bluestone  (email : thedux0r@gmail.com)

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook(__FILE__, 'socialvibe_init');
add_action("plugins_loaded", "init_socialvibe_widget");
add_action('admin_menu', 'socialvibe_menu');

function socialvibe_shortcodes($atts){
	extract(shortcode_atts(array(
		'flashid' => get_option('socialvibe_flash_id'),
		'userid' => get_option('socialvibe_user_id'),
	), $atts));

	$Width=get_option('socialvibe_width');
	$Height=get_option('socialvibe_width');

	if(empty($Width)){ $Width='316'; }
	if(empty($Height)){ $Height='348'; }

	if(empty($atts['flashid'])){
		$FlashID='488750';
	}else{
		$FlashID=$atts['flashid'];
	}
	if(empty($atts['userid'])){
		$UserID='382172';
	}else{
		$UserID=$atts['userid'];
	}
//<p style="margin-top:0; width:316; text-align:center;"><object width="316" height="348"><param name="movie" value="http://media.socialvibe.com/sv2.swf?sid=488750"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="s=12-488750"/><embed src="http://media.socialvibe.com/sv2.swf?sid=488750" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" flashvars="s=12-488750" width="316" height="348"></embed></object><br><a href="http://www.socialvibe.com/?r=382172&rs=join_sv" target="_blank"><img src="http://media.socialvibe.com/m/badge/join_sv.png" border="0"/></a></p>

	return '<p style="margin-top:0; width:'.$Width.'; text-align:center;"><object width="'.$Width.'" height="'.$Height.'"><param name="movie" value="http://media.socialvibe.com/sv2.swf?sid='.$FlashID.'"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="s=1-'.$FlashID.'"/><embed src="http://media.socialvibe.com/sv2.swf?sid='.$FlashID.'" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" flashvars="s=1-'.$FlashID.'" width='.$Width.'" height="'.$Height.'"></embed></object><a href="http://www.socialvibe.com/?r='.$UserID.'&rs=join_sv" target="_blank"><img src="http://media.socialvibe.com/m/badge/join_sv.png" border="0"/></a>';
}

add_shortcode('socialvibe', 'socialvibe_shortcodes');


function socialvibe_init(){
	add_option('socialvibe_widget_title','SocialVibe');
	add_option('socialvibe_flash_id','488750');
	add_option('socialvibe_user_id','382172');
}

function socialvibe_widget(){
	// This is the SocialVibe widget

	echo '<h2>'.get_option('socialvibe_widget_title').'</h2>';
	$FlashID=get_option('socialvibe_flash_id');
	$UserID=get_option('socialvibe_user_id');

	$Width=get_option('socialvibe_width');
	$Height=get_option('socialvibe_height');

	if(empty($Width)){ $Width=316; }
	if(empty($Height)){ $Height=348; }

	if(empty($FlashID)){
		$NoFlash=get_option('socialvibe_noflash');

		if($NoFlash=='Image1'){
			echo '<a href="http://www.socialvibe.com/?r='.$UserID.'&i1"><img border="0" src="http://media.socialvibe.com/m/invite/join_me.png"/></a></center>';
		}elseif($NoFlash=='Image2'){
			echo '<a href="http://www.socialvibe.com/?r='.$UserID.'&i1"><img border="0" src="http://media.socialvibe.com/m/invite/invite_msg.png"/></a></center>';
		}else{
			return;
		}
	}else{
		echo '<p style="margin-top:0; width:'.$Width.'; text-align:center;"><object width="'.$Width.'" height="'.$Height.'"><param name="movie" value="http://media.socialvibe.com/sv2.swf?sid='.$FlashID.'"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="s=1-'.$FlashID.'"/><embed src="http://media.socialvibe.com/sv2.swf?sid='.$FlashID.'" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" flashvars="s=1-'.$FlashID.'" width='.$Width.'" height="'.$Height.'"></embed></object>';
		if(!empty($UserID)){
			echo '<a href="http://www.socialvibe.com/?r='.$UserID.'&rs=join_sv" target="_blank"><img src="http://media.socialvibe.com/m/badge/join_sv.png" border="0"/></a>';
		}
		echo '</p>';
	}
}

function socialvibe_interpret($Code){
	// Accepts HTML code and extracts ID & ID2

	preg_match('/\?sid=([0-9]+)"/',$Code,$Match);
	preg_match('/\?r=([0-9]+)\&/',$Code,$Matches);

	$ID['Flash']=str_replace('?sid=','',substr($Match[0],0,-1));
	$ID['User']=str_replace('?r=','',substr($Matches[0],0,-1));

	return $ID;
}

function socialvibe_widget_control(){

	// Displays the control panel for the widget which are used in socialvibe_widget() above

	if($_POST['socialvibe_widget_submit']){
		update_option('socialvibe_widget_title', $_POST['socialvibe_widget_title']);
		update_option('socialvibe_widget_display', $_POST['socialvibe_widget_display']);
	}

	echo '<table class="form-table"><tr><td>
	<label for="socialvibe_widget_title">Widget Title: </label>
	</td><td>
	<input type="text" name="socialvibe_widget_title" value="'.get_option('socialvibe_widget_title').'" />
	</td></tr>
	<tr><td>
	<label for="socialvibe_widget_flashid">Flash ID: </label>
	</td><td>
	<input type="text" name="socialvibe_flash_id" value="'.get_option('socialvibe_flash_id').'" />
	</td></tr>
	<tr><td>
	<label for="socialvibe_widget_userid">User ID: </label>
	</td><td>
	<input type="text" name="socialvibe_user_id" value="'.get_option('socialvibe_user_id').'" />
	</td></tr>
	</table>
	<input type="hidden" name="socialvibe_widget_submit" value="1" />';
}

function init_socialvibe_widget(){
	register_sidebar_widget("SocialVibe", "socialvibe_widget");
	register_widget_control('SocialVibe', 'socialvibe_widget_control');
}


function socialvibe_menu(){
	add_submenu_page('themes.php', 'SocialVibe','SocialVibe - Options', 8, 'socialvibe_admin_page', 'socialvibe_admin_page'); 
}

/*
<p style="margin-top:0; width:316; text-align:center;"><object width="316" height="348"><param name="movie" value="http://media.socialvibe.com/sv2.swf?sid=488750"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="s=1-488750"/><embed src="http://media.socialvibe.com/sv2.swf?sid=488750" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" flashvars="s=1-488750" width=316" height="348"></embed></object><br><a href="http://www.socialvibe.com/?r=382172&rs=join_sv" target="_blank"><img src="http://media.socialvibe.com/m/badge/join_sv.png" border="0"/></a></p>
<center><a href="http://www.socialvibe.com/?r=382172&i1"><img width="94" height="109" border="0" src="http://media.socialvibe.com/user_photos/1314891/images_thumb.jpg"/><img border="0" src="http://media.socialvibe.com/m/invite/invite_msg.png"/></a><br><img border="0" src="http://media.socialvibe.com/m/invite/spacer.png"/><br><a href="http://www.socialvibe.com/?r=382172&i1"><b>Sean</b> invites you to SocialVibe.com</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.socialvibe.com/?r=382172&i1"><img border="0" src="http://media.socialvibe.com/m/invite/join_me.png"/></a></center>
<p style="margin-top:0; width:316; text-align:center;"><object width="316" height="348"><param name="movie" value="http://media.socialvibe.com/sv2.swf?sid=488750"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="s=12-488750"/><embed src="http://media.socialvibe.com/sv2.swf?sid=488750" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" flashvars="s=12-488750" width="316" height="348"></embed></object><br><a href="http://www.socialvibe.com/?r=382172&rs=join_sv" target="_blank"><img src="http://media.socialvibe.com/m/badge/join_sv.png" border="0"/></a></p>
<center><a href="http://www.socialvibe.com/?r=382172&i5"><img width="94" height="109" border="0" src="http://media.socialvibe.com/user_photos/1314891/images_thumb.jpg"/><img border="0" src="http://media.socialvibe.com/m/invite/invite_msg_b.png"/></a><br><img border="0" src="http://media.socialvibe.com/m/invite/spacer.png"/><br><a href="http://www.socialvibe.com/?r=382172&i5"><b>Sean</b> invites you to SocialVibe.com</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.socialvibe.com/?r=382172&i5"><img border="0" src="http://media.socialvibe.com/m/invite/join_me.png"/></a></center>

<center><a href="http://www.socialvibe.com/?r=382172&i5"><img width="94" height="109" border="0" src="http://media.socialvibe.com/user_photos/1314891/images_thumb.jpg"/><img border="0" src="http://media.socialvibe.com/m/invite/invite_msg_b.png"/></a><br><img border="0" src="http://media.socialvibe.com/m/invite/spacer.png"/><br><a href="http://www.socialvibe.com/?r=382172&i5"><b>Sean</b> invites you to SocialVibe.com</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.socialvibe.com/?r=382172&i5"><img border="0" src="http://media.socialvibe.com/m/invite/join_me.png"/></a></center>
*/
function socialvibe_admin_page(){

	if(empty($_POST)){
		$_POST['code']='Post the code SocialVibe gives you here and click extract. Then click save values to widget to display your cause.';
	}elseif($_POST['Submit']=='Save'){
		update_option('socialvibe_flash_id',$_POST['socialvibe_flash_id']);
		update_option('socialvibe_user_id',$_POST['socialvibe_user_id']);
	}elseif($_POST['Submit']=='Extract'){
		$CodeOut=socialvibe_interpret(stripslashes($_POST['code']));
	}

	echo '<div class="wrap">
	<form method="POST" action="" name="admin_page">
	<table class="form-table">
	<tr>
	<td><textarea rows="4" cols="55" name="code">'.stripslashes($_POST['code']).'</textarea></td>
	<td><input type="submit" name="Submit" value="Extract"><br><br>
	<input type="submit" name="Submit" value="Save"></td>
	</tr>
	<tr>
	<td>Flash ID: <input type="text" name="socialvibe_flash_id" value="'.($CodeOut['Flash'] ? $CodeOut['Flash'] : get_option('socialvibe_flash_id')).'"></td>
	<td>User ID: <input type="text" name="socialvibe_user_id" value="'.($CodeOut['User'] ? $CodeOut['User'] : get_option('socialvibe_user_id')).'"></td></tr>

	</table></form>

	<h2>Other Options</h2><form method="POST" action="options.php">';

	wp_nonce_field('update-options');

	echo '<table class="form-table"><tr>
	<td>Color Mode</td>';
	if($Mode=get_option('socialvibe_color_mode')=='Night'){
		$Night=' SELECTED';
	}else{
		$Day=' SELECTED';
	}

	echo '<td><select name="socialvibe_color_mode"><option value="Day"'.$Day.'>Day</option><option value="Night"'.$Night.'>Night</option></select></td></tr>

	<tr>
	<td>When no Flash ID is supplied:</td>
	<td><select name="socialvibe_no_flash">';

	foreach(array('Image1','Image2','Nothing') as $Opt){
		if($Opt==$NoFlash){
			$S=' SELECTED';
		}else{
			$S='';
		}
		echo '<option name="'.$Opt.'">Display '.$Opt.'</option>';
	}

	echo '</select></td>
	</tr>
	<tr><td>Image 1: <a href="http://www.socialvibe.com/?r=382172&i1"><img border="0" src="http://media.socialvibe.com/m/invite/invite_msg'.(get_option('socialvibe_color_mode')=='Night' ? '_b' : '').'.png"/></a>
	Image 2: <a href="http://www.socialvibe.com/?r=382172&i1"><img border="0" src="http://media.socialvibe.com/m/invite/join_me.png"/></a></td>
	<td><input type="hidden" name="action" value="update" /><input type="submit" name="Submit" value="Save Changes" /></td>
	</tr>
	</table>
	</form>
	</div>';
}

//<p style="margin-top:0; width:316; text-align:center;"><object width="316" height="348"><param name="movie" value="http://media.socialvibe.com/sv2.swf?sid=488750"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="flashvars" value="s=1-488750"/><embed src="http://media.socialvibe.com/sv2.swf?sid=488750" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" flashvars="s=1-488750" width="316" height="348"></embed></object><br><a href="http://www.socialvibe.com/?r=382172&rs=join_sv" target="_blank"><img src="http://media.socialvibe.com/m/badge/join_sv.png" border="0"/></a></p>

?>