<?php

use MatthiasMullie\Minify;

function minify_js($js){
    $minifier = new Minify\JS();
    $minifier->add($js);
    return $minifier->minify();
}

function minify_css($css){
    $minifier = new Minify\CSS();
    $minifier->add($css);
    return $minifier->minify();
}

/**
 * Minification of page HTML, moving inline javascript and styles to the bottom of the page
 *
 * @param string $html Mixed html with style and script tags
 * @return void
 */
function minify_page($html){

    $warning = null;
    $warning_html = '';

    if (($pos = strpos($html, 'type="x-tmpl-mustache"')) !== false) {
        $warning_desc = 'Handlebar templates detected, this creates problems for the minification, so move handlebar script blocks into the helper function.';
        $warning_html = 'type="x-tmpl-mustache"';
        $warning = 'js';
    }

    if (($pos = strpos($html, 'type="text/x-handlebars-template"')) !== false) {
        $warning_desc = 'Handlebar templates detected, this creates problems for the minification, so move handlebar script blocks into the helper function.';
        $warning_html = 'type="text/x-handlebars-template"';
        $warning = 'js';
    }

    if (($pos = strpos($html, '<script ')) === false) {
        $blocks = getDelimBlocks($html, '<script>', '</script>');
        $html = '';
        foreach ($blocks as $item) {
            if ($item['type'] == 'inner') {
                if ($item['right'] == '</script>') {
                    que_js($item['html'], 1);
                } else {
                    $html .= $item['left'] . $item['html'] . $item['right'];
                }
            } else {
                $html .= $item['html'];
            }
        }
    } else if($warning===null){
        $warning_html = htmlentities(substr($html, $pos, 64));
        $warning = 'js';
    }

    if (strpos($html, '<style ') === false) {
        $blocks = getDelimBlocks($html, '<style>', '</style>');
        $html = '';
        foreach ($blocks as $item) {
            if ($item['type'] == 'inner') {
                if ($item['right'] == '</style>') {
                    que_css($item['html'], 1);
                } else {
                    $html .= $item['left'] . $item['html'] . $item['right'];
                }
            } else {
                $html .= $item['html'];
            }
        }
    } else if($warning===null){
        $warning_html = htmlentities(substr($html, $pos, 32));
        $warning = 'css';
    }

    if($warning===null){

        $blocks = getDelimBlocks($html, [['<pre','>'], '</pre>']);
        $html = '';
        foreach ($blocks as $item) {
            if ($item['type'] == 'inner') {
                if ($item['right'] == '</pre>') {
                    $html .= $item['left'] . $item['html'] . $item['right'];
                } else {
                    $html .= minify_html($item['left'] . $item['html'] . $item['right']);
                }
            } else {
                $html .= minify_html($item['html']);
            }
        }

        return $html;

    }

    $warning_html =  '<b>Problem code:</b><p style="color:red;">' . str_replace(["\n","\r",' '],['<br>','','&nbsp;'], $warning_html) . '</p>';

    if(empty($warning_desc))
        $warning_desc = 'String was not compressed since we detected edge case.';

    $t = debug_backtrace();

    $dir = str_replace('\\','/',$t[1]['file']);
    $parts = explode('/', $dir);
    $wanted_parts = [];
    $i = count($parts);

    while($i>0) {
        $wanted_parts[] = $parts[$i-1];
        if($parts[$i] == 'webshop.easywebshop.no' or $parts[$i] == 'public_html' or $parts[$i] == 'www' or $parts[$i] == 'www.appdata')
            break;
        $i--;
    }
    $wanted_parts[0] = '<span style="color:blue;">' . $wanted_parts[0] . '</span>:<b style="color: green">' . $t[1]['line'] . '</b>';
    $str_path = '<b>Minify call:</b>./<br>' .  implode('/<br> ', array_reverse($wanted_parts));

/*
echo '<pre>';
echo '<h1>' . $warning . '<h1>';
echo $warning_desc . '<br><br>' . $warning_html . $str_path;
echo '<hr>';
echo htmlentities($html);
echo '<hr>';
var_dump($t);exit;
*/

    que_warning('COMPRESS WARNING', $warning, $warning_desc . '<br><br>' . $warning_html . $str_path);

    //$string .= html_turbo_compressor(trim($item['string']),false,true);
    
    return '<!-- Minification aborted -->' . LF . '<!-- / MINIFICATION BLOCK -->' . LF . $html . LF . '<!-- / MINIFICATION BLOCK -->' . LF;
}

/**
 * Quick minification, different modes for type of markup being minified
 *
 * @param string $html
 * @param string $type header|html
 * @return void
 */
function minify_html($html, $type='html'){

    // Remove comments
    $html = preg_replace('/(?=<!--)([\s\S]*?)-->/', '', $html);

    // Remove whitespace
    $html = preg_replace('/\s+/', ' ', $html);

    if ($type == 'header') {
        $html = str_replace(
            [' <meta ',' <link ',' <script ',' <script>','</script> '],
            [ '<meta ', '<link ', '<script ', '<script>','</script>'],
            $html
        );
        $html = str_replace(
            "<!DOCTYPE html> <html lang='nb' locale='NO' class=\"no-js\"> <head>",
            "<!DOCTYPE html>\n<html lang=\"nb\" locale=\"NO\" class=\"no-js\">\n<head>",
            $html
        );
    } else if($type == 'modals'){

    } else if($type == 'handlebar_tpls'){

    } else {
        $html = str_replace(
            ['> <div ','> </div> ','> <ul ','> </ul> ','> <li>','> </li>','> <a href','> <i ','> <span>','> <svg ','> <button '],
            ['><div ' , '></div> ', '><ul ', '></ul> ', '><li>', '></li>', '><a href', '><i ', '><span>', '><svg ', '><button '],
            $html
        );
    }

    return $html;
}




















/**
 * Check if session is enabled
 *
 * @return boolean True if started
 */

function is_session_started () {
    return function_exists ( 'session_status' ) ? ( PHP_SESSION_ACTIVE == session_status () ) : ( ! empty ( session_id () ) );
}

