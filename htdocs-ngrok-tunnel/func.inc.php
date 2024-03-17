<?php


if(!function_exists('ob_flush')){ function ob_flush() { return true; }} // Patch for DG 80.64.202.13 server
if(!function_exists('same_length')){ function same_length($a,$b,$s=' '){ if(strlen((string) $a) == strlen((string) $b)) return array($a,$b); if(strlen((string) $a) > strlen((string) $b)){ while(strlen((string) $a) > strlen((string) $b)){ $b .= $s; } return array($a,$b); } else { while(strlen((string) $a) < strlen((string) $b)){ $a .= $s; } return array($a,$b);} return array($a,$b);}}
if(!function_exists('getallheaders')){
    function getallheaders() {
        $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (str_starts_with($name, 'HTTP_')){
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        return $headers;
    }
}


/**
 * Logging for debugging when working local
 */
if( !function_exists('logfile') ){
    function logfile(...$strings ){
        $lf = "\n";
        $logfile = dirname(dirname(__FILE__)) . '/logs/snaketail.log';
        if( $fh = @fopen( $logfile, "a+" ) ){
            foreach($strings as $the_string){
                if(!is_string($the_string))
                    $the_string = json_encode($the_string);
                fputs( $fh, $the_string . $lf, strlen($the_string . $lf) );
            }
            fclose( $fh );
            return( true );
        } else {
            return( false );
        }
    }
}

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

if( empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) )
    $HTTP_ACCEPT_LANGUAGE = '';
    else
    $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

class KistaDashboardException extends Exception
{
}
define('LF', "\n");
require dirname(dirname(APPDATA_PATH)) . '/credentials.php';

require APPDATA_PATH . '/notifications.php';
require APPDATA_PATH . '/error_handling.php';
require APPDATA_PATH . '/image_helpers.php';
require APPDATA_PATH . '/db_helpers.php';
require APPDATA_PATH . '/string_manipulation.php';

if(!function_exists('sqlError__alertAndStop')){ function sqlError__alertAndStop($sql_error, $sql_query, $reference = '', $UserID = 0, $trace = null){
    return time();
} }

/**
 * Returns the filetype from a filename
 */
function get_extension($filename){
    return substr(strrchr((string) $filename, "." ),1);
}

/**
 * openai__guzzleDownloader: downloads file
 *
 * @param string $url Url to download
 * @param boolean $code404_mode
 * @return array [http_status, file_data, mime_file_type]
 */
function openai__guzzleDownloader($url, $code404_mode = false){

    /*
    $jar = \GuzzleHttp\Cookie\CookieJar::fromArray(
        [
            '__cfduid' => 'dee37465ad38688381eef5ed3915d30541614585158',
            'pll_language' => 'en',
            '_ga' => 'GA1.2.476151566.1614585160',
            '_gid' => 'GA1.2.1446800732.1614916192',
            '_jsuid' => '746616582',
            '_fbp' => 'fb.1.1614585172803.1388703749',
            'cookie_notice_accepted' => 'true',
            '_fbc' => 'fb.1.1614600114178.IwAR01zxmMh35m4FckyTSzMf800emphRV89F0MEl6Lp7yaqTpufH4GXhRODWw1-serie2-serie-gc',
            'mg_wp_session' => '31452913f5e7a85ea12e9fd180bae044||1614959390||1614957590',
            '_first_pageview' => '1',
            'no_tracky_101076340' => '1'
        ],
        'heinz-performance.com'
    );
    */

    $u = parse_url ($url);
    $client = new GuzzleHttp\Client(['base_uri' => $u['scheme'] . '://' . $u['host']]);

    try {

        $response = $client->request('GET', $url, [
            //'cookies' => $jar,
            'force_ip_resolve' => 'v4'
        ]);

        // Get all of the response headers.
        /*
        foreach ($response->getHeaders() as $name => $values) {
            echo $name . ': ' . implode(', ', $values) . "<br>";
        }
        */
        $body = (string) $response->getBody();
        //echo strlen($body) . ' bytes.';

        $mimes = new \Elephox\Mimey\MimeTypes;
        $extension = $mimes->getExtension($response->getHeaderLine('Content-Type'));

        return [(int) $response->getStatusCode(), $body, $extension];

    } catch (ClientException $e) {

        $response = $e->getResponse();
        //echo 'NOTICE: HTTP Status ' . $response->getStatusCode() . ' reported for URI ' . $url . '<br>';

        $mimes = new \Elephox\Mimey\MimeTypes;
        $extension = $mimes->getExtension($response->getHeaderLine('Content-Type'));

        if($code404_mode){
            $html = $response->getBody()->getContents();
            return [200, $html, $extension];
        } else {
            echo Psr7\Message::toString($e->getRequest());
            //echo Psr7\Message::toString($e->getResponse());
            return [404,null,null];
        }

    }

}



function generateUuid4() {
    $randomBytes = bin2hex(random_bytes(16));
    return sprintf("%s-%s-%s-%s-%s",
        substr($randomBytes, 0, 8),
        substr($randomBytes, 8, 4),
        substr($randomBytes, 12, 4),
        substr($randomBytes, 16, 4),
        substr($randomBytes, 20)
    );
}

/**
 * Simplified syntax for _GET values
 * 
 * Returns the _GET value if it is set, optionally alternate values can be defined with filter for _GET
 * 
 */
function _GET($val, $else_val=null, $filter=null){

    if( ($else_val === null) AND ($filter === null) ){
        if(isset($_GET[$val]))
            return $_GET[$val];
            else
            return '';
    }

    $return_var = $else_val;
    if(isset($_GET[$val])){
        $valid = true;
        if($filter == 'int'){
            if( !isInteger($_GET[$val]) )
                $valid = false;
        }
        if($filter == 'numeric'){
            if( !is_numeric($_GET[$val]) )
                $valid = false;
        }
        if($valid)
            $return_var = $_GET[$val];
    }

    return $return_var;

}

/**
 * Validate a string as integer
 * 
 * is_int() validates if the resource is integer, a post or get will always be a string.
 * 
 * @param string $val String to validate
 * @return boolean True if string is considered integer, false if not 
 */
function isInteger($val){
    if (!is_scalar($val) || is_bool($val)) {
        return false;
    }
    if(!is_numeric($val))
        return false;

    if (is_float($val + 0) && ($val + 0) > PHP_INT_MAX) {
        return false;
    }
    return is_float($val) ? false : preg_match('~^((?:\+|-)?[0-9]+)$~', $val);
}