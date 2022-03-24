<?php
namespace App\Mail;

use App\Users;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailConfirmation extends Notification
{
    use Queueable, SerializesModels;

    /**
     * Constructor recieves the users verification key, and uses it to build the email.
     *
     * @return void
     */
    public function __construct(String $userKey)
    {   
        $this->userKey = $userKey;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Thank you for registering with Fleet Portal! Click the button below to confirm your email!')
            ->action('Confirm Email', url('authorize/'.$this->userKey))
            ->line('If you received this email in error, no further action is required.');
    }


    // /**
    //  * Build the message and append the verification key to the end.
    //  *'localhost:8080/FleetPortal/public/authorize/'
    //  * @return $this
    //  */
    // public function build()
    // {   
    //     return $this->view('emailconfirmation')
    //                 ->with([
    //                     'text' => "Thank you for registering with Fleet Portal! Click the button below to confirm your email!",
    //                     'action' => 'localhost:8080/FleetPortal/public/authorize/'.$this->userKey,
    //                 ]);
    // }
}
