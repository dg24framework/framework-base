<?php

namespace DG24\SolutionBare;

use DG24\Loader;

class AppTest extends \PHPUnit_Framework_TestCase{

    protected $app;

    protected function setUp(){
        parent::setUp();
        $this->app = Loader::$app;
    }


    public function testGetInstance(){
        $str = 'testGetInstance';
        $obj = $this->app->getInstance('Test\Example\Example');
        $obj->setA($str);
        unset($obj);
        $this->assertEquals($str, $this->app->getInstance('Test\Example\Example')->getA());
    }

    public function testGetAliasInstance(){
        $str = 'testGetAliasInstance';
        $obj = $this->app->aliasInstance->get("TestExampleSingle");
        $obj->setA($str);
        unset($obj);
        $this->assertEquals($str, $this->app->aliasInstance->TestExampleSingle->getA());
    }

    public function testGetAliasInstanceParamNewInstance(){
        $str = 'testGetAliasInstanceParamNewInstance';
        $obj = $this->app->aliasInstance->create('TestExampleSingle');
        $obj->setA($str);
        $this->assertNotEquals($str, $this->app->aliasInstance->TestExampleSingle->getA());
    }

    public function testGetAliasInstanceException(){
        try{
            $this->app->aliasInstance->sadfrtyjhdthasdfsdatsrgjajhfgpaosof;
        }catch(\InvalidArgumentException $expected){
            $this->assertEquals("InvalidArgumentException", get_class($expected));
            return ;
        }
        $this->fail(__METHOD__. ' exception has not been raised.');
    }


    public function testGetAliasInstanceUsingFunc(){
        $str = 'TestExampleFunc';
        $this->assertEquals($str, $this->app->aliasInstance->{$str}->getA());
    }

    public function testGetAliasInstanceUsingFuncWithNewInstance(){
        $str = 'testGetAliasInstanceUsingFuncWithNewInstance';
        $obj = $this->app->aliasInstance->create('TestExampleFunc');
        $obj->setA($str);
        unset($obj);
        $this->assertNotEquals($str, $this->app->aliasInstance->TestExampleFunc->getA());
    }



}
