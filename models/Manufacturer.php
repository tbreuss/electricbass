<?php

namespace app\models;

use app\helpers\Url;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Manufacturer extends ActiveRecord
{
    public function getSortimentForGeneratedText(): array
    {
        $sortiment = [];
        if ($this->guitars) {
            $sortiment[] = 'Bass-Gitarren';
        }
        if ($this->pickups) {
            $sortiment[] = 'Bass-Tonabnehmer';
        }
        if ($this->strings) {
            $sortiment[] = 'Bass-Saiten';
        }
        if ($this->amps) {
            $sortiment[] = 'Bass-Verstärker und -Lautsprecher';
        }
        if ($this->gear) {
            $sortiment[] = 'Zubehör für Bassisten';
        }
        return $sortiment;
    }

    public function getSortiment(): array
    {
        $sortiment = [];
        if ($this->guitars) {
            $sortiment[] = 'Instrumente';
        }
        if ($this->pickups) {
            $sortiment[] = 'Pickups';
        }
        if ($this->amps) {
            $sortiment[] = 'Verstärker';
            $sortiment[] = 'Lautsprecher';
        }
        if ($this->strings) {
            $sortiment[] = 'Saiten';
        }
        if ($this->gear) {
            $sortiment[] = 'Zubehör';
        }
        return $sortiment;
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'countryCode']);
    }

    public static function allToArray($models)
    {
        return ArrayHelper::toArray($models, [
            'app\models\Manufacturer' => [
                'id',
                'name',
                'website',
                'wikipedia',
                'country',
                'guitars',
                'pickups',
                'strings',
                'amps',
                'gear',
                'assortment' => function($m) {
                    return $m->getSortiment();
                },
                'visits',
                'clicks',
                'visitUrl' => function ($m) {
                    return strlen($m->website) === 0 ? '' : Url::to(['visit', 'id' => $m->id]);
                },
                'text' => function($m) {
                    $text = '';
                    if ($m->country) {
                        $text .= sprintf('Das Unternehmen %s ist ein Hersteller aus %s. ', $m->name, $m->country->name);
                    } else {
                        $text .= sprintf('Das Unternehmen %s ist ein Hersteller. ', $m->name);
                    }
                    if ($m->founded > 0) {
                        $text .= sprintf('Die Firma wurde im Jahr %d gegründet. ', $m->founded);
                    }
                    $text .= sprintf('Zum Produktsortiment von %s gehören unter anderem ', $m->name);
                    $text .= join(', ', $m->getSortimentForGeneratedText());
                    $text .= '. ';
                    if ($m->discontinued > 0) {
                        $text .= sprintf('Im Jahr %d wurde das Unternehmen aufgelöst. ', $m->discontinued);
                    }
                    return $text;
                }
            ],
        ]);
    }
}
