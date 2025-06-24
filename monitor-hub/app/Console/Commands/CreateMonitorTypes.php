<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MonitorType;
use Illuminate\Console\Command;

class CreateMonitorTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:create-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize monitor types in Database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $types = MonitorType::getTypes();

        foreach ($types as $type) {
            $response = MonitorType::where('name', $type)->get();
            if ($response->isEmpty()) {
                MonitorType::create([
                    'name' => $type,
                ]);
                $this->info("Monitor type `{$type}` created");
            }
        }
    }
}
