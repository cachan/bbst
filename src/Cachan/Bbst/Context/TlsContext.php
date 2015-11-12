<?php
namespace Cachan\Bbst\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Cachan\Bbst\TestType\TlsTestType;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application Context from the specific context.
 */
class TlsContext implements Context, SnippetAcceptingContext
{
    /** @var  TlsTestType */
    private $tlsType;

    /**
     * @Given I am verifying HTTPS connections
     */
    public function iAmVerifyingHttpsConnections()
    {
        $this->tlsType = new TlsTestType();
        $this->tlsType->initialize();
    }

    /**
     * @When I make an HTTPS connection to :host
     */
    public function iMakeAnHttpsConnectionTo($host)
    {
        $this->tlsType->setHost($host);
        $this->tlsType->scan();
    }

    /**
     * @Then I should be able to connect with :compatLevel level compatibility
     */
    public function iShouldBeAbleToConnectWithCiphers($compatLevel)
    {
        $this->tlsType->analyze();
        $this->tlsType->meetsCompatibilityLevel($compatLevel);
    }

    /**
     * @Then The connection should be trusted
     */
    public function theConnectionShouldBeTrusted()
    {
        $this->tlsType->isTrustedConnection();
    }

    /**
     * @Then My connection is protected by perfect forward secrecy
     */
    public function protectedPerfectForwardSecrecy()
    {
        $this->tlsType->hasPerfectForwardSecrecy();
    }
}