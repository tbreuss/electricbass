<?php

namespace app\commands;

use JmesPath;
use yii\console\Controller;

final class YoutubeController extends Controller
{
    /**
     * Sync all playlists from YouTube
     */
    public function actionSyncPlaylists(): void
    {
        $playlistIds = \Yii::$app->db->createCommand(
            'SELECT playlist_id FROM youtube_playlist'
        )->queryColumn();

        foreach ($playlistIds as $id) {
            $this->actionSyncPlaylist($id);
        }
    }

    /**
     * Sync one playlist from YouTube
     */
    public function actionSyncPlaylist(string $playlistId): void
    {
        $internalId = (int)\Yii::$app->db->createCommand(
            'SELECT id FROM youtube_playlist WHERE playlist_id=:playlistId',
            ['playlistId' => $playlistId]
        )->queryScalar();

        if ($internalId === 0) {
            return;
        }

        $playlist = $this->getData($playlistId);

        if (count($playlist) === 0) {
            return;
        }

        \Yii::$app->db->createCommand()->update(
            'youtube_playlist_item',
            [
                'deleted' => new \yii\db\Expression('NOW()'),
            ],
            [
                'youtube_playlist_id' => $internalId
            ]
        )->execute();

        foreach ($playlist as $item) {
            $title = $item['title'];
            $thumbnails = json_encode($item['thumbnails'], JSON_PRETTY_PRINT);
            $published = date('Y-m-d H:i:s', strtotime($item['publishedAt']));
            $randomId = new \yii\db\Expression('LOWER(LPAD(CONV(FLOOR(RAND()*POW(36,8)), 10, 36), 8, 0))'); // see https://stackoverflow.com/a/39259742
            \Yii::$app->db->createCommand()->upsert('youtube_playlist_item', [
                'id' => $randomId,
                'youtube_playlist_id' => $internalId,
                'video_id' => $item['videoId'],
                'title' => $title,
                'thumbnails' => $thumbnails,
                'published' => $published,
                'created' => new \yii\db\Expression('NOW()'),
                'synchronized' => new \yii\db\Expression('NOW()'),
                'deleted' => null
            ], [
                'title' => $title,
                'thumbnails' => $thumbnails,
                'published' => $published,
                'synchronized' => new \yii\db\Expression('NOW()'),
                'deleted' => null
            ])->execute();
        }
    }

    private function getDataByPageToken(string $playlistId, ?string $pageToken = null): array
    {
        $ApiKey = $_ENV['YOUTUBE_API_KEY'] ?? '';

        $url = "https://www.googleapis.com/youtube/v3/playlistItems?key=$ApiKey&part=snippet&maxResults=50&playlistId=$playlistId";

        if ($pageToken) {
            $url .= '&pageToken=' . $pageToken;
        }

        $contents = file_get_contents($url);

        if ($contents === false) {
            return [];
        }

        return json_decode($contents, true);
    }

    private function getData(string $playlistId): array
    {
        $nextPageToken = null;
        $items = [];

        do {
            $data = $this->getDataByPageToken($playlistId, $nextPageToken);
            $nextPageToken = JmesPath\search('nextPageToken', $data);
            $expression = 'items[].{videoId: snippet.resourceId.videoId, title: snippet.title, publishedAt: snippet.publishedAt, thumbnails: snippet.thumbnails}';
            $items = array_merge($items, JmesPath\search($expression, $data));
        } while ($nextPageToken);

        return $items;
    }
}
