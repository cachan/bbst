<?php

namespace Cachan\Bbst\TestType;


abstract class AbstractTestType {
    public abstract function initialize();
    public abstract function tearDown();
}