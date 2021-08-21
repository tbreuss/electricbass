<?php

class ContactCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/contact');
    }

    public function openContactPage(\FunctionalTester $I)
    {
        $I->see('Kontakt', 'h1');
    }

}
