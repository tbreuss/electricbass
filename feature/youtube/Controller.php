<?php

namespace app\feature\youtube;

use app\feature\youtube\models\YoutubePlaylist;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(string $segment): string
    {
        $playlist = YoutubePlaylist::findPlaylist($segment);

        if ($playlist === false) {
            throw new GoneHttpException();
        }

        $playlistItems = YoutubePlaylist::findPlaylistItems($playlist['id']);

        return $this->render('@app/feature/youtube/views/index', [
            'playlist' => $playlist,
            'playlistItems' => $playlistItems
        ]);
    }

    public function actionView(string $segment, string $id): string
    {
        $playlist = YoutubePlaylist::findPlaylist($segment);

        if ($playlist === false) {
            throw new GoneHttpException();
        }

        $playlistItem = YoutubePlaylist::findPlaylistItem($playlist['id'], $id);

        if ($playlistItem === false) {
            throw new GoneHttpException();
        }

        $this->layout = 'empty';

        [$prevId, $nextId] = YoutubePlaylist::findPrevNext($playlist['id'], $playlistItem['row']);

        return $this->render('@app/feature/youtube/views/view', [
            'playlist' => $playlist,
            'playlistItem' => $playlistItem,
            'prevId' => $prevId,
            'nextId' => $nextId,
        ]);
    }
}
