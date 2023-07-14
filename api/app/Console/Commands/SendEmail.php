<?php

namespace App\Console\Commands;

use App\Models\Email;
use App\Mail\CommonMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
    protected $description = 'Send email to users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = Email::where(['try'=>0])->limit(50)->get(['id','name','email','subject','message']);
        $success=[];
        $fails=[];
        Email::whereIn('id',$emails->pluck('id'))->update(['try'=>1]);
        if (count($emails) > 0) {
            foreach ($emails as $email) {
                try {
                    Mail::to($email)->send(new CommonMail($email));
                    $success[]=$email->id;
                } catch (\Exception $e) {
                    logger($e->getMessage());
                }
            }
        }
        if(count($success)) Email::whereIn('id',$success)->update(['is_sent'=>1]);
    }
}
