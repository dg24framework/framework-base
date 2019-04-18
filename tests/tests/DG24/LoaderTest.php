<?php

namespace DG24;
use DG24;
use PHPUnit_Framework_TestCase;

class LoaderTest extends PHPUnit_Framework_TestCase{

    protected function setUp(){
        parent::setUp();
    }

    public function testInit(){
        $this->assertEquals('PhpunitTest', D_ENV);
        $this->assertEquals(1, D_DEBUG);
    }

    public function testLoadClass(){
        $classExampleSys = '\DG24\Error\CommonError';
        new $classExampleSys();
        $this->assertEquals(true, class_exists($classExampleSys, false));
    }

    public function testLoadClassFromApp(){
        $classExampleApp = "Test\Example\ExampleForLoadtest";
        Loader::loadClass($classExampleApp);
        $this->assertEquals(true, class_exists($classExampleApp, false));
    }

    public function testLoadClassFromAppUnderscore(){
        $classExampleApp = "Test\Example\Underscore_A";
        Loader::loadClass($classExampleApp);
        $this->assertEquals(true, class_exists($classExampleApp, false));
    }

    public function testLoadClassFromAppUnderscorePSR4(){
        $classExampleApp = "Test\Example\Underscore_B";
        Loader::loadClass($classExampleApp);
        $this->assertEquals(true, class_exists($classExampleApp, false));
    }

    public function testLoadClassNotExist(){
        $classExampleApp = "Vendor_name\SubNamespaceNames\Not_exist_class";
        Loader::loadClass($classExampleApp);
        $this->assertFalse(class_exists($classExampleApp, false));
    }

    //===============loader test path begin=====================

    public function testGetLoaderPath(){
      $path = Loader::getLoaderPath();
      //由于phpunit测试时引入框架的初始化文件phpunit.php采用了\DG24\Loader::AUTOLOAD_ALL，故应同时存在两个变量
      $this->assertArrayHasKey("app", $path);
      $this->assertArrayHasKey("framework", $path);
    }

    public function testSetLoaderPath(){
      Loader::setLoaderPath("testAppOther", D_APP_DIR. '/../app-other/Class');
      $path = Loader::getLoaderPath();
      $this->assertArrayHasKey("testAppOther", $path);
      $this->assertTrue(class_exists("AppOther\Service\TestA", true));

      Loader::delLoaderPath("testAppOther");
      $path = Loader::getLoaderPath();
      $this->assertArrayNotHasKey("testAppOther", $path);
      $this->assertFalse(class_exists("AppOther\Service\TestB", true));
    }

    //===============loader test path end=====================

    //===============config test begin=====================
    public function testConfigGet(){
        $this->assertEquals('index/index/index', Loader::config('Base', 'defaultRoute'));
    }

    public function testConfigGetAll(){
        $cfg = Loader::config('Base');
        $this->assertEquals('index/index/index', $cfg['defaultRoute']);
    }

    public function testConfigGetComplex(){
        $this->assertEquals('abc', Loader::config('Base', 'test1>test2'));
    }

    public function testConfigGetDefault(){
        $this->assertEquals('default', Loader::config('Base', 'a', 'default'));
    }


    public function testConfigGetComplexDefault(){
        $this->assertEquals('defa1', Loader::config('Base', 'test1>test2>test3', 'defa1'));
    }

    public function testConfigGetComplexUsingFunc(){
        $this->assertEquals('11111111111111111', Loader::config('FuncTest', 'testconfig'));
    }

    public function testConfigGetInSubFolder(){
        $this->assertEquals('help', Loader::config('Test/Lang', 'help'));
    }

    public function testConfigSet(){
        $cfg = Loader::config('Base');
        Loader::configSet('Base', array('needUserLogin'=>true));
        $this->assertTrue(Loader::config('Base', 'needUserLogin'));
    }

    public function testConfigSetReserved(){
        Loader::configSet('newSource2', array('test'=>true));
        $this->assertTrue(Loader::config('newSource2', 'test'));
    }

    public function testConfigInSubFolder(){
        Loader::configSet('Test/Lang', array('needUserLogin'=>true));
        $this->assertTrue(Loader::config('Test/Lang', 'needUserLogin'));
    }

    //===============config test end================================
}
