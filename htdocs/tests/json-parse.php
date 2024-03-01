<?php

// JSON string
/*
$jsonString = '{ "created": 1709252327, "data": [ { "url": "https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-zDFdO5oQodgy4e72ocT4sKJD.png?st=2024-02-29T23%3A18%3A47Z&se=2024-03-01T01%3A18%3A47Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T12%3A20%3A31Z&ske=2024-03-01T12%3A20%3A31Z&sks=b&skv=2021-08-06&sig=vWvU0vKzFqOMgQ%2BQ%2BiCP7zJ84ehmR0EedrOKh5XKj/E%3D" } ] }';
$data = json_decode($jsonString, true);
$url = $data['data'][0]['url'];
echo $url;
*/

$jsonString = '{ "created": 1709253260, "data": [ { "url": "https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-ThyhX8kc6CEtf7aWBjeT2cLx.png?st=2024-02-29T23%3A34%3A19Z&se=2024-03-01T01%3A34%3A19Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T20%3A05%3A38Z&ske=2024-03-01T20%3A05%3A38Z&sks=b&skv=2021-08-06&sig=7DWOKMavOR08AA6l9qTtMImoXNrA3C/XrgzfclzXzjU%3D" }, { "url": "https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-WzLjniODPPV8O6lWGNsIjozu.png?st=2024-02-29T23%3A34%3A20Z&se=2024-03-01T01%3A34%3A20Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T20%3A05%3A38Z&ske=2024-03-01T20%3A05%3A38Z&sks=b&skv=2021-08-06&sig=mUDII6v0yndqs//fIWgZs9%2B4W2LfAC6Q1XVDovIsYSM%3D" } ] }';
$jsonString = '{ "error": { "code": "invalid_image_format", "message": "Uploaded image must be a PNG and less than 4 MB.", "param": null, "type": "invalid_request_error" } }';

$data = json_decode($jsonString, true);

if( isset($data['error']) ){

    echo $data['error']['code'];
    echo '<br>';
    echo $data['error']['message'];

} else {

    $imgs_html = '';
    foreach( $data['data'] as $item ){
        echo $item['url'] . "<br>\n";
        $imgs_html .= '<img src="' . $item['url'] . '">';
    }

    echo '<br><br><div style="">';
    echo $imgs_html;
    echo '</div>';

    #var_dump( $data );
}




