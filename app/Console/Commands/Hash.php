<?php

namespace App\Console\Commands;

use Hashids\Hashids;
use Illuminate\Console\Command;

class Hash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash an id';

    protected $hashIds;

    /**
     * Create a new command instance.
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashIds = $hashids;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment(PHP_EOL.$this->hashIds->encode($this->argument('id')).PHP_EOL);
    }
}
