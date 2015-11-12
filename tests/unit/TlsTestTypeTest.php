<?php


class TlsTestTypeTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testSetHost()
    {
        $tls = new \Cachan\Bbst\TestType\TlsTestType();
        $tls->setHost('testHost');
        $this->assertEquals('testHost', $tls->getHost());
    }

}