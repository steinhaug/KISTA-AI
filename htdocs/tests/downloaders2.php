<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(__FILE__)) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(__FILE__)) . '/uploaded_files');

require_once dirname(APPDATA_PATH) . '/func.inc.php';
require_once dirname(APPDATA_PATH) . '/func.login.php';



use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

$retrieve_count = 0;
function openai__guzzleDownloader($url, $code404_mode = false){
    global $retrieve_count;

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
        $retrieve_count++;

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

$url = 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-HxrNYO2D4mQGQskdc0JxvuNd.png?st=2024-03-04T14%3A54%3A53Z&se=2024-03-04T16%3A54%3A53Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-03-04T15%3A11%3A23Z&ske=2024-03-05T15%3A11%3A23Z&sks=b&skv=2021-08-06&sig=72/A/cQpAWle9GMrrTj%2Bc3tiMNXgdZL7fii0t1EK%2Bv4%3D';

$data = openai__guzzleDownloader($url);
if($data[0]=='200' and $data[2]=='png'){
    $filename = $upload_id . '-' . $USER_ID . '-1.png';
    file_put_contents(UPLOAD_PATH . '/' . $filename, $data[1]);
    echo 'File saved: ' . UPLOAD_PATH . '/' . $filename . '<br>';
}

var_dump($data);