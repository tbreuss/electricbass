<?php

namespace app\features\youtube;

use app\features\youtube\models\YoutubePlaylist;
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
