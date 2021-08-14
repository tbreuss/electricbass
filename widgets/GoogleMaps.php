<?php

namespace app\widgets;

use yii\base\Widget;

class GoogleMaps extends Widget
{
    public $latitude = '';
    public $longitude = '';
    public $text = '';
    public $title = '';
    public $zoom = 10;

    public function run()
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
