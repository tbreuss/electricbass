<?php

namespace app\models;

final class YoutubePlaylist
{
    public static function findPlaylist(string $segment): array|false
    {
        $sql = 'SELECT * FROM youtube_playlist WHERE segment=:segment';

        $params = ['segment' => $segment];

        return \Yii::$app->db->createCommand($sql, $params)->queryOne();
    }

    public static function findPlaylists(): array
    {
        $sql = 'SELECT * FROM youtube_playlist ORDER BY title';

        return \Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function findPlaylistItems(int $playlistId): array
    {
        $sql = <<<SQL
            SELECT * 
            FROM youtube_playlist_item 
            WHERE youtube_playlist_id=:id 
            AND deleted IS NULL 
            ORDER BY title ASC
        SQL;

        $params = [
            'id' => $playlistId
        ];

        $playlistItems = \Yii::$app->db->createCommand($sql, $params)->queryAll();

        array_walk($playlistItems, function (array &$item) {
            $thumbnails = json_decode($item['thumbnails'], true);
            $key = !empty($thumbnails['high']) ? 'high' : (!empty($thumbnails['medium']) ? 'medium' : 'default');
            $item['thumbnail'] = [
                'url' => $thumbnails[$key]['url'] ?? '',
                'width' => $thumbnails[$key]['width'] ?? 0,
                'height' => $thumbnails[$key]['height'] ?? 0,
            ];
            unset($item['thumbnails']);
        });

        return $playlistItems;
    }

    public static function findPlaylistItem(int $playlistId, string $id): array|false
    {
        // see https://stackoverflow.com/a/13136645
        $sql = <<<SQL
            SELECT *
            FROM (
                SELECT @rownum := @rownum + 1 row, a.*
                FROM youtube_playlist_item AS a, (
                    SELECT @rownum := 0
                ) r
                WHERE deleted IS NULL
                AND youtube_playlist_id = :playlist_id
                ORDER BY title ASC, published DESC
            ) as article_with_rows
            WHERE id = :id
        SQL;

        $params = [
            'playlist_id' => $playlistId,
            'id' => $id
        ];

        return \Yii::$app->db->createCommand($sql, $params)->queryOne();
    }

    public static function findPrevNext(int $playlistId, int $row): array
    {
        $row = $row - 2;

        $sql = <<<SQL
            SELECT id
            FROM youtube_playlist_item
            WHERE deleted IS NULL
            AND youtube_playlist_id = :playlist_id
            ORDER BY title ASC, published DESC
            LIMIT :row, 3
        SQL;

        $params = [
            'playlist_id' => $playlistId,
            'row' => max($row, 0),
        ];

        $columns = \Yii::$app->db->createCommand($sql, $params)->queryColumn();

        if ($row < 0) {
            return [null, $columns[1]];
        }

        if (count($columns) < 3) {
            return [array_shift($columns), null];
        }

        return [array_shift($columns), array_pop($columns)];
    }
}
