<?php
$versions = array();
$versionCodes = array();

// translate shortcode -> biblegateway id
$versionCodes['niv'] = 31;
$versionCodes['esv'] = 47;
$versionCodes['asv'] = 8;
$versionCodes['nasv'] = 49;
$versionCodes['kjv'] = 9;
$versionCodes['nkjv'] = 50;
$versionCodes['msg'] = 65;


/* biblegateway ids 
 * from http://www.biblegateway.com/usage/linking/versionslist.php
 */

// english
$versions[31] = 'New International Version';
$versions[47] = 'English Standard Version';
$versions[8] = 'American Standard Version';
$versions[49] = 'New American Standard Version';
$versions[9] = 'King James Version';
$versions[50] = 'New King James Version';
$versions[65] = 'The Message';

// french
$versions[32] = 'La Bible du Semeur';
$versions[2] = 'Louis Segond';

// italian
$versions[3] = 'Conferenza Episcopale Italiana';
$versions[55] = 'La Nuova Diodati';

// spanish
$versions[42] = 'Nueva Versi&oacute;n Internacional';
$versions[6] = 'Reina-Valera Antigua';

// german
$versions[10] = 'Luther Bibel 1545';

// romanian
$versions[14] = 'Romanian';
?>