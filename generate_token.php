<?php
require __DIR__ . '/vendor/autoload.php';

// 1. Make sure you have a "credentials.json" file from Google Cloud Console
//    and place it in the same directory as this script.

$client = new Google_Client();
$client->setAuthConfig('credentials.json'); // Your credentials file
$client->setRedirectUri('http://localhost:8000');
$client->setScopes([
    'https://www.googleapis.com/auth/drive',
]);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// 2. Generate the URL
$authUrl = $client->createAuthUrl();
printf("Open the following link in your browser:\n%s\n", $authUrl);
print('Enter verification code: ');
$authCode = trim(fgets(STDIN));

// 3. Exchange authorization code for an access token.
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
$client->setAccessToken($accessToken);

// 4. Save the token to a file.
if (!file_exists(dirname('token.json'))) {
    mkdir(dirname('token.json'), 0700, true);
}
file_put_contents('token.json', json_encode($client->getAccessToken()));
printf("Token saved to token.json\n");