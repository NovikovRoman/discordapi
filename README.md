```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use AuthManager\OAuthManager;
use DiscordAPI\User;
use GuzzleHttp\Exception\GuzzleException;
use DiscordAPI\Client;

$clientID = 'our client id';
$secretKey = 'our secret key';

$client = new Client(
    $clientID,
    $secretKey,
    ['identify', 'email'],
    'https://our.domain'
);

$am = new OAuthManager($client);
$state = 123456;

if (!empty($_GET['code'])) {

    try {
        $token = $am->getToken($_SERVER['REQUEST_URI'], $state);
        $client->setToken($token);

        $u = new User($client);
        print_r($u->me());

    } catch (GuzzleException $e) {
        exit($e->getMessage());
        
    } catch (Exception $e) {
        exit($e->getMessage());
    }

} else {
    $am->signin($state, true, ['prompt' => true]);
}
```