<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class ConnexionContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given The connexion page
     */
    public function theConnexionPage(): void
    {
        throw new PendingException();
    }

    /**
     * @Given Enter credentials on connexion form
     */
    public function enterCredentialsOnConnexionForm()
    {
        throw new PendingException();
    }

    /**
     * @Given Click on connexion button
     */
    public function clickOnConnexionButton()
    {
        throw new PendingException();
    }

    /**
     * @Then Go to profil page
     */
    public function goToProfilPage(): void
    {
        throw new PendingException();
    }

}
