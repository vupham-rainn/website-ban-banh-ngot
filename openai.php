<?php
// openai.php – HOÀN HẢO CHO TECTALIC + PHP 8.0.30
require_once 'vendor/autoload.php';

use Tectalic\OpenAi\Manager;
use Tectalic\OpenAi\Authentication;
use GuzzleHttp\Client as GuzzleClient;

function getOpenAIClient() {
    static $client = null;
    if ($client === null) {
        $apiKey = ''; 

        $httpClient = new GuzzleClient();
        $auth = new Authentication($apiKey);
        $client = Manager::build($httpClient, $auth);
    }
    return $client;
}