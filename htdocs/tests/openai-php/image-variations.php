<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->images()->variation([
    'image' => fopen('../../../assets/dall-e/openai-01.png', 'r'),
    'n' => 4,
    'size' => '256x256',
    'response_format' => 'url',
]);

$response->created; // 1589478378

echo '<div>';
foreach ($response->data as $data) {
    $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
    $data->b64_json; // null

    echo '<img src="' . $data->url . '">';
}
echo '</div>';

krumo($response);
$json_all = $response->toArray();
echo htmlentities( json_encode($json_all) );

/*
{"created":1709274081,"data":[{"url":"https:\/\/oaidalleapiprodscus.blob.core.windows.net\/private\/org-UnWQwO02hhQxFEzXg50oYEIo\/user-MZxMP72hrU22dZHNg3I7Umkv\/img-YUSTZnTK2v7ei8ZrWyeBY6Zm.png?st=2024-03-01T05%3A21%3A21Z&se=2024-03-01T07%3A21%3A21Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image\/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-03-01T04%3A02%3A52Z&ske=2024-03-02T04%3A02%3A52Z&sks=b&skv=2021-08-06&sig=GIBQheqI4rgcPKvUKOUC8gEkcY\/L9W1gWFHNHJEmA6w%3D"},{"url":"https:\/\/oaidalleapiprodscus.blob.core.windows.net\/private\/org-UnWQwO02hhQxFEzXg50oYEIo\/user-MZxMP72hrU22dZHNg3I7Umkv\/img-PuF8jykAzD1Y6YwWfbkCAPyL.png?st=2024-03-01T05%3A21%3A21Z&se=2024-03-01T07%3A21%3A21Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image\/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-03-01T04%3A02%3A52Z&ske=2024-03-02T04%3A02%3A52Z&sks=b&skv=2021-08-06&sig=xgE3zzwQmbpwFhJpl6p99vCgL%2Buo56nTAsDFJg37kd8%3D"},{"url":"https:\/\/oaidalleapiprodscus.blob.core.windows.net\/private\/org-UnWQwO02hhQxFEzXg50oYEIo\/user-MZxMP72hrU22dZHNg3I7Umkv\/img-YGDLuhuXTEj5OCoH1PW1BpwW.png?st=2024-03-01T05%3A21%3A21Z&se=2024-03-01T07%3A21%3A21Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image\/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-03-01T04%3A02%3A52Z&ske=2024-03-02T04%3A02%3A52Z&sks=b&skv=2021-08-06&sig=P5R7PlQAzBl4U1vH71J9Uew0VpYXfNbxhPvVuFxq29g%3D"},{"url":"https:\/\/oaidalleapiprodscus.blob.core.windows.net\/private\/org-UnWQwO02hhQxFEzXg50oYEIo\/user-MZxMP72hrU22dZHNg3I7Umkv\/img-qDiHxuLKV29TifvsPMRYkbyS.png?st=2024-03-01T05%3A21%3A21Z&se=2024-03-01T07%3A21%3A21Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image\/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-03-01T04%3A02%3A52Z&ske=2024-03-02T04%3A02%3A52Z&sks=b&skv=2021-08-06&sig=y2AMMklHr9X3\/vx2qkMSjbnfLoy6RFd1hlSuCrTa5\/o%3D"}]}
*/