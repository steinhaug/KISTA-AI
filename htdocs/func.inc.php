<?php
date_default_timezone_set("Europe/Oslo");
mb_internal_encoding('UTF-8');
setlocale(LC_TIME, "nb_NO.utf8");

if(!isset($PHP_SELF))
$PHP_SELF = $_SERVER["SCRIPT_NAME"];

if(!isset($HTTP_REFERER)){
	if(isset($HTTP_REFERER))
	$HTTP_REFERER = $_SERVER["HTTP_REFERER"];
	else
	$HTTP_REFERER = '';
}
if( !isset($_SERVER['HTTP_ACCEPT_ENCODING']) )
    $_SERVER['HTTP_ACCEPT_ENCODING'] = '';
if( !isset($_SERVER['HTTP_USER_AGENT']) )
    $_SERVER['HTTP_USER_AGENT'] = '';

class KistaDashboardException extends Exception
{
}
define('LF', "\n");

require dirname(dirname(APPDATA_PATH)) . '/vendor/autoload.php';
require dirname(dirname(APPDATA_PATH)) . '/credentials.php';
require APPDATA_PATH . '/image_helpers.php';
require APPDATA_PATH . '/db_helpers.php';
require APPDATA_PATH . '/session_helpers.php';
require APPDATA_PATH . '/string_manipulation.php';

if(!function_exists('sqlError__alertAndStop')){ function sqlError__alertAndStop($sql_error, $sql_query, $reference = '', $UserID = 0, $trace = null){
    return time();
} }

/**
 * Logging for debugging when working local
 */
if( !function_exists('logfile') ){
    function logfile(...$strings ){
        $lf = "\n";
        $logfile = dirname(dirname(__FILE__)) . '/logs/snaketail.log';
        if( $fh = @fopen( $logfile, "a+" ) ){
            foreach($strings as $the_string){
                fputs( $fh, $the_string . $lf, strlen($the_string . $lf) );
            }
            fclose( $fh );
            return( true );
        } else {
            return( false );
        }
    }
}

/**
 * Prepare URL for use in header.location
 *
 * @return string The complete URI for the current page including params
 */
function prepareLocation(){
    $uri = $_SERVER['SCRIPT_NAME'];
    if(!empty($_SERVER['QUERY_STRING'])){
        parse_str($_SERVER['QUERY_STRING'], $arr);
        $params = '';
        foreach($arr as $k=>$v){
            if(strlen($params))
                $params .= '&';
            $params .= $k . '=' . rawurlencode($v);
        }
        $uri = $uri . '?' . $params;

    }
    return $uri;
}


/* This function is not identical in EWS and ECMS */
/* Example:      ECMS          EWS                */
/* WITH_under    with_under    with-under         */
/* WITH-dash     with-dash     withdash           */
function dirify($s,$delimiter='-',$pretty=false) {
  $s = convert_high_ascii($s);              // convert high-ASCII chars to 7bit
  $s = strtolower((string) $s);                      // lower-case
  $s = strip_tags($s);                      // remove HTML tags
  $s = preg_replace('/&[^;\s]+;/','',$s);   // remove HTML entities
  if($pretty){
    if(($delimiter == '-') OR ($delimiter == '_'))
      $s = preg_replace('/[^\w\s' . $delimiter . ']/','',$s); // remove non-word/space chars but keep delimiter!
      else
      $s = preg_replace('/[^\w\s]/','',$s); // remove non-word/space chars but keep delimiter!
    $s = preg_replace('/\s+/',(string) $delimiter,$s); // change space chars to underscores
    $s = utf8_decode($s);
    $s = str_replace('..','.',$s);
    $s = str_replace("?","",$s);
    $s = str_replace("__","_",$s);
    if(($delimiter == '-') OR ($delimiter == '_')){
      $s = preg_replace("/^" . $delimiter . "/","",$s);
      $s = preg_replace("/" . $delimiter . "$/","",$s);
    }
  } else {
    if(($delimiter == '-') OR ($delimiter == '_'))
      $s = preg_replace('/[^\w\s' . $delimiter . ']/',(string) $delimiter,$s); // remove non-word/space chars but keep delimiter!
      else
      $s = preg_replace('/[^\w\s]/','',$s); // remove non-word/space chars but keep delimiter!
    $s = preg_replace('/\s/',(string) $delimiter,$s); // change space chars to underscores
    $s = utf8_decode($s);
  }
  if($delimiter=='-') $s = str_replace('_',$delimiter,$s);
  if($delimiter=='_') $s = str_replace('-',$delimiter,$s);
  return $s;
}
function convert_high_ascii($s) {
  $high_ascii = array(
    "!\xc0!" => 'A',  "!\xe0!" => 'a',    // A` a`
    "!\xc1!" => 'A',  "!\xe1!" => 'a',    // A' a'
    "!\xc2!" => 'A',  "!\xe2!" => 'a',    // A^ a^
    "!\xc4!" => 'Ae', "!\xe4!" => 'ae',   // A: a:
    "!\xc3!" => 'A',  "!\xe3!" => 'a',    // A~ a~
    "!\xc8!" => 'E',  "!\xe8!" => 'e',    // E` e`
    "!\xc9!" => 'E',  "!\xe9!" => 'e',    // E' e'
    "!\xca!" => 'E',  "!\xea!" => 'e',    // E^ e^
    "!\xcb!" => 'Ee', "!\xeb!" => 'ee',   // E: e:
    "!\xcc!" => 'I',  "!\xec!" => 'i',    // I` i`
    "!\xcd!" => 'I',  "!\xed!" => 'i',    // I' i'
    "!\xce!" => 'I',  "!\xee!" => 'i',    // I^ i^
    "!\xcf!" => 'Ie', "!\xef!" => 'ie',   // I: i:
    "!\xd2!" => 'O',  "!\xf2!" => 'o',    // O` o`
    "!\xd3!" => 'O',  "!\xf3!" => 'o',    // O' o'
    "!\xd4!" => 'O',  "!\xf4!" => 'o',    // O^ o^
    "!\xd6!" => 'Oe', "!\xf6!" => 'oe',   // O: o:
    "!\xd5!" => 'O',  "!\xf5!" => 'o',    // O~ o~
    "!\xd9!" => 'U',  "!\xf9!" => 'u',    // U` u`
    "!\xda!" => 'U',  "!\xfa!" => 'u',    // U' u'
    "!\xdb!" => 'U',  "!\xfb!" => 'u',    // U^ u^
    "!\xdc!" => 'Ue', "!\xfc!" => 'ue',   // U: u:
    "!\xc7!" => 'C',  "!\xe7!" => 'c',    // ,C ,c
    "!\xd1!" => 'N',  "!\xf1!" => 'n',    // N~ n~
    "!\xdf!" => 'ss',                     //
    "!\xc6!" => 'AE', "!\xe6!" => 'ae',   // Æ  æ
    "!\xd8!" => 'OE', "!\xf8!" => 'oe',   // Ø  ø
    "!\xc5!" => 'A',  "!\xe5!" => 'a',    // Å  å
    "!\x8a!" => 'S',  "!\x9a!" => 's',    // S with v over (som å)
    "!\x8c!" => 'CE', "!\x9c!" => 'ce',   // CE symbol
    "!\x8e!" => 'Z',  "!\x9e!" => 'z',    // Z with v over (som å)
    "!\xdd!" => 'Y',  "!\xFd!" => 'Y',    // Y´ Y´
    "!\x9f!" => 'Y',  "!\xde!" => 'Y',    // Y: Y´
    "!\xd0!" => 'D',  "!\xf0!" => 'a',    // -D obelix?
    "!\xb9!" => '1',                      // sup 1
    "!\xb2!" => '2',  "!\xb3!" => '3',    // 2  3
  );
  $find = array_keys($high_ascii);
  $replace = array_values($high_ascii);
  $s = preg_replace($find,$replace,(string) $s);
  return $s;
}
/**
 * Returns the filetype from a filename
 */
function get_extension($filename){
    return substr(strrchr((string) $filename, "." ),1);
}
/* function prepare_filename
   split up filename for further use  */
function prepare_filename($filename){
  $file = array(
    'raw' => '',
    'rawname'=> '',
    'file'=>'',
    'name'=> '',
    'type'=> ''
  );
  $file['raw'] = $filename;
  $t_verEXT = explode('.',(string) $filename);
  $file['type'] = $t_verEXT[count($t_verEXT)-1];
  $t_FILE = '';
  if(count($t_verEXT)>=2){
    for($i=0;$i<count($t_verEXT) - 1;$i++){
      if($i) $t_FILE .= '.';
      $t_FILE .= $t_verEXT[$i];
    }
    $file['rawname'] = $t_FILE;
    $file['name'] = dirify($t_FILE);
    $file['file'] = dirify($t_FILE) . '.' . dirify($file['type']);
  } else {
    $t_FILE .= $t_verEXT[0];
    $file['rawname'] = $t_FILE;
    $file['name'] = dirify($t_FILE);
    $file['file'] = dirify($t_FILE);
  }
  return $file;
}
/**
 * Return filename that is also available in directory
 */
function prepare_available_filename($raw_filename, $path = []){
    $filename       = prepare_filename($raw_filename);
    $filename_new   = get_available_filename($path,$filename['file'],120);
    if( $filename['file'] !== $filename_new ){
        $filename = prepare_filename($filename_new);
    }
    return $filename;
}

/* functin get_available_filename
   Parameters:
   $dirpath: string or array of paths to check
   $filename: array('name'=>'','type'=>'')

   Will go through the paths and return available name which
   is not present in any directory.
   */
function get_available_filename($dirpath,$filename,$maxlen=40){
  $paths = array();
  if(is_array($dirpath)){
    foreach($dirpath AS $path){
      $paths[] = $path;
    }
  } else {
    $paths[] = $dirpath;
  }
  if(!is_array($filename)){
    $filename = prepare_filename($filename);
  }
  $valid_name = '';
  $available = false;
  $i = 0;

  if($maxlen AND ($maxlen > 4)){
    if(strlen($filename['name'] . '.' . $filename['type']) >= $maxlen)                   
      $filename['name'] = substr((string) $filename['name'],0, ($maxlen - 7)); // .jpg + _99 = 7  
  }

    while(!$available){
        if(preg_match("/-[0-9]{2}$/",(string) $filename['name'])){
            $filename['name'] = substr((string) $filename['name'],0,-3);
        }
        $available = true;

        if($i)
            $test = $filename['name'] . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) . (strlen((string) $filename['type'])?'.' . $filename['type']:'');
            else
            $test = $filename['name'] . (strlen((string) $filename['type'])?'.' . $filename['type']:'');

        foreach($paths AS $path){
            if(preg_match("/\/$/",(string) $path)) $path = preg_replace("/\/$/","",(string) $path);
            if(file_exists($path . '/' . $test)){
                $available = false;
                break;
            }
        }
        $i++;
    }

  return $test;
}

/**
 * Undocumented function
 *
 * @param [type] $text
 * 
 * @return void
 */
function cleanString($text) {
	global $mysqli;
	$utf8 = array(
	'/[áàâãªä]/u'   =>   'a',
	'/[ÁÀÂÃÄ]/u'    =>   'A',
	'/[ÍÌÎÏ]/u'     =>   'I',
	'/[íìîï]/u'     =>   'i',
	'/[éèêë]/u'     =>   'e',
	'/[ÉÈÊË]/u'     =>   'E',
	'/[óòôõºö]/u'   =>   'o',
	'/[ÓÒÔÕÖ]/u'    =>   'O',
	'/[úùûü]/u'     =>   'u',
	'/[ÚÙÛÜ]/u'     =>   'U',
	'/ç/'           =>   'c',
	'/Ç/'           =>   'C',
	'/ñ/'           =>   'n',
	'/Ñ/'           =>   'N',
	'/–/'           =>   '-',
	'/[\\\]/u'        =>   ' - ',
	'/[|’‘‹›‚\']/u'  =>   '',
	'/[“”´«»„"¨~^]/u'    =>   '',
	'/ /'           =>   ' ',
	);
	$res = $mysqli->real_escape_string(preg_replace(array_keys($utf8), array_values($utf8), $text));
	$res = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '\xEF\xBF\xBD', $res);
	return $res;
}

