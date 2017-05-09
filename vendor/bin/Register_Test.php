<?php
include_once("../../class.user.php"); 
class AddTests extends PHPUnit_Framework_TestCase
{
    private $reg;
 
    protected function setUp()
    {
        $this->reg = new USER();
    }
 
    protected function tearDown()
    {
        $this->reg = NULL;
    }
	
	public function testRegistration()
    {
        $result = $this->reg->register('hhhhhlllfflllf', 'sriharihhgggkkkfflllf', 'test1234');
		//$val = var_dump($result);
		//$this->assertNotNull(var_dump($result));
        
    }
	public function testLogin()
    {
        $result = $this->reg->doLogin('srihari1', 'srihari@gmail.com', 'test1234');
		
		 $this->assertEquals(1, $result,'Login successfully');
		
        
    }
	
	public function testFailedLogin()
    {
        $result = $this->reg->doLogin('srihari1wwww', 'srihariwwww@gmail.com', 'test1234');
		
		 $this->assertEquals(0, $result);
		
        
    }
}