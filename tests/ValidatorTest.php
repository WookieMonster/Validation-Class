<?php

use WookieMonster\Validator\Validator;

class ImageResizerTest extends PHPUnit_Framework_Testcase {

	protected $validator;

	public function setUp()
	{
		$this->validator = new Validator;
	}
	
	public function tearDown()
	{
		unset($this->validator);
	}

	// Core tests
	// ----------------------------------------------------------------------

	public function testCreatesInstanceOfValidator()
	{
		$this->assertInstanceOf('WookieMonster\Validator\Validator', $this->validator);
	}

	public function testSetRuleSetsKeyAndValues()
	{
		$this->validator->setRule('email', 'e-mail', array('required'));
		$this->assertArrayHasKey('email', $this->validator->getRules());
		$this->assertEquals('e-mail', $this->validator->getRules()['email']['name']);
		$this->assertEquals(array('required'), $this->validator->getRules()['email']['rules']);
	}

	public function testSetFieldSetsKeyAndValue()
	{
		$this->validator->setField('name', 'Joe Bloggs');
		$this->assertArrayHasKey('name', $this->validator->getFields());
		$this->assertEquals('Joe Bloggs', $this->validator->getFields()['name']);
	}

	/**
     * @expectedException WookieMonster\Validator\Exception\RulesFieldsMismatchException
     */
	public function testMismatchedFields()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('badvalue', 'test');
		$this->validator->run();
	}

	public function testGetAllErrors()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('name', '');
		$this->validator->run();
		$this->assertInternalType('string', $this->validator->getAllErrors());

	}

	public function testGetAllFirstErrors()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('name', '');
		$this->validator->run();
		$this->assertInternalType('string', $this->validator->getAllFirstErrors());
	}

	public function testGetFirstFieldError()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('name', '');
		$this->validator->run();
		$this->assertInternalType('string', $this->validator->getAllFirstErrors('name'));
	}

	public function testGetFirstFieldErrors()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('name', '');
		$this->validator->run();
		$this->assertInternalType('string', $this->validator->getAllFirstErrors('name'));
	}

	public function testRepopulateReturnsNonZeroLengthStringOnFail()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('name', '');
		$this->validator->run();
		$this->greaterThan(0, strlen($this->validator->repopulate('name')));
	}

	// Rules tests
	// ----------------------------------------------------------------------

	public function testBetweenPasses()
	{
		$this->validator->setRule('id', 'id', array('between' => array(1, 10)));
		$this->validator->setField('id', 5);
		$this->assertTrue($this->validator->run());
	}

	public function testBetweenFails()
	{
		$this->validator->setRule('id', 'id', array('between' => array(1, 10)));
		$this->validator->setField('id', 13);
		$this->assertFalse($this->validator->run());
	}

	public function testRequiredPasses()
	{
		$this->validator->setRule('name', 'Name', array('required'));
		$this->validator->setField('name', 'test');
		$this->assertTrue($this->validator->run());
	}

	public function testRequiredFails()
	{
		$this->validator->setRule('name', 'Name',array('required'));
		$this->validator->setField('name', '');
		$this->assertFalse($this->validator->run());
	}

	public function testEmailPasses()
	{
		$this->validator->setRule('email', 'e-mail', array('email'));
		$this->validator->setField('email', 'test@test.com');
		$this->assertTrue($this->validator->run());
	}

	public function testEmailFails()
	{
		$this->validator->setRule('email', 'e-mail', array('email'));
		$this->validator->setField('email', 'invalid_email');
		$this->assertFalse($this->validator->run());
	}

	public function testIpPasses()
	{
		$this->validator->setRule('ip', 'ip address', array('ip'));
		$this->validator->setField('ip', '127.0.0.1');
		$this->assertTrue($this->validator->run());
	}

	public function testIpFails()
	{
		$this->validator->setRule('ip', 'ip address', array('ip'));
		$this->validator->setField('ip', 'invalid_url');
		$this->assertFalse($this->validator->run());
	}

	public function testMaxLengthPasses()
	{
		$this->validator->setRule('name', 'name', array('maxLength' => 10));
		$this->validator->setField('name', 'Tim');
		$this->assertTrue($this->validator->run());
	}

	public function testMaxLengthFails()
	{
		$this->validator->setRule('name', 'name', array('maxLength' => 10));
		$this->validator->setField('name', 'Wolfe­schlegel­stein­hausen­berger­dorff');
		$this->assertTrue($this->validator->run());
	}

	public function testMinLengthPasses()
	{
		$this->validator->setRule('name', 'name', array('minLength' => 3));
		$this->validator->setField('name', 'John');
		$this->assertTrue($this->validator->run());
	}

	public function testMinLengthFails()
	{
		$this->validator->setRule('name', 'name', array('minLength' => 3));
		$this->validator->setField('name', 'Jo');
		$this->assertTrue($this->validator->run());
	}

	public function testAlphaDashPass()
	{
		$this->validator->setRule('name', 'name', array('alphaDash'));
		$this->validator->setField('name', '-_aBcD-ds_ads-');
		$this->assertTrue($this->validator->run());
	}

	public function testAlphaDashFails()
	{
		$this->validator->setRule('name', 'name', array('alphaDash'));
		$this->validator->setField('name', '^&*');
		$this->assertFalse($this->validator->run());
	}

	public function testDatePasses()
	{
		$this->validator->setRule('date', 'date', array('date'));
		$this->validator->setField('date', '08/09/2012');
		$this->assertTrue($this->validator->run());
	}

	public function testDateFails()
	{
		$this->validator->setRule('date', 'date', array('date'));
		$this->validator->setField('date', '^&*');
		$this->assertFalse($this->validator->run());
	}
}