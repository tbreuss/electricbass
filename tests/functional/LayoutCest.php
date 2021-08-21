<?php

class LayoutCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('sitemap/index');
    }

    public function openPage(\FunctionalTester $I)
    {
        // heading
        $I->see('Sitemap', 'h1.sitemap__title');

        $I->see('▲ nach oben');

        // footer
        $I->see('Über ELECTRICBASS', '.footer__title');
        $I->see('Kontakt & Info', '.footer__title');
        $I->see('Links & mehr', '.footer__title');

        $links = [
            'Impressum',
            'Kontakt',
            'Sitemap',
            'Facebook',
            'Twitter',
            'Bass-Glossar',
            'Bass-Websites',
            'Bassisten-Witze',
            'Zitate von Bassisten',
            'Kleinanzeigen',
            'Suche'
        ];

        foreach ($links as $link) {
            $I->see($link, '.footer__list');
        }

        // bottom
        $I->see('© 1998-2021 ELECTRICBASS');
        $I->see('Mit Herzblut, Yii2 und Bootstrap gemacht.');
    }

}
