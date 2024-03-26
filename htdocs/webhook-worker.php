<?php
use Intervention\Image\ImageManagerStatic as Image;

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';


if( ($items = $mysqli->prepared_query("SELECT * FROM `" . $kista_dp . "replicate__hooks` WHERE `processed` = ?", 'i', [3])) === [] )
    die('No hooks to process');

echo '<pre>';
echo print_r( $items,1 );
echo '</pre>';