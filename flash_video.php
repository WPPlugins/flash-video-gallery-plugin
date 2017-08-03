<?php
/*
	Plugin Name: Flash Video Gallery plugin, pluginswp.com
	Plugin URI: http://www.pluginswp.com/flash-video-gallery-plugin/
	Description: With this plugin you can create video galleries with flv videos and/or youtube videos. You can use the plugin as a widget. USE: Install and activate the plugin. You will see a new button on your wordpress administrator, "flash Video." Click here to create your videos galleries. To insert a gallery in your posts, type [flash_video X/], where X is the ID of the gallery.
	Version: 1.1
	Author: pluginswp.com
	Author URI: http://www.pluginswp.com/
*/	
$contador=0;

$nombrebox="Webpsilon".rand(99, 99999);
function flash_video_head() {
	
	$site_url = get_option( 'siteurl' );

			
}
function flash_video($content){
	$content = preg_replace_callback("/\[flash_video ([^]]*)\/\]/i", "flash_video_render", $content);
	return $content;
	
}

function flash_video_render($tag_string){
$contador=rand(9, 9999999);
	$site_url = get_option( 'siteurl' );
global $wpdb; 	
$table_name = $wpdb->prefix . "flash_video";	


if(isset($tag_string[1])) {
	$auxi1=str_replace(" ", "", $tag_string[1]);
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = ".$auxi1.";" );
}
if(count($myrows)<1) $myrows = $wpdb->get_results( "SELECT * FROM $table_name;" );
	$conta=0;
	$id= $myrows[$conta]->id;
	$video = $myrows[$conta]->video;
	$titles = $myrows[$conta]->titles;
	$width = $myrows[$conta]->width;
	$height = $myrows[$conta]->height;
	$images = $myrows[$conta]->images;
	$round = $myrows[$conta]->round;
	$controls = $myrows[$conta]->controls;
	$skin = $myrows[$conta]->skin;
	$overplay = $myrows[$conta]->overplay;
	$row= $myrows[$conta]->row;
	$color1 = $myrows[$conta]->color1;
	$color2 = $myrows[$conta]->color2;
	$autoplay = $myrows[$conta]->autoplay;

	$tags = $myrows[$conta]->tags;
	
	$texto='';
	
	

$texto='title='.$titles.'&controls='.$controls.'&color1='.$color1.'&color2='.$color2.'&round='.$round.'&autoplay='.$autoplay.'&skin='.$skin.'&youtube='.$youtube.'&overplay='.$overplay.'&rows='.$row.'&round='.$round;

$links = array();
$titlesa = array();
if($video!="") $links=preg_split ("/\n/", $video);
if($titles!="") $titlesa=preg_split ("/\n/", $titles);
if($images!="") $imagesa=preg_split ("/\n/", $images);
$cont1=0;

while($cont1<count($links)) {
	$auxititle="";
	$auxivideo="";
	$auxiimages="";
	$auxtipo=0;
	if(isset($titlesa[$cont1])) $auxititle=$titlesa[$cont1];
	if(isset($links[$cont1])) $auxivideo=$links[$cont1];
	if(isset($imagesa[$cont1])) $auxiimages=$imagesa[$cont1];
	if($auxivideo!="") {
		$auxtipo=1;
		if(strstr($auxivideo, "http")) {
			if(strpos($auxivideo, "youtube")>0) {
				$auxivideo=getYTidflash($auxivideo);
				$auxtipo=2;
				
			}
			else $auxtipo=1;
		}
		else $auxtipo=2;
		

	}
	$texto.='&video'.$cont1.'='.$auxivideo.'&title'.$cont1.'='.$auxititle.'&tipo'.$cont1.'='.$auxtipo.'&image'.$cont1.'='.$auxiimages;
	$cont1++;
}
$texto.='&cantidad='.$cont1;
	
	

	
	$table_name = $wpdb->prefix . "flash_video";
	$saludo= $wpdb->get_var("SELECT id FROM $table_name ORDER BY RAND() LIMIT 0, 1; " );
	$output='
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$width.'" height="'.$height.'" id="flash'.$id.'-'.$contador.'" title="'.$tags.'">
  <param name="movie" value="'.$site_url.'/wp-content/plugins/flash-video-gallery-plugin/flash_video.swf" />
  <param name="quality" value="high" />
  <param name="wmode" value="transparent" />
  	<param name="flashvars" value="'.$texto.'" />
	   <param name="allowFullScreen" value="true" />
  <param name="swfversion" value="9.0.45.0" />
  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
  <param name="expressinstall" value="'.$site_url.'/wp-content/plugins/flash-video-gallery-plugin/Scripts/expressInstall.swf" />
  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
  <!--[if !IE]>-->
  <object type="application/x-shockwave-flash" data="'.$site_url.'/wp-content/plugins/flash-video-gallery-plugin/flash_video.swf" width="'.$width.'" height="'.$height.'">
    <!--<![endif]-->
    <param name="quality" value="high" />
    <param name="wmode" value="transparent" />
    	<param name="flashvars" value="'.$texto.'" />
		   <param name="allowFullScreen" value="true" />
    <param name="swfversion" value="9.0.45.0" />
    <param name="expressinstall" value="'.$site_url.'/wp-content/plugins/flash-video-gallery-plugin/Scripts/expressInstall.swf" />
    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
    <div>
      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
    </div>
    <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object>
<script type="text/jflashscript">
<!--
swfobject.registerObject("flash'.$id.'-'.$contador.'");
//-->
</script><br/>'.$ligtext;
	return $output;
}


function getYTidflash($ytURL) {
#
 
#
$ytvIDlen = 11; // This is the length of YouTube's video IDs
#
 
#
// The ID string starts after "v=", which is usually right after
#
// "youtube.com/watch?" in the URL
#
$idStarts = strpos($ytURL, "?v=");
#
 
#
// In case the "v=" is NOT right after the "?" (not likely, but I like to keep my
#
// bases covered), it will be after an "&":
#
if($idStarts === FALSE)
#
$idStarts = strpos($ytURL, "&v=");
#
// If still FALSE, URL doesn't have a vid ID
#
if($idStarts === FALSE)
#
die("YouTube video ID not found. Please double-check your URL.");
#
 
#
// Offset the start location to match the beginning of the ID string
#
$idStarts +=3;
#
 
#
// Get the ID string and return it
#
$ytvID = substr($ytURL, $idStarts, $ytvIDlen);
#
 
#
return $ytvID;
#
 
#
}


function flash_video_instala(){
	global $wpdb; 
	$table_name= $wpdb->prefix . "flash_video";
   $sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		video longtext NOT NULL ,
		titles longtext NOT NULL ,
		width tinytext NOT NULL ,
		height tinytext NOT NULL ,
		images longtext NOT NULL ,
		round tinytext NOT NULL ,
		controls tinytext NOT NULL ,
		skin tinytext NOT NULL ,
		overplay tinytext NOT NULL ,
		row tinytext NOT NULL ,
		color1 tinytext NOT NULL ,
		color2 tinytext NOT NULL ,
		autoplay tinytext NOT NULL ,
		tags tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) ;";

   	$id= $myrows[$conta]->id;
	$video = $myrows[$conta]->video;
	$titles = $myrows[$conta]->titles;
	$width = $myrows[$conta]->width;
	$height = $myrows[$conta]->height;
	$images = $myrows[$conta]->images;
	$round = $myrows[$conta]->round;
	$controls = $myrows[$conta]->controls;
	$skin = $myrows[$conta]->skin;
	$overplay = $myrows[$conta]->overplay;
	$row= $myrows[$conta]->row;
	$color1 = $myrows[$conta]->color1;
	$color2 = $myrows[$conta]->color2;
	$autoplay = $myrows[$conta]->autoplay;
   	$tags = $myrows[$conta]->tags;
   
	$wpdb->query($sql);
	$sql = "INSERT INTO $table_name (video, titles, width, height, images, round, controls, skin, overplay, row, color1, color2, autoplay, tags) VALUES ('http://www.youtube.com/watch?v=ByYWGscaG4U\nhttp://www.youtube.com/watch?v=wDiUG52ZyHQ\nhttp://www.youtube.com/watch?v=llMIDqFbsnE\nhttp://www.youtube.com/watch?v=zzNs4-kRLaE\nhttp://www.youtube.com/watch?v=KR_9A-cUEJc', 'Victoria S\n300\nWoW\nAssasins\nPirates', '100%', '500px', '', '20', '0',  '1', '0', '4', '000000', 'ffffff', '0', '');";
	$wpdb->query($sql);
}
function flash_video_desinstala(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "flash_video";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);
}	
function flash_video_panel(){
	global $wpdb; 
	$table_name = $wpdb->prefix . "flash_video";	
	
	if(isset($_POST['crear'])) {
		$re = $wpdb->query("select * from $table_name");
//autos  no existe
if(empty($re))
{
  $sql = " CREATE TABLE $table_name(
	id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		video longtext NOT NULL ,
		titles longtext NOT NULL ,
		width tinytext NOT NULL ,
		height tinytext NOT NULL ,
		images longtext NOT NULL ,
		round tinytext NOT NULL ,
		controls tinytext NOT NULL ,
		skin tinytext NOT NULL ,
		overplay tinytext NOT NULL ,
		row tinytext NOT NULL ,
		color1 tinytext NOT NULL ,
		color2 tinytext NOT NULL ,
		autoplay tinytext NOT NULL ,
		tags tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) ;";
	$wpdb->query($sql);

}
		
	$wpdb->query($sql);
	$sql = "INSERT INTO $table_name (video, titles, width, height, images, round, controls, skin, overplay, row, color1, color2, autoplay, tags) VALUES ('http://www.youtube.com/watch?v=ByYWGscaG4U\nhttp://www.youtube.com/watch?v=wDiUG52ZyHQ\nhttp://www.youtube.com/watch?v=llMIDqFbsnE\nhttp://www.youtube.com/watch?v=zzNs4-kRLaE\nhttp://www.youtube.com/watch?v=KR_9A-cUEJc', 'Victoria S\n300\nWoW\nAssasins\nPirates', '100%', '500px', '', '20', '0',  '1', '0', '4', '000000', 'ffffff', '0', '');";
	$wpdb->query($sql);
	}
	
if(isset($_POST['borrar'])) {
		$sql = "DELETE FROM $table_name WHERE id = ".$_POST['borrar'].";";
	$wpdb->query($sql);
	}
	if(isset($_POST['id'])){	


$sql= "UPDATE $table_name SET `video` = '".$_POST["video".$_POST['id']]."', `titles` = '".$_POST["titles".$_POST['id']]."', `width` = '".$_POST["width".$_POST['id']]."', `height` = '".$_POST["height".$_POST['id']]."', `images` = '".$_POST["images".$_POST['id']]."', `round` = '".$_POST["round".$_POST['id']]."', `controls` = '".$_POST["controls".$_POST['id']]."', `skin` = '".$_POST["skin".$_POST['id']]."', `overplay` = '".$_POST["overplay".$_POST['id']]."', `row` = '".$_POST["row".$_POST['id']]."', `color1` = '".$_POST["color1".$_POST['id']]."', `color2` = '".$_POST["color2".$_POST['id']]."', `autoplay` = '".$_POST["autoplay".$_POST['id']]."', `tags` = '".$_POST["tags".$_POST['id']]."' WHERE `id` =  ".$_POST["id"]." LIMIT 1";
			$wpdb->query($sql);
	}
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name" );
$conta=0;

include('template/cabezera_panel.html');
while($conta<count($myrows)) {
	$id= $myrows[$conta]->id;
	$video = $myrows[$conta]->video;
	$titles = $myrows[$conta]->titles;
	$width = $myrows[$conta]->width;
	$height = $myrows[$conta]->height;
	$images = $myrows[$conta]->images;
	$round = $myrows[$conta]->round;
	$controls = $myrows[$conta]->controls;
	$skin = $myrows[$conta]->skin;
	$overplay = $myrows[$conta]->overplay;
	$row= $myrows[$conta]->row;
	$color1 = $myrows[$conta]->color1;
	$color2 = $myrows[$conta]->color2;
	$autoplay = $myrows[$conta]->autoplay;
	$tags = $myrows[$conta]->tags;
	include('template/panel.html');			
	$conta++;
	}

}









function widget_flash_video($args) {

 
  
    extract($args);
	
	  $options = get_option("widget_flash_video");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'flash video',
	  'id' => '1'
      );
  }

	$aaux=array();
	$aaux[0]="flash_video";
	
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  $aaux[1]=$options['id'];
 echo flash_video_render($aaux);
  echo $after_widget;

}



function flash_video_control()
{
  $options = get_option("widget_flash_video");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'flash video',
	  'id' => '1'
      );
  }
 
  if ($_POST['flash-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['flash-WidgetTitle']);
	 $options['id'] = htmlspecialchars($_POST['flash-WidgetId']);
    update_option("widget_flash_video", $options);
  }
  
  
  global $wpdb; 
	$table_name = $wpdb->prefix . "flash_video";
	
	$myrows = $wpdb->get_results( "SELECT * FROM $table_name;" );

if(empty($myrows)) {
	
	echo '
	<p>First create a new gallery of videos, from the administration of video flash plugin.</p>
	';
}

else {
	$contaa1=0;
	$selector='<select name="flash-WidgetId" id="flash-WidgetId">';
	while($contaa1<count($myrows)) {
		
		
		$tt="";
		if($options['id']==$myrows[$contaa1]->id)  $tt=' selected="selected"';
		$selector.='<option value="'.$myrows[$contaa1]->id.'"'.$tt.'>'.$myrows[$contaa1]->id.'</option>';
		$contaa1++;
		
	}
	
	$selector.='</select>';
	
	
 
echo '
  <p>
    <label for="flash-WidgetTitle">Widget Title: </label>
    <input type="text" id="flash-WidgetTitle" name="flash-WidgetTitle" value="'.$options['title'].'" /><br/>
	<label for="flash-WidgetTitle">flash Video Gallery ID: </label>
   '.$selector.'
    <input type="hidden" id="flash-Submit" name="flash-Submit" value="1" />
  </p>
';
}


}









function flash_video_init(){
	register_sidebar_widget(__('flash video'), 'widget_flash_video');
	register_widget_control(   'flash video', 'flash_video_control', 300, 300 );
}












function flash_video_add_menu(){	
	if (function_exists('add_options_page')) {
		//add_menu_page
		add_menu_page('flash_video', 'flash Video', 8, basename(__FILE__), 'flash_video_panel');
	}
}
if (function_exists('add_action')) {
	add_action('admin_menu', 'flash_video_add_menu'); 
}
add_action('wp_head', 'flash_video_head');
add_filter('the_content', 'flash_video');
add_action('activate_flash_video/flash_video.php','flash_video_instala');
add_action('deactivate_flash_video/flash_video.php', 'flash_video_desinstala');
add_action("plugins_loaded", "flash_video_init");
?>