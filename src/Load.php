<?php

function loadRemoteUserData(string $username): string
{
    $httpClient = new \GuzzleHttp\Client();
    $fullUrl = "https://www.instagram.com/$username/?__a=1";
    $response = $httpClient->get("$fullUrl");
    $content = $response->getBody()->getContents();
    $data = json_decode($content, true);

    return "";
}