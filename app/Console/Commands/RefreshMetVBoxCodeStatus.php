<?php

namespace App\Console\Commands;

use App\Models\AuthCode;
use App\Services\MetVBoxService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshMetVBoxCodeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metvbox:refresh-all-codes {--limit=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all codes status from MetVBox API. Runs every hour.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting MetVBox code status refresh...');

        $metvboxService = app(MetVBoxService::class);
        $limit = (int) $this->option('limit');
        $updated = 0;
        $failed = 0;
        $skipped = 0;

        // Map MetVBox status to numeric status
        $statusMap = [
            'inactive' => 0,
            'active' => 1,
            'revoked' => 2,
        ];

        try {
            // Get all codes that are not revoked (we still want to track revoked codes)
            // Fetch in batches to avoid memory issues
            $batchSize = 50;
            $totalCodes = 0;
            $page = 0;

            while (true) {
                $codes = AuthCode::where('created_at', '>=', '2026-01-01 00:00:00')
                    ->whereNull('expire_at')
                    ->limit($batchSize)
                    ->offset($page * $batchSize)
                    ->get();

                if ($codes->isEmpty()) {
                    break;
                }

                $totalCodes += $codes->count();

                foreach ($codes as $code) {
                    try {
                        $this->line("Processing code: {$code->auth_code}...");

                        // Call MetVBox API to get current status
                        $status = $metvboxService->checkCodeStatus($code->auth_code);

                        if ($status && $status['success'] && isset($status['data'])) {
                            $data = $status['data'];
                            $metvboxStatus = $data['status'] ?? 'unknown';
                            $newStatus = $statusMap[$metvboxStatus] ?? 0;

                            // Convert ISO 8601 datetime to MySQL format
                            $newExpireAt = null;
                            if ($data['expires_at']) {
                                try {
                                    $newExpireAt = Carbon::parse($data['expires_at'])->format('Y-m-d H:i:s');
                                } catch (\Exception $e) {
                                    Log::warning('Failed to parse expires_at: ' . $data['expires_at']);
                                }
                            }

                            // Update the code
                            $code->update([
                                'status' => $newStatus,
                                'expire_at' => $newExpireAt,
                            ]);

                            $updated++;
                            $this->info("✓ Updated: {$code->auth_code} - Status: {$metvboxStatus}");
                        } else {
                            $failed++;
                            $this->warn("✗ Failed to fetch status for code: {$code->auth_code}");
                        }
                    } catch (\Exception $e) {
                        $failed++;
                        Log::error("Error refreshing code {$code->auth_code}: " . $e->getMessage());
                        $this->error("✗ Error processing code {$code->auth_code}: " . $e->getMessage());
                    }
                }

                $page++;
            }

            Log::info('MetVBox code status batch refresh completed', [
                'total_codes' => $totalCodes,
                'updated' => $updated,
                'failed' => $failed,
            ]);

            $this->newLine();
            $this->info('✅ Refresh completed!');
            $this->line("Total Codes: <fg=cyan>{$totalCodes}</>");
            $this->line("Updated: <fg=green>{$updated}</>");
            $this->line("Failed: <fg=red>{$failed}</>");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('MetVBox code status refresh error: ' . $e->getMessage());
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

