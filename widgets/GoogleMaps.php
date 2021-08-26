<?php

namespace app\widgets;

use yii\base\Widget;

final class GoogleMaps extends Widget
{
    public ?float $latitude = null;
    public ?float $longitude = null;
    public string $text = '';
    public string $title = '';
    public int $zoom = 10;

    public function run(): string
    {
        return $this->render('googlemaps', [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'text' => $this->text,
            'title' => $this->title,
            'uniqid' => 'map' . uniqid(),
            'zoom' => $this->zoom,
        ]);
    }
}
