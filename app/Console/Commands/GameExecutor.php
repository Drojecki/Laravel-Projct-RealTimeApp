<?php

namespace App\Console\Commands;

use App\Events\RemainingTime;
use App\Events\WinnerNumber;
use Illuminate\Console\Command;

class GameExecutor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start executing the game';

    private $time = 10;
    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(true){
            broadcast(new RemainingTime($this->time.'s'));
            $this->time--;
            sleep(1);

            if($this->time===0){
                $this->time = 'Waiting to start';
                broadcast(new RemainingTime($this->time));

                broadcast(new WinnerNumber(mt_rand(1,12)));

                sleep(3);
                $this->time = 10;
            }
        }
    }
}
