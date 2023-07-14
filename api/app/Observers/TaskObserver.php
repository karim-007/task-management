<?php

namespace App\Observers;

use App\Models\Email;
use App\Models\Task;
use App\Models\User;

class TaskObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        try {
            if ($task->assing_to){
                $user = User::find($task->assing_to);
                $email=[
                    'name'=>$user->name,
                    'subject'=>'New Task assign',
                    'email'=>$user->email,
                    'message'=>"You have a new task $task->title $task->description",
                    'user_id'=>$task->assing_to,
                    'task_id'=>$task->id,
                    'try'=>0,
                    'is_sent'=>0,
                ];
                $this->createEmail($email);
            }
        }catch (\Exception $e){
            //
        }

    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        try {
            if ($task->assing_to){
                $user = User::find($task->assing_to);
                $email=[
                    'name'=>$user->name,
                    'subject'=>'New Task assign/ task updated',
                    'email'=>$user->email,
                    'message'=>"You have a new task/updated $task->title $task->description",
                    'user_id'=>$task->assing_to,
                    'task_id'=>$task->id,
                    'try'=>0,
                    'is_sent'=>0,
                ];
                $this->createEmail($email);
            }
        }catch (\Exception $e){
            //
        }
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }

    private function createEmail($email){
        try {
            Email::create($email);
        }catch (\Exception $e){

        }
    }
}
