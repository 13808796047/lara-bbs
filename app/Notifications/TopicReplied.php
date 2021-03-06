<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Notifications\Channels\JPushChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;
    public function __construct(Reply $reply)
    {
        // 注入回复实体,方便toDatabase方法中使用
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database', 'mail', JPushChannel::class];
    }

    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link = $topic->link(['#reply' . $this->reply->id]);
        // 存入数据里的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }
    public function toMail($notifiable)
    {
        $url = $this->reply->topic->link('#reply' . $this->reply->id);
        return (new MailMessage)->line('您的话题有新回复!')
            ->action('查看回复', $url);
    }
    public function toJPush($notifiable, PushPayload $payload): PushPayload
    {
        return $payload
            ->setPlatform('all')
            ->addRegistrationId($notifiable->registration_id)
            ->setNotificationAlert(strip_tags($this->reply->content));
    }
}
