<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:lreset {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets and reseeds all the project tables';

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
        $this->info(
            "This command resets and reseeds all the database tables."
        );
        if($this->option('force') or $this->confirm("WARNING: This operation will delete all the data in the database table. Do you want to continue?")) {
            $this->info('Beginning the database deletion');
            $colname = 'Tables_in_' . env('DB_DATABASE');
            $tables = \DB::select("SHOW FULL TABLES where Table_Type = 'BASE TABLE'");
            $views = \DB::select("SHOW FULL TABLES where Table_Type = 'VIEW'");
            $t_droplist = [];
            foreach($tables as $table) {
                $t_droplist[] = $table->$colname;
            }
            $v_droplist = [];
            foreach($views as $table) {
                $v_droplist[] = $table->$colname;
            }
            $t_droplist = implode(',', $t_droplist);
            $v_droplist = implode(',', $v_droplist);

            \DB::beginTransaction();
            \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
	        if($t_droplist) {
		        \DB::statement("DROP TABLE $t_droplist");
	        }
	        if($v_droplist) {
		        \DB::statement("DROP VIEW $v_droplist");
	        }
            \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            \DB::commit();
            $this->info('Database deletion ended');

            $this->info('Beginning migrations');
            $this->call('migrate');
            $this->info('Migrations ended');

            $this->info('Seeding');
            $this->call('db:seed');
            $this->info('Seeding ended');

            $this->info('Have a nice day!');
        }
        else {
            $this->info('Operazione undone. Have a nice day!');
        }
    }
}
