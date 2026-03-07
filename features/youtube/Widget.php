<?php

namespace app\features\youtube;

use app\features\youtube\models\YoutubePlaylist;

final class Widget extends \yii\base\Widget
{
    public function run(): string
    {
        $playlists = YoutubePlaylist::findPlaylists();
        return $this->render('widget', [
            'playlists' => $playlists,
        ]);
    }
}
