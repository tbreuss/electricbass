<?php

class ImpressumCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/impressum');
    }

    public function openImpressumPage(\FunctionalTester $I)
    {
        $I->see('Impressum', 'h1');
    }

}
