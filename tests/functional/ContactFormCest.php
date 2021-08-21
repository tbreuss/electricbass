<?php

class ContactFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/contact']);
    }

    public function openContactPage(\FunctionalTester $I)
    {
        $I->see('Kontakt', 'h1');
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->submitForm('#form', []);
        $I->expectTo('see that fields are wrong');
        $I->see('Kontakt', 'h1');
        $I->see('Betreff darf nicht leer sein', '.invalid-feedback');
        $I->see('Nachricht darf nicht leer sein', '.invalid-feedback');
        $I->see('Name darf nicht leer sein', '.invalid-feedback');
        $I->see('E-Mail darf nicht leer sein', '.invalid-feedback');
        $I->see('Der Prüfcode ist falsch', '.invalid-feedback');
    }

    public function submitFormWithIncorrectEmail(\FunctionalTester $I)
    {
        $I->submitForm('#form', [
            'ContactForm[subject]' => 'tester subject',
            'ContactForm[body]' => 'test body',
            'ContactForm[name]' => 'tester name',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->dontSee('Name darf nicht leer sein', '.invalid-feedback');
        $I->see('E-Mail ist keine gültige E-Mail-Adresse', '.invalid-feedback');
        $I->dontSee('Subject darf nicht leer sein', '.invalid-feedback');
        $I->dontSee('Body darf nicht leer sein', '.invalid-feedback');
        $I->dontSee('Der Prüfcode ist falsch', '.invalid-feedback');
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#form', [
            'ContactForm[subject]' => 'tester subject',
            'ContactForm[body]' => 'test body',
            'ContactForm[name]' => 'tester name',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeEmailIsSent();
        $I->seeElement('#form');
        $I->see('Danke für deine Nachricht. Ich melde mich möglichst rasch bei dir.');
    }
}
