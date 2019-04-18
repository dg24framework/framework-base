<?php

namespace DG24\DI;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase{
    
    protected function setUp(){
        parent::setUp();
    }
    
    public function testReg(){
        $diLocator = new ServiceLocator();
        $diLocator->reg('test', function($name){
            return new \Exception("a");
        });
        $this->assertTrue($diLocator->has("test"));
        $this->assertFalse($diLocator->has("test", true));
        $this->assertFalse($diLocator->has("testNotExist"));
        
        $diLocator->reg('test', null);
        $this->assertFalse(isset($diLocator->test));
        
        
    }
    
    public function testRegBadCode(){
        $diLocator = new ServiceLocator();
        try{
            $diLocator->reg('test', array('asdf'));
        }catch(\InvalidArgumentException $e){
            $this->assertEquals("InvalidArgumentException", get_class($e));
            return ;
        }
        $this->fail(__METHOD__. " NOT THROW Exception");
    }
    
    public function testRegBadCodeButThrowInRuntime(){
        $diLocator = new ServiceLocator();
        try{
            $diLocator->reg('test', function($name){
                return array('asdf');
            });
            $diLocator->get('test');
        }catch(\RuntimeException $e){
            $this->assertEquals("RuntimeException", get_class($e));
            return ;
        }
        $this->fail(__METHOD__. " NOT THROW Exception");
    }
    
    public function testRegBatch(){
        $diLocator = new ServiceLocator(array(
            'testA' => function($name){
                return new \Exception('testA');
            },
            'testB' => function($name){
                return new \Exception('testB');
            },
        ));
        
        $this->assertTrue($diLocator->has("testA"));
        $this->assertFalse($diLocator->has("testA", true));
        
        $this->assertTrue(isset($diLocator->testB));
        $this->assertFalse($diLocator->has("testB", true));
        
        $this->assertFalse(isset($diLocator->testNotExist));
    
    }
    
    
    public function testCreate(){
        $diLocator = new ServiceLocator();
        $diLocator->reg('test', new \StdClass());
        
        $this->assertNotSame($diLocator->create("test"), $diLocator->get("test"));
        $this->assertNotSame($diLocator->create("test"), $diLocator->create("test"));
        
        $this->assertSame($diLocator->get("test"), $diLocator->get("test"));
    
    }
    
    public function testCreateObjectButTheSame(){
        $diLocator = new ServiceLocator();
        $diLocator->reg('test', new \StdClass());
        
        $this->assertSame($diLocator->create("test", false), $diLocator->get("test"));
    
    }
    
    
    public function testGet(){
        $diLocator = new ServiceLocator();
        $diLocator->reg('test', function($name){
            return new \StdClass();
        });
    
        $this->assertSame($diLocator->test, $diLocator->get("test"));
    
    }

}
