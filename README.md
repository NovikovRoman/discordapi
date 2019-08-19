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
$code = 'http://localhost/?code=Yis7ICUZrSwEkGOH7lM2hczjApY5ur&state=123456';

if (!empty($_GET['code'])) {

    try {
        $am->getToken($_SERVER['REQUEST_URI'], $state);

        $u = new User($client);
        print_r($u->me());

    } catch (GuzzleException $e) {
        exit($e->getMessage());
        
    } catch (Exception $e) {
        exit($e->getMessage());
    }

} else {
    print_r($am->signin($state, false, ['prompt' => true]));
}
```