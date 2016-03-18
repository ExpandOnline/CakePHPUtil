<?php

App::uses('ApiToken', 'CakePHPUtil.Lib/Api');
App::uses('ApiScopeValidator', 'CakePHPUtil.Lib/Api/Scopes');
App::uses('ApiScopeFactory', 'CakePHPUtil.Lib/Api/Scopes');

class ApiTokenTest extends CakeTestCase {

	public $token = [
		'id' => 'test-id',
		'test' => 'testdata'
	];

	/** @var ApiToken $apiToken */
	public $apiToken;
	public $secretKey = 'testkey';


	public function setUp() {
		parent::setUp();
		$this->apiToken = new ApiToken(
			$this->token['id'],
			[ApiScopeFactory::logScope()->setCreate()->setRead()->setUpdate()->setDelete()],
			['test' => $this->token['test']]
		);
	}

	public function testTokenId() {

		$this->assertEquals($this->token['id'], $this->apiToken->getId(), 'Token ID does not match expected id');

		$tokenWithoutId = new ApiToken();
		$this->assertEquals(null, $tokenWithoutId->getId(), 'Token without ID did not return null for id');
	}

	public function testTokenScopes() {

		$this->apiToken->setScopes([
			ApiScopeFactory::logScope()->setCreate()->setRead()->setUpdate()->setDelete()
		]);

		$this->assertTrue(ApiScopeValidator::hasScopes(
			(new ApiScopeValidator())->resolveScopes(
				[ApiScopeFactory::logScope()->setCreate()->setRead()->setUpdate()->setDelete()]
			),
			[
				ApiScopeFactory::logScope()->setCreate()->setRead()->setUpdate()->setDelete()
			]
		));

		$this->assertEquals([
			'logs.create',
			'logs.read',
			'logs.update',
			'logs.delete'
		], $this->apiToken->getRawScopes());


		$this->assertEquals([], (new ApiToken())->getRawScopes());
		$this->assertEquals(['logs.read'], (new ApiToken('1', ['logs.read']))->getRawScopes());
	}

	public function testExtraFields() {
		$this->assertEquals($this->token['test'], $this->apiToken->getExtraFields()['test']);
	}

	public function testIfTokenCanBeEncoded() {
		$encoded = $this->apiToken->encode($this->secretKey);
		$this->assertInternalType("string", $encoded);
	}

	public function testIfTokenCanBeDecoded() {
		$apiToken = new ApiToken();
		$apiToken->decode($this->apiToken->encode($this->secretKey), $this->secretKey);

		$this->assertEquals($apiToken->getId(), $this->apiToken->getId(), 'Token ID does not match expected id');
	}

}