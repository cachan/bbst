<?php

namespace Cachan\Bbst\TestType;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TlsTestType extends AbstractTestType {
    /** @var  string */
    private $host;
    /** @var  string */
    private $scanOutput;
    private $results;
    /** @var  string */
    private $analysis;

    public function initialize()
    {

    }

    public function tearDown()
    {

    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function scan()
    {
        $process = new Process('vendor/jvehent/cipherscan/cipherscan --json ' . $this->host);
        $process->setTimeout(600);

        try {
            $process->mustRun();

            $this->results = json_decode($process->getOutput());
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }
    }

    public function getScanResult()
    {
        foreach ($this->results->ciphersuite as $cipher) {

        }
    }

    public function isTrustedConnection()
    {
        if (!$this->iterateScanResultsCipher('trusted', 'False')) {
            throw new \Exception("Certificate untrusted");
        }
    }

    public function hasPerfectForwardSecrecy()
    {
        $pfs = false;

        foreach ($this->results->ciphersuite as $cipher) {
            if ("None" !== $cipher->pfs && false === $pfs) {
                $pfs = true;
            }
        }

        if (false === $pfs) {
            throw new \Exception("Perfect forward secrecy unavailable");
        }
    }

    public function iterateScanResultsCipher($attr, $value)
    {
        foreach ($this->results->ciphersuite as $cipher) {
            if ($value === $cipher->$attr) {
                return false;
            }
        }
    }

    public function analyze()
    {
        $process = new Process('vendor/jvehent/cipherscan/analyze.py -t ' . $this->host);
        $process->setTimeout(600);

        try {
            $process->mustRun();

            $this->analysis = $process->getOutput();
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }
    }

    public function parseAnalysis()
    {
        echo $this->analysis;
    }

    public function meetsCompatibilityLevel($level)
    {
        $recs = $this->getAnalysisItems();

        if ('old' === $level) {
            echo "Recommended " . $recs['oldActions'] . "\n\n";
        }

        if ('intermediate' === $level) {
            echo "Recommended " . $recs['intermediateActions'] . "\n\n";
        }

        if ('modern' === $level) {
            echo "Recommended " . $recs['modernActions'] . "\n\n";
        }

        if ('all' === $level) {
            echo "Recommended " . $recs['oldActions'] . "\n\n";
            echo "Recommended " . $recs['intermediateActions'] . "\n\n";
            echo "Recommended " . $recs['modernActions'] . "\n\n";
        }

        echo "For recommended server settings, visit https://wiki.mozilla.org/Security/Server_Side_TLS\n\n";

        if (strpos($this->analysis, $this->host . ":443 has bad ssl/tls", 0) !== false) {
            throw new \Exception("There is a problem with the SSL/TLS implementation:\n" . $recs['badActions']);
        }
    }

    public function getAnalysisItems()
    {
        $recommendations = array('bad', 'badActions', 'oldActions', 'intermediateActions', 'modernActions');
        $items = explode("\n\n", $this->analysis);

        foreach ($items as $item) {
            if ($this->host . ":443 has bad ssl/tls" === $item) {
                $recommendations['bad'] = $item;
            }

            if (false !== strpos($item, "Things that are bad")) {
                $recommendations['badActions'] = $item;
            }

            if (false !== strpos($item, "Changes needed to match the old level:")) {
                $recommendations['oldActions'] = $item;
            }

            if (false !== strpos($item, "Changes needed to match the intermediate level:")) {
                $recommendations['intermediateActions'] = $item;
            }

            if (false !== strpos($item, "Changes needed to match the modern level:")) {
                $recommendations['modernActions'] = $item;
            }
        }

        return $recommendations;
    }
}