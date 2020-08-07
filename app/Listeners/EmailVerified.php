<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Validated;

class EmailVerified
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Validated  $event
     * @return void
     */
    public function handle(Validated $event)
    {
        //会话里闪存认证成功后的消息提醒
        session()->flash('success', '邮箱验证成功^_^');
    }
}
