<?php

// JSON string
$jsonString = '{ "created": 1709252327, "data": [ { "url": "https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-zDFdO5oQodgy4e72ocT4sKJD.png?st=2024-02-29T23%3A18%3A47Z&se=2024-03-01T01%3A18%3A47Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T12%3A20%3A31Z&ske=2024-03-01T12%3A20%3A31Z&sks=b&skv=2021-08-06&sig=vWvU0vKzFqOMgQ%2BQ%2BiCP7zJ84ehmR0EedrOKh5XKj/E%3D" } ] }';

// Decode the JSON string into an associative array
$data = json_decode($jsonString, true);

// Access the "url" part
$url = $data['data'][0]['url'];

// Print the URL
echo $url;
