<?php

namespace App\Notifications;

use App\Channels\SmsMessage;
use App\Models\EmailSMSTemplate;
use App\Utilities\Overrider;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRequest extends Notification {
    use Queueable;

    private $paymentRequest;
    private $template;
    private $replace = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($paymentRequest) {
        Overrider::load("Settings");
        $this->paymentRequest = $paymentRequest;
        $this->template       = EmailSMSTemplate::where('slug', 'PAYMENT_REQUEST')->first();

        $this->replace['name']     = $this->paymentRequest->receiver->name;
        $this->replace['email']    = $this->paymentRequest->receiver->email;
        $this->replace['phone']    = $this->paymentRequest->receiver->phone;
        $this->replace['amount']   = decimalPlace($this->paymentRequest->amount, currency($this->paymentRequest->currency->name));
        $this->replace['dateTime'] = $this->paymentRequest->created_at;
        $this->replace['payNow']   = '<a href="' . route('payment_requests.pay_now', encrypt($this->paymentRequest->id)) . '" class="btn btn-success btn-sm">' . _lang('Pay Now') . '</a>';
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
