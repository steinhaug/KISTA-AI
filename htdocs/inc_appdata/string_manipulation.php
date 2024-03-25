<?php

/*
    If you put a serialized string in an HTML form on a UTF-8 page 
    you'll likely need to use this function when processing the form.
*/
function mb_unserialize($string) {
    //$string = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $string);
    $string = preg_replace_callback('/!s:(\d+):"(.*?)";!se/', function($matches) { return 's:'.strlen($matches[1]).':"'.$matches[1].'";'; }, $string);
    return unserialize($string);
}

function javasafe($string){
  $string = str_replace("'",'&rsquo;',$string);
  $string = str_replace('"','&quot;',$string);
  return $string;
}
function jsEscape($str) {
  return addcslashes($str,"\\\'\"&\n\r<>");
}
function stripQuotes($string){
  $string = str_replace("'","",$string);
  $string = str_replace('"',"",$string);
  return $string;
}


/* Returns a string with single whitespace */
/* Means two spaces becomes one space      */
function normalize_whitespace($string){
  return trim(preg_replace("/\s\s+/", ' ', $string));
}

/**
 * Seperate a block of code by sub blocks. Example, removing all <script>...<script> tags from HTML kode
 * Original sollution: https://stackoverflow.com/questions/27078259/get-string-between-find-all-occurrences-php
 * 
 * Lives kundeweb
 * 
 * @param string $str, text block
 * @param string $startDelimiter, string to match for start of block to be extracted
 * @param string $endDelimiter, string to match for ending the block to be extracted
 * @param string $removeDelimiters, remove delimiters from returned code
 * 
 * @return array [all inner blocks, all outer blocks, all blocks]
 */
function getDelimitedStrings($str, $startDelimiter, $endDelimiter, $removeDelimiters=true) {
    $contents = array();
    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = $outStart = $outEnd = 0;
    while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
        $contentStart += $startDelimiterLength;
        $contentEnd = strpos($str, $endDelimiter, $contentStart);
        $outEnd = $contentStart - 1;
        if (false === $contentEnd) {
            break;
        }

        if($removeDelimiters)
            $contents['inner'][] = substr($str, $contentStart, $contentEnd - $contentStart);
            else
            $contents['inner'][] = substr($str, ($contentStart-$startDelimiterLength), ($contentEnd + ($startDelimiterLength*2) +1) - $contentStart);

        if( $outStart ){
            $contents['outer'][] = substr($str, ($outStart+$startDelimiterLength+1), $outEnd - $outStart - ($startDelimiterLength*2));
            $contents['items'][] = ['type'=>'outer','string'=>substr($str, ($outStart+$startDelimiterLength+1), $outEnd - $outStart - ($startDelimiterLength*2))];
        } else if( ($outEnd - $outStart - ($startDelimiterLength-1)) > 0 ){
            $contents['outer'][] = substr($str, $outStart, $outEnd - $outStart - ($startDelimiterLength-1));
            $contents['items'][] = ['type'=>'outer','string'=>substr($str, $outStart, $outEnd - $outStart - ($startDelimiterLength-1))];
        }

        if($removeDelimiters)
            $contents['items'][] = ['type'=>'inner','string'=>substr($str, ($contentStart), ($contentEnd) - $contentStart)];
            else
            $contents['items'][] = ['type'=>'inner','string'=>substr($str, ($contentStart-$startDelimiterLength), ($contentEnd + ($startDelimiterLength*2) +1) - $contentStart)];

        $startFrom = $contentEnd + $endDelimiterLength;
        $startFrom = $contentEnd;
        $outStart = $startFrom;
    }

    // No full block detected by delimiters, so full $str is returned
    if( !isset($contents['inner']) ){
        $contents['outer'][] = $str;
        $contents['items'][] = ['type'=>'outer','string'=>$str];
        return $contents;
    }

    $total_length = strlen($str);
    $current_position = $outStart + $startDelimiterLength + 1;
    if( $current_position < $total_length ){
        $contents['outer'][] = substr($str, $current_position);
        $contents['items'][] = ['type'=>'outer','string'=>substr($str, $current_position)];
    }

    return $contents;
}

/**
 * Helper function for getDelimitedStrings(). Returns two blocks.
 */
function getDelimitedStrings_flattened($string, $startDelimiter, $endDelimiter, $removeDelimiters=true){
    $parsed = getDelimitedStrings($string, $startDelimiter, $endDelimiter, $removeDelimiters);
    return $parsed['items'];
}

function getDelimitedStrings_string($string, $startDelimiter, $endDelimiter, $removeDelimiters=true){
    $parsed = getDelimitedStrings($string, $startDelimiter, $endDelimiter, $removeDelimiters);
    foreach($parsed['items'] as $v){
        if($v['type'] == 'inner')
            return $v['string'];
    }
    return '';
}

/**
 * Padd all lines in a block of lines.
 *
 * @param string $lines      The input string.
 * @param int    $pad_length Number of time the $pad_string string should be repeated.
 * @param string $pad_string The string to be repeated.
 *
 * @return string Returns the string with each line padded.
 */
function line_pad($lines, $pad_length = 4, $pad_string = ' ')
{
    if (!str_contains($lines, "\n")) {
        return $lines;
    }

    $_lines = explode("\n", $lines);
    $out = '';
    foreach ($_lines as $line) {
        $out .= str_repeat($pad_string, $pad_length) . $line . "\n";
    }

    return rtrim($out);
}

/**
 * parse url params
 *
 * @param string $str Eg. some=value&another=value
 * @return array Parsed values
 */
function proper_parse_str($str) {
  # result array
  $arr = array();

  # split on outer delimiter
  $pairs = explode('&', $str);

  # loop through each pair
  foreach ($pairs as $i) {
    # split into name and value
    list($name,$value) = explode('=', $i, 2);
    
    # if name already exists
    if( isset($arr[$name]) ) {
      # stick multiple values into an array
      if( is_array($arr[$name]) ) {
        $arr[$name][] = $value;
      }
      else {
        $arr[$name] = array($arr[$name], $value);
      }
    }
    # otherwise, simply stick it in a scalar
    else {
      $arr[$name] = $value;
    }
  }

  # return result array
  return $arr;
}


/**
 * Returns the filetype from a filename, eg. "jpg"
 */
function get_extension($filename){
    return substr(strrchr((string) $filename, "." ),1);
}

/**
 * Returns the filename without extension, eg. "MyImage.jpg" => "MyImage"
 *
 * @param string $filename A filename string
 * 
 * @return string Returns the string without the last extension
 */
function get_name_only($filename){

    return pathinfo($filename, PATHINFO_FILENAME);
    return substr($filename, 0, strrpos($filename, '.'));
}

function _bool($var, $g=null){

    if($g!==null){
        if($g=='_SESSION'){
            $var = $_SESSION[$var] ?? '';
        } else if($g=='_POST'){
            $var = $_POST[$var] ?? '';
        } else if($g=='_GET'){
            $var = $_GET[$var] ?? '';
        } else {
            logfile('_bool $g unknown! ' . $g);
            echo '_bool $g unknown! ' . $g;
            exit;
        }
    }

  if(is_bool($var)){
    return $var;
  } else if($var === NULL || $var === 'NULL' || $var === 'null'){
    return false;
  } else if(is_string($var)){
    $var = strtolower(trim($var));
    if($var=='false'){ return false;
    } else if($var=='true'){ return true;
    } else if($var=='no'){ return false;
    } else if($var=='yes'){ return true;
    } else if($var=='off'){ return false;
    } else if($var=='on'){ return true;
    } else if($var==''){ return false;
    } else if(ctype_digit($var)){
      if((int) $var)
        return true;
        else
        return false;
    } else { return true; }
  } else if(ctype_digit((string) $var)){
    if((int) $var)
      return true;
      else
      return false;
  } else if(is_array($var)){
    if(count($var))
      return true;
      else
      return false;
  } else if(is_object($var)){
    return true;// No reason to (bool) an object, we assume OK for crazy logic
  } else {
    return true;// Whatever came though must be something,  OK for crazy logic
  }
}