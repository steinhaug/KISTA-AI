<?php



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