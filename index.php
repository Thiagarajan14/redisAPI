<?php
require './vendor/autoload.php';

$users = [];
$t0 = 0;
$t1 = 0;
$TimeTaken = -1;
$t0 = microtime(true)*1000;
$redis = new Predis\Client();
$cachedEntry = $redis->get('users');
$t1 = microtime(true)*1000;

if($cachedEntry)
{
    echo "Display from redis  <br>";
    $users = json_decode($cachedEntry);
}
else
{
    $t0 = microtime(true)*1000;
    echo "Dispalay the data from server<br>";
    $httpClient = new GuzzleHttp\Client(['base_uri' => 'https://jsonplaceholder.typicode.com/', 'verify'=>false ]);
    $response = $httpClient->request('GET', 'users');
    $users = json_decode($response->getBody());
    $redis->set('users', json_encode($users));
    $redis->expire('users', 10); 
    $t1 = microtime(true)*1000;

}

    foreach($users as $u){
        echo "<strong>ID:</strong> $u->id <br>";
        echo "<strong>Name:</strong> $u->name <br>";
        echo "<strong>Email ID:</strong> $u->email <br><br>";
        
    }

$TimeTaken = $t1-$t0;
echo "Total Time Taken is $TimeTaken"
?>