<?php

namespace DG24\StdTrait;

use Test\Service\TestService;

class PropertyOptionStdTraitTest extends \PHPUnit_Framework_TestCase{

    protected $testInstance;

    protected function setUp(){
        parent::setUp();
        $this->testInstance = new TestService();
    }

    public function testGetOption(){
        $val = $this->testInstance->getOption("randString");
        $this->assertEquals("", $val);
    }

    public function testSetOption(){
        $val = $this->testInstance->getOption("randString");
        $this->assertEquals("", $val);

        $val2 = "x____". mt_rand();
        $this->testInstance->setOption("randString", $val2);

        $val3 = $this->testInstance->getOption("randString");
        $this->assertEquals($val2, $val3);

        $val4 = "y____". mt_rand();
        $this->testInstance->setOption(array("randString" =>  $val4));
        $val5 = $this->testInstance->getOption("randString");
        $this->assertEquals($val4, $val5);

    }


    public function testSetOptionNotexist(){

        $this->testInstance->setOption("notexis111111", 1);

        $val3 = $this->testInstance->getOption("notexis111111");
        $this->assertNull($val3);

        $this->testInstance->setOption(array("notexis111111" =>  2222222));
        $val5 = $this->testInstance->getOption("notexis111111");
        $this->assertNull($val5);

    }


    public function testGetOptionPrivate(){

        $this->assertFalse($this->testInstance->getOptDebug());
        $this->assertNull($this->testInstance->getOption("_debug"));

    }



    public function testSetOptionPrivate(){

        $this->testInstance->setOption("_debug", true);

        $this->assertTrue($this->testInstance->getOptDebug());
        $this->assertNull($this->testInstance->getOption("_debug"));


    }

}
