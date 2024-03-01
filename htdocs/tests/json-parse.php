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

$jsonString = '[{ "error": { "message": "\'answers\' is not one of [\'fine-tune\', \'assistants\'] - \'purpose\'", "type": "invalid_request_error", "param": null, "code": null } } ,{ "object": "list", "data": [], "has_more": false }]';

$jsonString = '{ "object": "list", "data": [ { "id": "gpt-4-vision-preview", "object": "model", "created": 1698894917, "owned_by": "system" }, { "id": "dall-e-3", "object": "model", "created": 1698785189, "owned_by": "system" }, { "id": "gpt-4-turbo-preview", "object": "model", "created": 1706037777, "owned_by": "system" }, { "id": "gpt-3.5-turbo-0613", "object": "model", "created": 1686587434, "owned_by": "openai" }, { "id": "dall-e-2", "object": "model", "created": 1698798177, "owned_by": "system" }, { "id": "gpt-3.5-turbo-instruct-0914", "object": "model", "created": 1694122472, "owned_by": "system" }, { "id": "whisper-1", "object": "model", "created": 1677532384, "owned_by": "openai-internal" }, { "id": "tts-1-hd-1106", "object": "model", "created": 1699053533, "owned_by": "system" }, { "id": "tts-1-hd", "object": "model", "created": 1699046015, "owned_by": "system" }, { "id": "babbage-002", "object": "model", "created": 1692634615, "owned_by": "system" }, { "id": "text-embedding-3-small", "object": "model", "created": 1705948997, "owned_by": "system" }, { "id": "gpt-3.5-turbo-instruct", "object": "model", "created": 1692901427, "owned_by": "system" }, { "id": "gpt-3.5-turbo-0125", "object": "model", "created": 1706048358, "owned_by": "system" }, { "id": "gpt-3.5-turbo", "object": "model", "created": 1677610602, "owned_by": "openai" }, { "id": "davinci-002", "object": "model", "created": 1692634301, "owned_by": "system" }, { "id": "gpt-3.5-turbo-0301", "object": "model", "created": 1677649963, "owned_by": "openai" }, { "id": "tts-1", "object": "model", "created": 1681940951, "owned_by": "openai-internal" }, { "id": "tts-1-1106", "object": "model", "created": 1699053241, "owned_by": "system" }, { "id": "text-embedding-ada-002", "object": "model", "created": 1671217299, "owned_by": "openai-internal" }, { "id": "gpt-3.5-turbo-1106", "object": "model", "created": 1698959748, "owned_by": "system" }, { "id": "gpt-3.5-turbo-16k", "object": "model", "created": 1683758102, "owned_by": "openai-internal" }, { "id": "gpt-4", "object": "model", "created": 1687882411, "owned_by": "openai" }, { "id": "gpt-4-0613", "object": "model", "created": 1686588896, "owned_by": "openai" }, { "id": "gpt-3.5-turbo-16k-0613", "object": "model", "created": 1685474247, "owned_by": "openai" }, { "id": "gpt-4-1106-preview", "object": "model", "created": 1698957206, "owned_by": "system" }, { "id": "text-embedding-3-large", "object": "model", "created": 1705953180, "owned_by": "system" }, { "id": "gpt-4-0125-preview", "object": "model", "created": 1706037612, "owned_by": "system" } ] }';


$data = json_decode($jsonString, true);

var_dump( $data );

if( isset($data['error']) ){

    echo $data['error']['code'];
    echo '<br>';
    echo $data['error']['message'];


} else {

    if( isset($data['data']) ){
        foreach( $data['data'] as $item ){
            echo $item['id'] . "<br>\n";
        }
    }


/*
    $imgs_html = '';
    foreach( $data['data'] as $item ){
        echo $item['url'] . "<br>\n";
        $imgs_html .= '<img src="' . $item['url'] . '">';
    }

    echo '<br><br><div style="">';
    echo $imgs_html;
    echo '</div>';

    #var_dump( $data );
*/
}




