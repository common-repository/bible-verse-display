<div class="wrap">
<h2>Bible Verse Display</h2>

<h3>Instructions for use</h3>
<ol>
<li>Use the widget to add a verse in a particular location of your layout</li>
<li>Use the [bible-verse-display] short tag to have a verse appear in a post or on a page:
<br />[bible-verse-display version="niv" type="bg" showversion="1" class="myclass"] (see parameters below)</li>
<li>To show the verse in your layout without using widgets, edit your layout&rsquo;s index.php, putting the shortcode where you want it to appear:
<br />&lt;?php echo do_shortcode("[bible-verse-display]"); ?&gt; (same parameters apply)</li>
</ol>

<h4>Shortcode parameters</h4>
You can use the shortcode with no parameters, in which case it will use version, etc. as defined in the Bible Verse Display Settings, or else specify any or all of the below parameters:
<ol>
<li>version = niv, esv, asv, nasv, kjv, nkjv, msg or the 
<a href="http://www.biblegateway.com/usage/linking/versionslist.php" target="_blank">biblegateway.com version id</a> </li>
<li>type = fav, bg</li>
<li>showversion = 1 or 0
<li>class = whatever you want (bvdshortcode is the default)
</ol>

<?php
$version = get_option("bvd_post_version");

$type = get_option("bvd_post_type");
$bg_sel = $type == "bg" ? 'selected' : '';
$fav_sel = $type == "fav" ? 'selected' : '';

$cxn = get_option("bvd_connection");
$fopen_sel = $cxn == "fopen" ? 'checked=checked' : '';
$curl_sel = $cxn == "curl" ? 'checked=checked' : '';

$showversion = get_option("bvd_show_version");
$showversion_sel = $showversion == "1" ? 'checked' : '';
$hideversion_sel = $showversion == "0" ? 'checked' : '';
?>
	
<form method="post" name="options" target="_self">

<table>

<tr><td colspan="2"><h4>Default settings</h4> <em>These apply to shortcodes that don&rsquo;t specify otherwise</em></td></tr>

<tr>
<td><strong>Verse Selection</strong></td> 

<td>
<select name="bvd_post_type">
  <option value="fav" <?php echo $fav_sel; ?>>My Favorites (Random)</option>
  <option value="bg" <?php echo $bg_sel; ?>>BibleGateway VOTD</option>
  </select>
</td>
</tr>

<tr>
<td><strong>Show Version</strong></td>
<td>
<input type="radio" name="bvd_show_version" value="1" <?php echo $showversion_sel; ?> /> yes
<input type="radio" name="bvd_show_version" value="0" <?php echo $hideversion_sel; ?> /> no 
 | <em>e.g. Psalm 23:1 (ESV)</em>
</td>
</tr>

<tr>
<td valign="top"><strong>Bible Version</strong></td> 

<td>
<select name="bvd_post_version">
<?php
foreach ($this->versions as $num => $name) {
  $sel = $version == $num ? 'selected' : '';
  echo '<option value="'.$num.'" '.$sel.'>'.$name.'</option>'."\r\n";
}
?>
  </select>
<br />
  <em>At this time Biblegateway doesn&rsquo;t supply the VOTD in certain translations.<br />Please see the <a href="http://www.biblegateway.com/usage/votd/custom_votd.php" target="_blank">dropdown list</a> at biblegateway for which versions are supported.</em>
</td>
</tr>


<tr><td colspan="2"><h4>Other settings</h4> <em>These apply everywhere</em></td></tr>

<tr>
<td><strong>Connection</strong></td>
<td>
<input type="radio" name="bvd_connection" value="fopen" <?php echo $fopen_sel; ?> /> fopen
<input type="radio" name="bvd_connection" value="curl" <?php echo $curl_sel; ?> /> curl
</td>
</tr>

</table>

  <p class="submit">
    <input type="submit" name="bvd_update" class="button-primary" value="Update Options &raquo;" />
  </p>

<hr />

<table>
<tr>
<td><strong>Add Favorite Verse</strong></td>
<td>
<input type="text" size="50" name="bvd_new_fav"> 
<input type="submit" name="bvd_add" class="button-primary" value="Add Verse &raquo;" />
</td>
</tr>
<tr><td>&nbsp;</td><td colspan="2"><em>e.g. 1 Corinthians 2:3-5</em></td></tr>
</table>

<br />

<hr />

<table width="100%" >
<tr><td><strong>Current Favorites</strong></td></tr>
<?php
echo "<tr>\r\n";
foreach ($favorites as $key => $fav) {  
  echo '<td><input type="submit" alt="Delete '.$fav.'" title="Delete '.$fav.'" name="bvd_delete_'.$key.'" class="button-primary" value="X" /> ';
  echo '<a href="javascript:void();" class="bvdtooltip">'.$fav.'</a></td>'."\r\n";
  
  if (($key % 3) == 2) echo "</tr>\r\n<tr>\r\n";
} 
?>
</table>

</form>

  </div>
