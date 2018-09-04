<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateFreshWithViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:fresh_with_views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Like migrate:fresh, but also delete views';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::statement('DROP VIEW knowledge_grades_summary');
        Artisan::call('migrate:fresh');
    }
}
