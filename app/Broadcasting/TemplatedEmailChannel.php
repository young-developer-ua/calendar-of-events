<?php

namespace App\Broadcasting;

use App\Mail\TemplatedEmail;
use App\Models\User;
use App\Notifications\EmailMessageModel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TemplatedEmailChannel
{
    /**
     * Authenticate the user's access to the channel.
     *
     * @param  User  $user
     * @return array|bool
     */
    public function join(User $user)
    {
        return true;
    }

    /**
     * Send the given notification.
     *
     * @param User $user
     * @param Notification $notification
     * @return void
     * @throws \ReflectionException
     */
    public function send($user, Notification $notification)
    {
        $emailModel = $notification->toEmail();
        if (empty($emailModel))
        {
            return;
        }
        if ($emailModel instanceof EmailMessageModel)
        {
            $data = array_merge([
                'userName' => $user->name,
                'email' => $user->email,
            ], $emailModel->data);

            Mail::to($user)
                ->send(new TemplatedEmail($emailModel->template, $emailModel->body, $emailModel->subject, $data));
        }
        else
        {
            Log::error('The email message is not instance of EmailMessage');
        }
    }
}

