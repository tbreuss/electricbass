<?php

class SearchCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('search/index');
    }

    public function openSearchPage(\FunctionalTester $I)
    {
        $I->see('Suche', 'h1');
    }

}
