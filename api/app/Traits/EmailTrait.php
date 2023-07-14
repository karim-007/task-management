<?php
namespace App\Traits;

use App\Mail\DefaultMail;
use App\Mail\OrderConfirm;
use App\Mail\OrderConfirm2;
use App\Mail\TutoringOrder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

trait EmailTrait{

    protected function defaultEmail($name, $email,$subject,$message): void
    {
        $data=[
            'name'=>$name,
            'email'=>$email,
            'subject'=>$subject,
            'message'=>$message,
        ];
        try {
            Mail::to($email)->bcc(env('ADMIN_NOTIFY_MAIL'))->send(new DefaultMail($data));
        }catch (\Exception $e){
            //
        }
    }

    protected function passwordRestEmail($email,$message): void
    {
        $data=[
            'name'=>'',
            'email'=>$email,
            'subject'=>'Password reset request',
            'message'=>$message,
        ];
        try {
            Mail::to($email)->send(new DefaultMail($data));
        }catch (\Exception $e){
            //
        }
    }

}
