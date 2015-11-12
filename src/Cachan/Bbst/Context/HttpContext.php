<?php
namespace Cachan\Bbst\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Cachan\Bbst\TestType\HttpTestType;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application Context from the specific context.
 */
class HttpContext implements Context, SnippetAcceptingContext
{
    /** @var  HttpTestType */
    private $httpType;

    public function __construct()
    {
    }

    /**
     * @Given I am in a web browser
     */
    public function iAmInAWebBrowser()
    {
        $this->httpType = new HttpTestType();
        $this->httpType->initialize();
    }

    /**
     * @When I make an HTTP GET request to :arg1
     */
    public function iMakeAnHttpRequestTo($arg1)
    {
        $this->httpType->makeRequest('GET', $arg1);
    }

    /**
     * @Then I should be redirected to :arg1
     */
    public function iShouldBeRedirectedTo($arg1)
    {
        if (!$this->httpType->isRedirectMatch($arg1)) {
            throw new \Exception("Expected $arg1 does not match " . $this->httpType->getResponse()->getEffectiveUrl());
        }
    }

    /**
     * @Given I have an :type referer
     */
    public function iHaveAnExternalReferer($type)
    {
        if ('internal' === $type) {
            $this->httpType->setInternalReferer();
        }
        else if ('external' === $type) {
            $this->httpType->setExternalReferer();
        }
        else {
            throw new \Exception("Unknown referer type $type.");
        }

    }

    /**
     * @Then I should see the text :text
     */
    public function iShouldSeeTheText($text)
    {
        if (false === $this->httpType->textExistsInResponseBody($text)) {
            throw new \Exception("Expected text '$text' does not appear in the response'");
        }
    }

    /**
     * @Then I should receive response code :statusCode
     */
    public function iShouldReceiveReponseCode($statusCode)
    {
        $actualCode = (int)$this->httpType->getStatusCode();

        if ((int)$statusCode !== $actualCode) {
            throw new \Exception("Expected status code $statusCode does not match $actualCode.");
        }
    }

    /**
     * @Then I should receive a file with MIME type :contentType
     */
    public function iShouldReceiveAFileWithMimeType($contentType)
    {
        $actualType = $this->httpType->getContentType();

        if ($contentType !== $actualType) {
            throw new \Exception("Expected Content-Type $contentType does not match $actualType");
        }
    }

}