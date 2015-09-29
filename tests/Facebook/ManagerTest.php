<?php

namespace Test\Demo\Facebook;

use Demo\Facebook\Manager as FacebookManager;
use Demo\User\Data as UserData;
use Demo\User\Session\Data as SessionData;
use Facebook\GraphUser;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	const USER_ID = 1;
	const EMAIL   = 'name@email.com';
	const PICTURE = '/picture/url';

	/**
	 * @var FacebookManager
	 */
	private $object;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $dao;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $userManager;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $sessionHandler;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $facebookApi;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $passwordGenerator;

	protected function setUp()
	{
		parent::setUp();

		$this->dao               = $this
			->getMockBuilder('\Demo\Facebook\Dao')
			->disableOriginalConstructor()
			->getMock();

		$this->userManager       = $this
			->getMockBuilder('\Demo\User\Manager')
			->disableOriginalConstructor()
			->getMock();

		$this->sessionHandler    = $this
			->getMockBuilder('\Demo\User\Session\Handler')
			->disableOriginalConstructor()
			->getMock();

		$this->facebookApi       = $this
			->getMockBuilder('\Demo\Facebook\Api')
			->disableOriginalConstructor()
			->getMock();

		$this->passwordGenerator = $this
			->getMockBuilder('\Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator')
			->disableOriginalConstructor()
			->getMock();

		$this->object = new FacebookManager(
			$this->dao,
			$this->userManager,
			$this->sessionHandler,
			$this->facebookApi,
			$this->passwordGenerator
		);
	}

	public function testProcessWithNewUser()
	{
		$this->facebookApi
			->expects($this->once())
			->method('getAccessToken');

		$this->facebookApi
			->expects($this->once())
			->method('getProfile')
			->willReturn($this->createFacebookProfileData());

		$this->sessionHandler
			->expects($this->once())
			->method('getData')
			->willReturn(null);

		$this->userManager
			->expects($this->once())
			->method('getByEmail')
			->willReturn(null);

		$this->passwordGenerator
			->expects($this->once())
			->method('generatePassword');

		$this->userManager
			->expects($this->once())
			->method('register')
			->willReturn(self::USER_ID);

		$this->userManager
			->expects($this->once())
			->method('loginCallback');

		$this->dao
			->expects($this->once())
			->method('save');

		$actual = $this->object->process();

		$this->assertEquals(self::USER_ID, $actual);
	}

	public function testProcessWithRegisteredUser()
	{
		$this->facebookApi
			->expects($this->once())
			->method('getAccessToken');

		$this->facebookApi
			->expects($this->once())
			->method('getProfile')
			->willReturn($this->createFacebookProfileData());

		$this->sessionHandler
			->expects($this->once())
			->method('getData')
			->willReturn(null);

		$this->userManager
			->expects($this->once())
			->method('getByEmail')
			->willReturn($this->createUserData());

		$this->userManager
			->expects($this->once())
			->method('loginCallback');

		$this->userManager
			->expects($this->never())
			->method('register');

		$this->passwordGenerator
			->expects($this->never())
			->method('generatePassword');

		$this->dao
			->expects($this->once())
			->method('save');

		$actual = $this->object->process();

		$this->assertEquals(self::USER_ID, $actual);
	}

	public function testProcessWithLoggedInUser()
	{
		$this->facebookApi
			->expects($this->once())
			->method('getAccessToken');

		$this->facebookApi
			->expects($this->once())
			->method('getProfile')
			->willReturn($this->createFacebookProfileData());

		$this->sessionHandler
			->expects($this->once())
			->method('getData')
			->willReturn($this->createSessionData());

		$this->userManager
			->expects($this->never())
			->method('getByEmail');

		$this->userManager
			->expects($this->never())
			->method('loginCallback');

		$this->userManager
			->expects($this->never())
			->method('register');

		$this->passwordGenerator
			->expects($this->never())
			->method('generatePassword');

		$this->dao
			->expects($this->once())
			->method('save');

		$actual = $this->object->process();

		$this->assertEquals(self::USER_ID, $actual);
	}

	private function createFacebookProfileData()
	{
		return new GraphUser([
			'data' => [
				'id'    	 => 123456789,
				'email'   => self::EMAIL,
				'picture' => [
					'url' => self::PICTURE
				]
			]
		]);
	}

	private function createUserData()
	{
		return UserData::create([
			'id'       => self::USER_ID,
			'email'    => self::EMAIL,
			'password' => 'abc123',
		]);
	}

	private function createSessionData()
	{
		return SessionData::create(
			self::USER_ID, self::EMAIL, self::PICTURE
		);
	}
}
