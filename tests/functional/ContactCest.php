<?php

class ContactCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute(['/contact/index']);
    }

    public function openContactPage(\FunctionalTester $I)
    {
        $I->see('Kontakt', 'h1');
    }

}
