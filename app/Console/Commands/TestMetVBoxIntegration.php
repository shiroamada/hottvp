<?php

namespace App\Console\Commands;

use App\Services\MetVBoxService;
use Illuminate\Console\Command;

class TestMetVBoxIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metvbox:test {--quantity=1} {--days=30}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Test MetVBox API integration by generating a test code';

    /**
     * Execute the console command.
     */
    public function handle(MetVBoxService $service): int
    {
        $this->info('Testing MetVBox API Connection...');

        // Test connection first
        $connectionTest = $service->testConnection();

        if (!$connectionTest['success']) {
            $this->error('❌ Connection Test Failed');
            $this->error('Error: ' . $connectionTest['error']);
            $this->error('Message: ' . $connectionTest['message']);
            return 1;
        }

        $this->info('✓ Connection test passed');
        $this->newLine();

        // Test code generation
        $quantity = (int) $this->option('quantity');
        $days = (int) $this->option('days');

        $this->info("Testing Code Generation (Quantity: {$quantity}, Days: {$days})...");

        $result = $service->generateCode(
            validDays: $days,
            deviceType: 'all',
            quantity: $quantity
        );

        if (!$result) {
            $this->error('❌ Code Generation Failed');
            $this->error('The API returned no response');
            return 1;
        }

        $this->info('✓ Code generation request successful');
        $this->newLine();

        // Display response
        $this->info('API Response:');
        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->newLine();

        // Try to extract codes
        $codes = [];
        if (isset($result['data']) && is_array($result['data'])) {
            foreach ($result['data'] as $item) {
                $code = is_array($item) ? ($item['code'] ?? null) : $item;
                if (is_string($code)) {
                    $codes[] = $code;
                }
            }
        }

        if (!empty($codes)) {
            $this->info("✓ Successfully extracted {$quantity} code(s):");
            foreach ($codes as $code) {
                $this->line("  - {$code}");
            }
        } else {
            $this->warn('⚠ No codes could be extracted from the response');
            $this->warn('Please verify the response format matches your API documentation');
        }

        return 0;
    }
}
