<?php

use App\Services\AuthService;
use CodeIgniter\Test\CIUnitTestCase;

final class AuthServiceTestDouble extends AuthService
{
    public array $resetEmailCalls = [];

    public function setAuthModel(object $model): void
    {
        $this->authModel = $model;
    }

    protected function sendResetEmail(string $email, string $token): array
    {
        $this->resetEmailCalls[] = [
            'email' => $email,
            'token' => $token,
        ];

        return ['success' => true];
    }
}

final class AuthModelTestDouble
{
    public array $loginUser = [];
    public array $emailUser = [];
    public array $resetUser = [];
    public array $inserted = [];
    public array $updated = [];
    public array $whereCalls = [];

    public function verifyLogin(string $username, string $password): array
    {
        return $this->loginUser;
    }

    public function insert($data = null, bool $returnID = true)
    {
        $this->inserted[] = $data;

        return 123;
    }

    public function update($id, $data)
    {
        $this->updated[] = [
            'id'   => $id,
            'data' => $data,
        ];

        return true;
    }

    public function getByEmail(string $email): array
    {
        return $this->emailUser;
    }

    public function where(string $key, $value = null, ?bool $escape = null): self
    {
        $this->whereCalls[] = [
            'key'    => $key,
            'value'  => $value,
            'escape' => $escape,
        ];

        return $this;
    }

    public function first(): array
    {
        return $this->resetUser;
    }
}

/**
 * @internal
 */
final class AuthServiceTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        service('session')->remove('user_id');
    }

    public function testLoginStoresUserIdInSession(): void
    {
        $model  = new AuthModelTestDouble();
        $model->loginUser = ['id' => 7];

        $service = new AuthServiceTestDouble();
        $service->setAuthModel($model);

        $result = $service->login('alice', 'secret123');

        $this->assertTrue($result['success']);
        $this->assertSame(7, $result['user_id']);
        $this->assertSame(7, service('session')->get('user_id'));
    }

    public function testRegisterHashesPasswordAndSetsDefaults(): void
    {
        $model   = new AuthModelTestDouble();
        $service = new AuthServiceTestDouble();
        $service->setAuthModel($model);

        $result = $service->register([
            'username' => 'alice',
            'email'    => 'alice@example.com',
            'password' => 'secret123',
            'phone'    => '08123456789',
        ]);

        $this->assertTrue($result['success']);
        $this->assertCount(1, $model->inserted);
        $payload = $model->inserted[0];

        $this->assertSame('alice', $payload['username']);
        $this->assertSame('alice@example.com', $payload['email']);
        $this->assertSame('08123456789', $payload['phone']);
        $this->assertSame(0, $payload['balance']);
        $this->assertSame('Member', $payload['level']);
        $this->assertSame('On', $payload['status']);
        $this->assertTrue(password_verify('secret123', $payload['password']));
    }

    public function testLogoutClearsRememberTokenAndSessionUserId(): void
    {
        $model  = new AuthModelTestDouble();
        $service = new AuthServiceTestDouble();
        $service->setAuthModel($model);

        service('session')->set('user_id', 11);

        $service->logout();

        $this->assertNull(service('session')->get('user_id'));
        $this->assertCount(1, $model->updated);
        $this->assertSame(11, $model->updated[0]['id']);
        $this->assertSame(['remember_token' => null], $model->updated[0]['data']);
    }

    public function testForgotPasswordReturnsSuccessWithoutLeakingMissingEmails(): void
    {
        $model   = new AuthModelTestDouble();
        $service = new AuthServiceTestDouble();
        $service->setAuthModel($model);

        $result = $service->forgotPassword('missing@example.com');

        $this->assertTrue($result['success']);
        $this->assertSame([], $model->updated);
        $this->assertSame([], $service->resetEmailCalls);
    }

    public function testForgotPasswordStoresResetTokenAndCallsEmailSender(): void
    {
        $model            = new AuthModelTestDouble();
        $model->emailUser = ['id' => 15, 'email' => 'alice@example.com'];

        $service = new AuthServiceTestDouble();
        $service->setAuthModel($model);

        $result = $service->forgotPassword('alice@example.com');

        $this->assertTrue($result['success']);
        $this->assertCount(1, $model->updated);
        $this->assertSame(15, $model->updated[0]['id']);
        $this->assertArrayHasKey('reset_token', $model->updated[0]['data']);
        $this->assertArrayHasKey('reset_expires_at', $model->updated[0]['data']);
        $this->assertSame(64, strlen($model->updated[0]['data']['reset_token']));
        $this->assertCount(1, $service->resetEmailCalls);
        $this->assertSame('alice@example.com', $service->resetEmailCalls[0]['email']);
        $this->assertSame($model->updated[0]['data']['reset_token'], $service->resetEmailCalls[0]['token']);
    }

    public function testResetPasswordClearsResetAndRememberTokens(): void
    {
        $model            = new AuthModelTestDouble();
        $model->resetUser = ['id' => 21];

        $service = new AuthServiceTestDouble();
        $service->setAuthModel($model);

        $result = $service->resetPassword('token-abc', 'new-secret');

        $this->assertTrue($result['success']);
        $this->assertCount(1, $model->updated);
        $this->assertSame(21, $model->updated[0]['id']);
        $this->assertTrue(password_verify('new-secret', $model->updated[0]['data']['password']));
        $this->assertNull($model->updated[0]['data']['reset_token']);
        $this->assertNull($model->updated[0]['data']['reset_expires_at']);
        $this->assertNull($model->updated[0]['data']['remember_token']);
    }
}
