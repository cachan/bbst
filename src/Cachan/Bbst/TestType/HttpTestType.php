<?php

namespace Cachan\Bbst\TestType;

use Codeception\Lib\Connector\Guzzle;
use GuzzleHttp;
use Guzzle\Http\Client;
use Guzzle\Plugin\History;

class HttpTestType extends AbstractTestType {

    /** @var  \Guzzle\Http\Client */
    private $httpClient;
    /** @var  \Guzzle\Http\Message\Request */
    private $request;
    /** @var  \Guzzle\Plugin\History\HistoryPlugin */
    private $history;
    /** @var  \Guzzle\Http\Message\Response */
    private $response;
    /** @var  array */
    private $headers;

    public function initialize()
    {
        $this->httpClient = new Client();
        $this->history = new History\HistoryPlugin();
        $this->httpClient->addSubscriber($this->history);
    }

    public function tearDown()
    {

    }

    /**
     * @param $type
     * @param $uri
     *
     * @return $this
     */
    public function makeRequest($type, $uri)
    {
        if ('GET' === $type) {
            $this->request = $this->httpClient->get($uri);
        }

        $this->setRequestHeaders();
        $this->response = $this->request->send();

        return $this;
    }

    /**
     * @return GuzzleHttp\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->request->getResponse();
    }

    /**
     * Check if the redirects match.
     *
     * @param $redirects
     *
     * @return bool
     */
    public function isRedirectMatch($redirects)
    {
        $expectedRedirects = $this->parseExpectedRedirects($redirects);
        $actualRedirects = array();

        foreach ($this->history->getAll() as $transaction) {
            $actualRedirects[] = $transaction['response']->getEffectiveUrl();
        }
        // The first response is a response. Remove it.
        array_shift($actualRedirects);

        if (array() === array_diff($expectedRedirects,$actualRedirects) &&
                array() === array_diff($actualRedirects, $expectedRedirects)) {
            return true;
        }

        return false;
    }

    /**
     * Parse the expected results.
     *
     * @param $redirectsString
     *
     * @return array
     */
    public function parseExpectedRedirects($redirectsString)
    {
        return explode(' ', $redirectsString);
    }

    public function setReferer($referer)
    {
        $this->headers['Referer'] = $referer;

        return $this;
    }

    public function setInternalReferer()
    {
        $this->setReferer('http://localhost');
    }

    public function setExternalReferer()
    {
        $this->setReferer('http://example.com');
    }

    /**
     *
     */
    public function setRequestHeaders()
    {
        if (count($this->headers) > 0) {
            foreach ($this->headers as $name => $value) {
                $this->request->setHeader($name, $value);
            }
        }
    }

    /**
     * @param $expected
     *
     * @return bool
     */
    public function textExistsInResponseBody($expected)
    {
        $pos = strpos($this->getResponse()->getBody(), $expected);

        if (false === $pos) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Get the response body.
     *
     * @return GuzzleHttp\Stream\StreamInterface|null
     */
    public function getResponseBody()
    {
        return $this->getResponse()->getBody();
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->getResponse()->getStatusCode();
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return (string)$this->getResponse()->getHeader('Content-Type');
    }
}