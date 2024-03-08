<?php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

/**
 * openai__parse_vision_completion
 *
 * @param string $string Completion for ingredients
 * @return string Rewritten list of ingredients
 */
function openai__parse_vision_completion($string) {
    // Split the string into an array of lines
    $lines = explode("\n", $string);
    $result = "";

    foreach ($lines as $line) {
        // Check if the line starts with a number followed by a period
        if (preg_match('/^\d+\.\s*(.*)$/', $line, $matches)) {
            // Remove the markdown bold syntax
            $cleanLine = str_replace('**', '', $matches[1]);
            // Append the formatted line to the result string
            $result .= "- " . $cleanLine . "\n";
        }
    }

    return trim($result);
}

/**
 * openai__extract_prompts
 *
 * @param string $string
 * @return void
 */
function openai__extract_prompts($string) {
    // Split the string into lines
    $lines = explode("\n", $string);
    $array = [];

    foreach ($lines as $line) {
        // Use regex to capture the title and description, ignoring the numbering
        if (preg_match('/^\d+\.\s*"([^"]+)"\s*:\s*(.*)$/', $line, $matches)) {
            // Add the title and description as a single entry to the array
            $array[] = '"' . $matches[1] . '": ' . $matches[2];
        }
    }

    return $array;
}

/**
 * openai__extract_the_prompts: This one works
 *
 * @param string $str The prompts having prefix of "1. A", "2. A" and so on.
 * @return array Returns an array with 4 values.
 */
function openai__extract_the_prompts($str){
    $arr = [];

    $match = '2. ';
    $p2 = strpos($str, $match);
    $arr[] = substr($str, 0, $p2);

    $match = '3. ';
    $p3 = strpos($str, $match);
    $arr[] = substr($str, $p2, ($p3-$p2));

    $match = '4. ';
    $p4 = strpos($str, $match);
    $arr[] = substr($str, $p3, ($p4-$p3));

    $arr[] = substr($str, $p4);

    return array_map(function($val) {
        return trim(substr($val, 3));
    }, $arr);
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





function getArray__splitAtNeedle($string, $needle){

    if( ($pos=strpos($string, $needle)) === false )
        return [$string, ''];

    $str_before = substr($string, 0, $pos);
    $str_after  = substr($string, $pos);

    return [$str_before, $str_after];
}

/**
 * Prepare the reciepe for Markdown
 *
 * @param [type] $string
 * @return void
 */
function openai__parse_reciepe($string){

    $title = '';
    $header = '';
    $ingredients = '';
    $instructions = '';

    [$string, $instructions] = getArray__splitAtNeedle($string, 'Instructions:');
    [$string, $ingredients] = getArray__splitAtNeedle($string, 'Ingredients:');

    $ingredients = str_replace('Optional Ingredients:', '### Optional Ingredients:', $ingredients);

    $lines = explode("\n", $string);
    $title = str_replace('Recipe:', '', array_shift($lines));
    $header = implode("\n", $lines);

    $arr = [$title, $header, $ingredients, $instructions];

    return array_map(function($val) {
        return trim($val);
    }, $arr);
}


function openai__generateReciepe($string, $img){

    $img_src = '/uploaded_files/' . $img;
    $thumb_src = '/uploaded_files/_thumbs/' . $img;

    $parts = openai__parse_reciepe($string);
    $Parsedown = new Parsedown();
    echo $Parsedown->text( '# ' . $parts[0] );
    if(!empty($img))
        echo '<a href="' . $img_src . '" data-gallery="gallery-1"><img src="' . $thumb_src . '" alt="" style="float:right"></a>';
    echo $Parsedown->text( '' . $parts[1] );
    echo $Parsedown->text( '## ' . $parts[2] );
    echo $Parsedown->text( '## ' . $parts[3] );

}


function reciepe_thumb($image_name){
    return '/uploaded_files/_thumbs/' . $image_name;
}



function promptDalle($prompt, $imgInt=1){
    global $mysqli, $open_ai_key, $upload_id, $USER_ID, $kista_dp;

    try {
        $client = OpenAI::client($open_ai_key);

        $dalle_image_url = null;

        $response = $client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url',
        ]);

        foreach ($response->data as $data) {
            $dalle_image_url = $data->url;
        }
        $log[] = 'func(Dall-E), completed.';

        if(empty($dalle_image_url))
            throw new Exception('func(Dall-E) image_url error, empty.');

        $data = openai__guzzleDownloader($dalle_image_url);
        if($data[0]=='200' and $data[2]=='png'){
            if( is_string($imgInt) ){
                $filename = $imgInt;
            } else {
                $filename = str_pad($upload_id, 5, '0', STR_PAD_LEFT) . '-' . str_pad($USER_ID, 5, '0', STR_PAD_LEFT) . '-' . $imgInt . '.png';
            }
            file_put_contents(UPLOAD_PATH . '/' . $filename, $data[1]);
            $log[] = 'func(Dall-E), downloaded.';
            createThumbnail(
                UPLOAD_PATH . '/' . $filename,
                UPLOAD_PATH . '/_thumbs/' . $filename,
                ['resize' => [150, 150]]
            );
            $log[] = 'func(Dall-E) thumbnail, created.';

            if( $upload_id ){
                $sql = new sqlbuddy;
                $sql->que('reciepe_image', $filename, 'string');
                $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            }

            $dalle_img1 = [
                'path' => UPLOAD_PATH . '/' . $filename,
                'thumb_path' => UPLOAD_PATH . '/_thumbs/' . $filename,
                'src' => UPLOAD_URI . '/' . $filename,
                'thumb_src' => UPLOAD_URI . '/_thumbs/' . $filename,
            ];

        } else {
            throw new Exception('func(Dall-E) download error, http_status: ' . $data[0] . ', ' . $e->getMessage());
        }

    } catch (Exception $e) {
        //throw new OpenAIException($e->getMessage());
        return false;
    }

    return true;
    //return $filename;
}

/**
 * queryChatGPT
 *
 * @param string $prompt The prompt
 * @return string The completion
 */
function promptChatGPT3($prompt){
    global $mysqli, $open_ai_key, $upload_id, $USER_ID, $kista_dp;
    try {
        $client = OpenAI::client($open_ai_key);
        $settings = [
            'model' => 'gpt-3.5-turbo', // 'gpt-4-1106-preview', // 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];
        $response1 = $client->chat()->create($settings);
        $completion = '';
        foreach ($response1->choices as $result) {
            $completion = $result->message->content;
        }
    } catch (Exception $e) {
        debug_log_error($e->getMessage());
        return '';
    }

    return $completion;
}



