<?php

namespace StatIg\Stat;

use function StatIg\Storage\getUserData;

function getUserStat(string $username): array
{
    $data = getUserData($username);
    if ($data === null) {
        throw new \Exception("Data are not found for $username");
    }
    return getStatFromUserData($data);
}

function getStatFromUserData(\stdClass $data): array
{
    $stat = [];
    $stat['followers'] = $data->graphql->user->edge_followed_by->count;

    return $stat;
}