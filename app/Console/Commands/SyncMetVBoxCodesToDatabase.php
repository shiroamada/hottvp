<?php

namespace App\Console\Commands;

use App\Models\Assort;
use App\Models\AuthCode;
use App\Services\MetVBoxService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncMetVBoxCodesToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metvbox:sync-codes {--limit=100} {--status=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all codes from MetVBox API to database. New codes are inserted with vendor=metvbox.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting MetVBox code sync from API to database...');

        $metvboxService = app(MetVBoxService::class);
        $limit = (int) $this->option('limit');
        $statusFilter = $this->option('status');

        $totalFromApi = 0;
        $newInserted = 0;
        $alreadyExists = 0;
        $failed = 0;

        // Map MetVBox status to numeric status
        $statusMap = [
            'inactive' => 0,
            'active' => 1,
            'revoked' => 2,
        ];

        try {
            // Fetch codes from MetVBox API with pagination
            $page = 1;
            $pageSize = 50; // Fetch 50 codes per page from API

            while (true) {
                $this->line("Fetching page {$page} from MetVBox API...");

                $response = $metvboxService->listCodes($statusFilter, $page, $pageSize);

                if (!$response || !$response['success']) {
                    $this->error("❌ Failed to fetch codes from MetVBox API");
                    Log::error('Failed to fetch codes from MetVBox API', ['response' => $response]);
                    $failed++;
                    break;
                }

                $codes = $response['codes'] ?? [];
                $pagination = $response['pagination'] ?? [];

                if (empty($codes)) {
                    $this->line("No more codes to fetch from API.");
                    break;
                }

                $totalFromApi += count($codes);

                foreach ($codes as $apiCode) {
                    try {
                        // Check if code already exists
                        $exists = AuthCode::where('auth_code', $apiCode['code'] ?? $apiCode['auth_code'] ?? null)->exists();

                        if ($exists) {
                            $alreadyExists++;
                            $this->line("⊘ Code already exists: {$apiCode['code']}");
                            continue;
                        }

                        // Map status
                        $metvboxStatus = $apiCode['status'] ?? 'inactive';
                        $newStatus = $statusMap[$metvboxStatus] ?? 0;

                        // Parse expiry date
                        $expireAt = null;
                        if ($apiCode['expires_at'] ?? null) {
                            try {
                                $expireAt = Carbon::parse($apiCode['expires_at'])->format('Y-m-d H:i:s');
                            } catch (\Exception $e) {
                                Log::warning('Failed to parse expires_at for code: ' . ($apiCode['code'] ?? 'unknown'));
                            }
                        }

                        // Find assort_id by valid_days
                        $validDays = $apiCode['valid_days'] ?? 1;
                        $assort = Assort::where('duration', $validDays)->first();
                        $assortId = $assort?->id ?? 1; // Default to assort_id=1 if not found

                        // Create auth code record
                        $currentEnv = app()->environment();
                        $authCode = AuthCode::create([
                            'assort_id' => $assortId,
                            'user_id' => 1, // System admin - codes not generated in production
                            'auth_code' => $apiCode['code'] ?? $apiCode['auth_code'],
                            'remark' => "Not generated in {$currentEnv}, in other environment. Synced from MetVBox API. Status: {$metvboxStatus}, Valid Days: {$validDays}",
                            'status' => $newStatus,
                            'type' => 0, // Default type
                            'is_try' => 1, // Not a trial code
                            'profit' => 0,
                            'expire_at' => $expireAt,
                            'num' => 1,
                        ]);

                        $newInserted++;
                        $this->info("✓ Inserted new code: {$apiCode['code']} (Status: {$metvboxStatus})");

                    } catch (\Exception $e) {
                        $failed++;
                        Log::error("Error syncing code: " . $e->getMessage(), [
                            'code' => $apiCode['code'] ?? 'unknown',
                            'error' => $e->getMessage(),
                        ]);
                        $this->error("✗ Error syncing code {$apiCode['code']}: " . $e->getMessage());
                    }
                }

                // Check if there are more pages
                if (($pagination['page'] ?? 1) >= ($pagination['total_pages'] ?? 1)) {
                    break;
                }

                $page++;
            }

            Log::info('MetVBox code sync completed', [
                'total_from_api' => $totalFromApi,
                'new_inserted' => $newInserted,
                'already_exists' => $alreadyExists,
                'failed' => $failed,
            ]);

            $this->newLine();
            $this->info('✅ Sync completed!');
            $this->line("Total codes from API: <fg=cyan>{$totalFromApi}</>");
            $this->line("New inserted: <fg=green>{$newInserted}</>");
            $this->line("Already exists: <fg=yellow>{$alreadyExists}</>");
            $this->line("Failed: <fg=red>{$failed}</>");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('MetVBox code sync error: ' . $e->getMessage());
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
