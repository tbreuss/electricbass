<?php

namespace app\controllers;

use app\models\YoutubePlaylist;
use yii\web\Controller;
use yii\web\GoneHttpException;

final class YoutubePlaylistController extends Controller
{
    public function actionIndex(string $segment): string
    {
        $playlist = YoutubePlaylist::findPlaylist($segment);

        if ($playlist === false) {
            throw new GoneHttpException();
        }

        $playlistItems = YoutubePlaylist::findPlaylistItems($playlist['id']);

        return $this->render('index', [
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

        return $this->render('view', [
            'playlist' => $playlist,
            'playlistItem' => $playlistItem,
            'prevId' => $prevId,
            'nextId' => $nextId,
        ]);
    }
}
