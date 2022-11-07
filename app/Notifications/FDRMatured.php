<?php

namespace App\Notifications;

use App\Channels\SmsMessage;
use App\Models\EmailSMSTemplate;
use App\Utilities\Overrider;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FDRMatured extends Notification {
    use Queueable;

    private $transaction;
    private $template;
    private $replace = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction) {
        Overrider::load("Settings");
        $this->transaction = $transaction;
        $this->template    = EmailSMSTemplate::where('slug', 'FDR_MATURED')->first();

        $this->replace['name']     = $this->transaction->user->name;
        $this->replace['email']    = $this->transaction->user->email;
        $this->replace['phone']    = $this->transaction->user->phone;
        $this->replace['amount']   = decimalPlace($this->transaction->amount, currency($this->transaction->currency->name));
        $this->replace['dateTime'] = $this->transaction->created_at;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        $channels = ['database'];
        if ($this->template != null && $this->template->email_status == 1) {
            array_push($channels, 'mail');
        }
        if ($this->template != null && $this->template->sms_status == 1) {
            array_push($channels, \App\Channels\TwilioSms::class);
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $message = processShortCode($this->template->email_body, $this->replace);

        return (new MailMessage)
            ->subject($this->template->subject)
            ->markdown('email.notification', ['message' => $message]);
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTwilioSms($notifiable) {
        $message = processShortCode($this->template->sms_body, $this->replace);

        return (new SmsMessage())
            ->setContent($message)
            ->setRecipient($notifiable->country_code . $notifiable->phone);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        $message = processShortCode($this->template->sms_body, $this->replace);
        return ['message' => $message];
    }
}
