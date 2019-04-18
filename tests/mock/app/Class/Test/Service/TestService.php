<?php


namespace Test\Service;

use DG24\StdTrait\PropertyOptionStdTrait;
use DG24\StdTrait\PropertyLastErrorStdTrait;

class TestService{

    use PropertyOptionStdTrait, PropertyLastErrorStdTrait;

    /**
     * 允许PropertyOptionStdTrait读写
     * @var string
     */
    protected $opt2randString = "";

    /**
     * 允许PropertyOptionStdTrait写，但不允许PropertyOptionStdTrait读
     * @var string
     */
    protected $opt2_debug = false;

    const ERRORCODE_TEST = -2;

    const ERROR_TEST = "ERROR_FOR_TEST_TestService";

    public function getOptDebug(){
        return $this->opt2_debug;
    }

}
