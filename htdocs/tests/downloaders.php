<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(__FILE__)) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(__FILE__)) . '/uploaded_files');

require_once dirname(APPDATA_PATH) . '/func.inc.php';
require_once dirname(APPDATA_PATH) . '/func.login.php';


function downloadPngFile($url, $folder_path, $file_prefix) {
    $ch = curl_init();

    #$curl_browser_USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36";

    curl_setopt($ch, CURLOPT_URL, $url);
    #curl_setopt($ch, CURLOPT_USERAGENT, $curl_browser_USER_AGENT);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_HEADER, true);             // true to include the header in the output.
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);    // Follow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // Return the transfer as a string
    $data = curl_exec($ch);

    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $filename = basename(parse_url($effectiveUrl, PHP_URL_PATH));
    echo 'Save filename: ' . $filename . '<br>';

    $headers = curl_getinfo($ch);
    $header = substr($data, 0, $headers['header_size']);

    // Attempt to extract filename from the Content-Disposition header
    $matches = array();
    if (preg_match('/filename="([^"]+)"/', $header, $matches)) {
        $filename = $matches[1];
        echo 'New filename: ' . $filename . '<br>';
    } elseif (preg_match("/filename=([^;\s]+)/", $header, $matches)) {
        $filename = $matches[1];
        echo 'New filename: ' . $filename . '<br>';
    }

    curl_close($ch);

    $file_extension = get_extension($filename);
    if( $file_extension=='png' ){
        if ($data) {
            $final_filename = $file_prefix . $filename;
            file_put_contents($folder_path . '/' . $final_filename, $data);
            return $final_filename;
        } else {
            return false;
        }
    }

    return null;
}

$url = 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-YhCxTC54By5qmwZ4epr4Tki7.png?st=2024-03-04T13%3A46%3A43Z&se=2024-03-04T15%3A46%3A43Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-03-04T14%3A46%3A27Z&ske=2024-03-05T14%3A46%3A27Z&sks=b&skv=2021-08-06&sig=a9ZZSTn1ZGhId5HKjsNODjHg6eBBIOJbKPKOeIue5R8%3D';

echo downloadPngFile($url, UPLOAD_PATH, $USER_ID . '-');

