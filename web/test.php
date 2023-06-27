<?php

require(__DIR__ . '/../vendor/autoload.php');

$items = getData('PLsJsWn3-KmsqhFcERx2TjNH0VnUEk1lyG');
echo '<pre>';print_r($items);echo '</pre>';
echo 'Finished';


function getDataByPageToken(string $playlistId, string $pageToken = null): array
{
    $url = 'https://www.googleapis.com/youtube/v3/playlistItems?key=AIzaSyAPvjxAhbwVYRMrLn5zAOuoRFq6vJVHpUI&part=snippet&maxResults=50&playlistId=' . $playlistId;
    if ($pageToken) {
        $url .= '&pageToken=' . $pageToken;
    }
    return json_decode(file_get_contents($url), true);
}

function getData(string $playlistId): array
{
    $nextPageToken = null;
    $items = [];
    
    do {
        $data = getDataByPageToken($playlistId, $nextPageToken);
        $nextPageToken = JmesPath\search('nextPageToken', $data);
        $expression = 'items[].{id: snippet.resourceId.videoId, title: snippet.title, publishedAt: snippet.publishedAt, thumbnails: snippet.thumbnails}';
        $items = array_merge($items, JmesPath\search($expression, $data));
    } while ($nextPageToken);
    
    return $items;    
}
