<?php

namespace app\widgets;

use yii\base\Widget;

class GoogleMaps extends Widget
{
    public float $latitude = 0.0;
    public float $longitude = 0.0;
    public string $text = '';
    public string $title = '';
    public int $zoom = 10;

    public function run(): string
    {
        return $this->render('googlemaps', array(
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'text' => $this->text,
            'title' => $this->title,
            'uniqid' => 'map' . uniqid(),
            'zoom' => $this->zoom,
        ));
    }

}
