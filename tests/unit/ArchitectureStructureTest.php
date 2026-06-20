<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ArchitectureStructureTest extends CIUnitTestCase
{
    public function testProviderContractsLiveBesideTheirProviders(): void
    {
        $this->assertFileDoesNotExist(APPPATH . 'Contracts/GameProviderInterface.php');
        $this->assertFileDoesNotExist(APPPATH . 'Contracts/PaymentProviderInterface.php');
        $this->assertFileExists(APPPATH . 'Providers/Game/GameProviderInterface.php');
        $this->assertFileExists(APPPATH . 'Providers/Payment/PaymentProviderInterface.php');
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
        $this->assertStringContainsString('getHomePage()', file_get_contents(APPPATH . 'Controllers/Home.php'));
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
}
