<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateUserCountStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_user_count_stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new record in stats table';

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
     * Execute the console command
     */
    public function handle()
    {
        DB::table('stats')->insert(['date' => date('Y-m-d')]);

        \Sheets::spreadsheet('1WwgxOzrjb8X53LgH30yfVYTJqQCJWvb9bhiuHNoxq1c')->sheet('Посещаймость')->range('')
            ->append([[date('Y-m-d'), 0]]);
    }
}