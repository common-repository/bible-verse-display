<?php
  /*
   Plugin Name: Bible Verse Display
   Plugin URI: http://kirilisa.com/projects/bible-verse-display/
   Description: Bible Verse Display lets you have a bible verse displayed in a widget, on a page, or in a post. The bible verse is taken randomly from your nominated favorites or from Biblegateway's Verse of the Day. No typing of verses is necessary. Multiple versions/languages are available.
   Version: 1.6
   Author: Elise Bosse
   Author URI: http://kirilisa.com

   Copyright 2010  Elise Bosse  (email : kirilisa@gmail.com)   
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */

if (!class_exists("BVDisplay")) {
  class BVDisplay {
  
    public $versions;
    public $versionCodes;

    function BVDisplay() {
      require('bible-versions.php');    
      $this->versions = $versions;
      $this->versionCodes = $versionCodes;
    }

    function activate() {
      // add options to database
      add_option("bvd_post_version", '31');	
      add_option("bvd_post_type", 'fav');
      add_option("bvd_connection", 'fopen');
      add_option("bvd_show_version", '1');
      add_option("bvd_favorites", 'Psalm 8:3-5|Psalm 4:8|Psalm 27:1|Psalm 27:14|Psalm 11:7|1 Samuel 15:22|Psalm 30:4-5|Psalm 27:7-9a|Psalm 38:9|Psalm 50:10-11|Psalm 50:23|Psalm 71:19|Psalm 73:21-24|Hebrews 13:17|Hebrews 12:3|James 4:3|James 4:7-8|Psalm 4:4|Jeremiah 17:5|Hebrews 13:5-6|Hebrews 13:8|Psalm 56:3-4|Psalm 43:5|Psalm 146:7');
    }

    function deactivate() {
      // remove options from database
      delete_option("bvd_post_version");	
      delete_option("bvd_post_type");
      delete_option("bvd_connection");
      delete_option("bvd_show_version");
      delete_option("bvd_favorites");
    }

    function add_admin_page() {
      add_submenu_page('options-general.php', 'Bible Verse Display', 'Bible Verse Display', 10, __FILE__, array(&$this, 'admin_page'));
    } 

    function admin_page() {     
      // update settings
      if(isset($_POST['bvd_update'])) {

	// posted data
	$type = $_POST['bvd_post_type'];
	$version = $_POST['bvd_post_version'];
	$cxn = $_POST['bvd_connection'];
	$showver = $_POST['bvd_show_version'];
        
	// update data in database
	update_option("bvd_post_type", $type);	
	update_option("bvd_post_version", $version);	
	update_option("bvd_connection", $cxn);	
	update_option("bvd_show_version", $showver);	
	
	// updated message
	echo "<div id=\"message\" class=\"updated fade\"><p><strong>Bible Verse Display options updated.</strong></p></div>";
      }

      // add new favorite
      else if(isset($_POST['bvd_add'])) {	
	$new = trim($_POST['bvd_new_fav']);

	if (!BVDisplay::isVerse($new)) {
	  echo "<div id=\"message\" class=\"updated fade\"><p><strong>Invalid verse format.</strong></p></div>";
	} else {
	  BVDisplay::updateFavorites($new);
	  echo "<div id=\"message\" class=\"updated fade\"><p><strong>Added $new to Favorites.</strong></p></div>";
	}
      }

      // delete favorite
      else if (isset($_POST) && !empty($_POST)) {
	foreach ($_POST as $key => $val) {
	  if (preg_match('/^bvd_delete_([0-9]+)$/', $key, $matches)) {
	    BVDisplay::updateFavorites($matches[1]);
	    echo "<div id=\"message\" class=\"updated fade\"><p><strong>Removed item.</strong></p></div>";
	  }
	}
      }

      $favorites = explode('|', get_option('bvd_favorites', '')); 

      require_once('admin_page.php');
    }


    function updateFavorites($str) {
      $favorites = explode('|', get_option('bvd_favorites', ''));

      // delete action
      if (preg_match('/^[0-9]+$/', $str)) {
	unset($favorites[$str]);
	$str = '';
      }

      // add action
      else {
	$str .= "|";
      }

      foreach ($favorites as $fav) $str .= "$fav|";
      $str = trim($str, '|');
      
      update_option("bvd_favorites", $str);
    }

    //@@@ need better verse matching
    function isVerse($v) {
      return preg_match('/^[a-z0-9: -]+$/i', $v);
    }

    function widget_init() {
      if (!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
	return;

      register_widget('BVDisplayWidget');
    }


    function add_js() {
      echo file_get_contents(ABSPATH.'wp-content/plugins/bible-verse-display/functions.js');
    }

  }
}

class BVDisplayWidget extends WP_Widget {
  
    public $versions;
    public $versionCodes;

  function BVDisplayWidget() {
    require('bible-versions.php');    
    $this->versions = $versions;
    $this->versionCodes = $versionCodes;

    $widget_ops = array('classname' => 'bvd', 'description' => 'Widget to display bible verse');
    $control_ops = array('id_base' => 'bvd-widget');

    $this->WP_Widget('bvd-widget', 'Bible Verse Display', $widget_ops, $control_ops);
  }

    function replace_shortcode($atts) {
      // set defaults
      extract(shortcode_atts(array('type'=> get_option('bvd_post_type'), 'version' => get_option('bvd_post_version'), 'showversion' => get_option('bvd_show_version'), 'class' => 'bvdshortcode'), $atts));

      return $this->get_verse($type, $version, $showversion, $class);
    }

  function biblegateway_version($version) {
    // $version can't be an integer
    if (preg_match('/^[0-9]+$/', $version)) return $version; 

    // return chosen version: default to NIV
    return array_key_exists($version, $this->versionCodes) ? $this->versionCodes[$version] : 31;
  }

  function fetch_url($url) {
    switch (get_option('bvd_connection')) {
    case "curl":
      if (function_exists("curl_init")) {
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);  
        curl_close($ch);
	return $output;
      }
      break;

    case "fopen": // falls through
    default:
      return ($fp = fopen($url, 'r')) ? stream_get_contents($fp) : false;
      break;
    }

    return false;
  }

  function get_verse($type, $version, $showVersion, $class='bvdwidget') {
    switch($type) {
    case "fav":
      $favorites = explode('|', get_option('bvd_favorites', ''));
      $lookup = urlencode($favorites[array_rand($favorites)]);
      $ver =  $this->biblegateway_version($version); // get biblegateway ID if a code was used
      $url = "http://www.biblegateway.com/passage/?search=$lookup&version=$ver";
      $verse = "";

      $content = $this->fetch_url($url); 
      if ($content != "") {
	// get verse in HTML off biblegateway.com
	if (preg_match('/<meta property="og:description" content="([^\/]+)"/', $content, $matches)){
	  $verse = $matches[1];
     	} else {
	  echo "Couldn't get ".urldecode($lookup)." ($url).";
	}

	if ($verse != "") {
	  // add scripture reference
	  $verse .= " &mdash; ".urldecode($lookup);

	  // add version if we have shorthand for it and they want it
	  $verse .= $showVersion && in_array($ver, $this->versionCodes) ? " (".strtoupper(array_search($ver, $this->versionCodes)).")" : ""; 
	}
      } else {
	echo "No verse found.";
      }

      break;

    case "bg":
      $ver =  $this->biblegateway_version($version); // get biblegateway ID if a code was used
      $url = "http://www.biblegateway.com/votd/get/?format=atom&version=$ver";

      $content = $this->fetch_url($url); 
      if ($content != "") {
	$cnt1 = preg_match('/<entry>\s+?<title>(.*?)<\/title>/i', $content, $matches1);
	$cnt2 = preg_match('/<content type="html"><\!\[CDATA\[\&ldquo;(.*?)\&rdquo;\]\]><\/content>/', $content, $matches2);

	if ($cnt2 == 1) {
	  $verse = $matches2[1];
	  $verse .= " &mdash; ".$matches1[1];
	  
	  // add version if we have shorthand for it and they want it
	  $verse .= $showVersion && in_array($ver, $this->versionCodes) ? " (".strtoupper(array_search($ver, $this->versionCodes)).")" : ""; 
	} else {
	  $verse = "Couldn't get Verse of the Day.";
	}
      } else {
	$verse = "No Verse of the Day found.";
      }

      break;

    default:
      $verse = "No verse found.";
      break;
    }

    return "<div class=\"$class\">$verse</div>";
  }

  function widget($args, $instance) {
    extract($args);

    $title = apply_filters('widget_title', $instance['title']);
    $type = $instance['type'];
    $version = $instance['version'];
    $showVersion = $instance['showVersion'];
    $showDate = $instance['showDate'];
    $dateFormat = $instance['dateFormat'];

    echo $before_widget;

    if ($showDate) {
      switch($dateFormat) {
      case 'y-m-d':
	$title = trim($title." ".date('Y-m-d', time()));
	break;
      case 'd/m/y':
	$title = trim($title." ".date('j/n/Y', time()));
	break;
      case 'd.m.y':
	$title = trim($title." ".date('d.m.Y', time()));
	break;
      case 'm/d/y':
	$title = trim($title." ".date('n/j/Y', time()));
	break;
      }
    }

    if ( $title )
      echo $before_title . $title . $after_title;

    if ( $type && $version )
      echo  BVDisplayWidget::get_verse($type, $version, $showVersion);

    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['version'] = $new_instance['version'];
    $instance['showVersion'] = $new_instance['showVersion'];
    $instance['type'] = $new_instance['type'];
    $instance['showDate'] = $new_instance['showDate'];
    $instance['dateFormat'] = $new_instance['dateFormat'];

    return $instance;
  }

  function form($instance) {

    $defaults = array( 'title' => 'Verse of the Day', 'version' => '31', 'showVersion' => 1, 'type' => 'fav', 'showDate' =>'0', 'dateFormat' => 'y-m-d');
    $instance = wp_parse_args( (array) $instance, $defaults ); 

    include("widget_form.php");
  }
}

  // instantiate class
if (class_exists("BVDisplay")) {
  $bvdisplay = new BVDisplay();
}

if (class_exists("BVDisplay")) {
  $bvwidget = new BVDisplayWidget();
}

// actions/filters
if (isset($bvdisplay)) {
  // administrative options
  add_action('admin_menu', array(&$bvdisplay, 'add_admin_page'));  
  add_action("widgets_init", array(&$bvdisplay, 'widget_init'));

  // shortcodes
  //add_shortcode('bible-verse-display', array(&$bvdisplay, 'replace_shortcode'));
  add_shortcode('bible-verse-display', array(&$bvwidget, 'replace_shortcode'));

  // activate/deactivate
  register_activation_hook(__FILE__, array(&$bvdisplay, 'activate'));
  register_deactivation_hook(__FILE__, array(&$bvdisplay, 'deactivate'));
}
?>
