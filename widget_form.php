<?php
$version = $instance['version'];

$type = $instance['type'];
$bg_sel = $type == "bg" ? 'selected' : '';
$fav_sel = $type == "fav" ? 'selected' : '';

$showDate = $instance["showDate"];
$show_date_no = $showDate == 0 ? 'checked' : '';
$show_date_yes = $showDate == 1 ? 'checked' : '';

$showVersion = $instance["showVersion"];
$show_version_no = $showVersion == 0 ? 'checked' : '';
$show_version_yes = $showVersion == 1 ? 'checked' : '';

$dateFormat = $instance["dateFormat"];
$ymd_sel = $dateFormat == "y-m-d" ? 'selected' : '';
$dmy_sel = $dateFormat == "d/m/y" ? 'selected' : '';
$mdy_sel = $dateFormat == "m/d/y" ? 'selected' : '';
$dmy2_sel = $dateFormat == "d.m.y" ? 'selected' : '';
?>

<p>
<b>Title:</b><br />
<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
</p>

  <p>
  <b>Type:</b><br />
<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
  <option value="fav" <?php echo $fav_sel; ?>>My Favorites (Random)</option>
  <option value="bg" <?php echo $bg_sel; ?>>BibleGateway VOTD</option>
  </select>
  </p>

<p>
<b>Version:</b><br />
<select id="<?php echo $this->get_field_id( 'version' ); ?>" name="<?php echo $this->get_field_name( 'version' ); ?>">
<?php
foreach ($this->versions as $num => $name) {
  $sel = $version == $num ? 'selected' : '';
  echo '<option value="'.$num.'" '.$sel.'>'.$name.'</option>'."\r\n";
}
?>
</select>
<br /><em>At this time Biblegateway doesn&rsquo;t supply the VOTD in all versions shown.</em>
  </p>

<p>
<strong>Show Version?</strong>
<input type=radio name="<?php echo $this->get_field_name( 'showVersion' ); ?>" value="1" <?php echo $show_version_yes; ?> /> Yes
<input type=radio name="<?php echo $this->get_field_name( 'showVersion' ); ?>" value="0" <?php echo $show_version_no; ?> /> No 
<br /><em>This will show the version acronym (if it exists) after the scripture reference</em>
</p>


<p>
<strong>Show Date in Title?</strong>
<input type=radio name="<?php echo $this->get_field_name( 'showDate' ); ?>" value="1" <?php echo $show_date_yes; ?> /> Yes 
<input type=radio name="<?php echo $this->get_field_name( 'showDate' ); ?>" value="0" <?php echo $show_date_no; ?> /> No 
<br /><em>This will tack today&rsquo;s date onto the end of the widget title</em>
</p>


<p>
<strong>Date Format</strong>
<select id="<?php echo $this->get_field_id( 'dateFormat' ); ?>" name="<?php echo $this->get_field_name( 'dateFormat' ); ?>">
  <option value="y-m-d" <?php echo $ymd_sel; ?>>2010-06-19</option>
  <option value="d/m/y" <?php echo $dmy_sel; ?>>19/6/2010</option>
  <option value="m/d/y" <?php echo $mdy_sel; ?>>6/19/2010</option>
  <option value="d.m.y" <?php echo $dmy2_sel; ?>>19.06.2010</option>
</select>
<br /><em>Applies if &lsquo;Show Date&rsquo; is &lsquo;Yes&rsquo;</em>
</p>
