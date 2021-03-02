<?php

namespace StatIg\Stat;

use Illuminate\Support\Collection;
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
    $followers = $data->graphql->user->edge_followed_by->count ?? null;
    $stat['followers'] = $followers;
    $stat['posts'] = [];

    $postNodes = $data->graphql->user->edge_owner_to_timeline_media->edges;
    foreach ($postNodes as $nodeData){
        $node = $nodeData->node;
        $comments = $node->edge_media_to_comment->count;
        $likes = $node->edge_liked_by->count;
        $engagements = $comments + $likes;
        $er = null;
        if ($followers > 0) {
        $er = round($engagements / $followers * 100);
        }
        $post = [
            'comments' => $comments,
            'likes' => $likes,
            'engagements' => $engagements,
            'er' => $er,
            'url' => "https://www.instagram.com/p/{$node->shortcode}"
        ];
        $stat['posts'][] = $post;
    }
    if ($followers > 0) {
        $stat['avgEr'] = getAverageEngagementRate($stat['posts'], $followers);
    }
    $stat['mostLikedPost'] = findTopPost($stat['posts'], 'likes');
    $stat['mostCommentedPost'] = findTopPost($stat['posts'], 'comments');
    $stat['topEr'] = findTopPost($stat['posts'], 'er');
    return $stat;
}

function engSum($engs, $post)
{
    return $engs + $post['engagements'];
}

function getAverageEngagementRate(array $posts, int $followers): float
{
    $totalEngagements = array_reduce($posts, "StatIg\\Stat\\engSum", 0) ;
    if ($followers > 0) {
        $rate = round($totalEngagements / $followers / count($posts) * 100, 2);
    }
    return $rate;
}

function findTopPost(array $posts, $property): ?array
{
    $collection = new Collection($posts);
    return $collection->sortByDesc($property)->first();
}