<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ArchitectureStructureTest extends CIUnitTestCase
{
    public function testBackendUsesMvcAndServicesWithoutProviderLayer(): void
    {
        $this->assertFileDoesNotExist(APPPATH . 'Contracts/GameProviderInterface.php');
        $this->assertFileDoesNotExist(APPPATH . 'Contracts/PaymentProviderInterface.php');
        $this->assertDirectoryDoesNotExist(APPPATH . 'Providers');

        $serviceFiles = glob(APPPATH . 'Services/*.php') ?: [];
        $source       = implode("\n", array_map(static fn (string $file): string => file_get_contents($file), $serviceFiles));

        $this->assertStringNotContainsString('App\\Providers', $source);
        $this->assertStringNotContainsString('ProviderFactory', $source);
    }

    public function testServicesUseConsistentModelNames(): void
    {
        $serviceFiles = glob(APPPATH . 'Services/*.php') ?: [];
        $source       = implode("\n", array_map(static fn (string $file): string => file_get_contents($file), $serviceFiles));

        $this->assertStringNotContainsString('App\\Models\\M_', $source);
        $this->assertStringNotContainsString('new M_', $source);
    }

    public function testControllersDelegatePageCompositionToServices(): void
    {
        $this->assertStringContainsString('HomeService', file_get_contents(APPPATH . 'Controllers/Home.php'));
        $this->assertStringContainsString('getDetailPage($slug)', file_get_contents(APPPATH . 'Controllers/Game.php'));
        $this->assertStringContainsString('getDetailPage($token)', file_get_contents(APPPATH . 'Controllers/Payment.php'));
        $this->assertStringContainsString('checkInvoice($invoice)', file_get_contents(APPPATH . 'Controllers/Payment.php'));
    }

    public function testPaymentDetailQueryLoadsRelatedGameFromProduct(): void
    {
        $this->assertTrue(method_exists(\App\Models\OrderModel::class, 'findByTokenWithGame'));

        $source = file_get_contents(APPPATH . 'Models/OrderModel.php');

        $this->assertStringContainsString('product.id = orders.product_id', $source);
        $this->assertStringContainsString('games.id = product.games_id', $source);
    }

    public function testOrderSchemaDoesNotKeepProviderColumns(): void
    {
        $createOrdersMigration = file_get_contents(APPPATH . 'Database/Migrations/2026-06-09-100000_CreatePaymentMethodsAndOrders.php');
        $dropColumnsMigration  = APPPATH . 'Database/Migrations/2026-06-20-120000_DropProviderColumnsFromOrders.php';

        $this->assertStringNotContainsString("'game_provider'", $createOrdersMigration);
        $this->assertStringNotContainsString("'payment_provider'", $createOrdersMigration);
        $this->assertFileExists($dropColumnsMigration);

        $dropColumnsSource = file_get_contents($dropColumnsMigration);

        $this->assertStringContainsString("'game_provider'", $dropColumnsSource);
        $this->assertStringContainsString("'payment_provider'", $dropColumnsSource);
        $this->assertStringContainsString("dropColumn('orders'", $dropColumnsSource);
    }
}
