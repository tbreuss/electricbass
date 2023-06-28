<?php

namespace app\widgets;

use app\models\YoutubePlaylist;
use yii\base\Widget;

final class YoutubePlaylistMenu extends Widget
{
    public function run(): string
    {
        $playlists = YoutubePlaylist::findPlaylists();
        return $this->render('youtube_playlist_menu', [
            'playlists' => $playlists,
        ]);
    }
}
