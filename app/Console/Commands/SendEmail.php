<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify about task deadline.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::where('deadline', '<=', Carbon::parse(today())->toDateString())->get();
        foreach ($tasks as $key => $value) {
            $user = User::where('id', $tasks->user_id)->first();
            Mail::to($user->mail, send(new \App\Mail\ExampleEmail()));
            $this->info('Email sent.');
        }
    }
}
