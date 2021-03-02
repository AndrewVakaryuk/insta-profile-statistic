<?php
namespace StatIg\Load;
use function StatIg\Storage\saveUserData;

function loadRemoteUserData(string $username): string
{
    $httpClient = new \GuzzleHttp\Client();
    $fullUrl = "https://www.instagram.com/$username/?__a=1";
    $response = $httpClient->get("$fullUrl");
    $content = $response->getBody()->getContents();
    $data = json_decode($content);
    if (!is_object($data)) {
        throw new \Exception("User data is not an object ");
    }
    $fullPath = saveUserData($username, $data);

    return $fullPath;
}