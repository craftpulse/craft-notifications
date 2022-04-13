<?php

namespace percipiolondon\notifications\channels;

use GuzzleHttp\Client as HttpClient;
use percipiolondon\notifications\messages\SlackAttachment;
use percipiolondon\notifications\messages\SlackAttachmentField;
use percipiolondon\notifications\messages\SlackMessage;
use percipiolondon\notifications\models\Notification;

/**
 * Class SlackWebhookChannel
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 */
class SlackWebhookChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Create a new Slack channel instance.
     *
     * @param  \GuzzleHttp\Client  $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        $message = $notification->toSlack();

        $this->http->post($notifiable, $this->buildJsonPayload($message));
    }

    /**
     * Build up a JSON payload for the Slack webhook.
     *
     * @param  SlackMessage  $message
     * @return array
     */
    protected function buildJsonPayload(SlackMessage $message)
    {
        $optionalFields = array_filter([
            'username' => data_get($message, 'username'),
            'icon_emoji' => data_get($message, 'icon'),
            'channel' => data_get($message, 'channel'),
        ]);

        return array_merge([
            'json' => array_merge([
                'text' => $message->content,
                'attachments' => $this->attachments($message),
            ], $optionalFields),
        ], $message->http);
    }

    /**
     * Format the message's attachments.
     *
     * @param  SlackMessage  $message
     * @return array
     */
    protected function attachments(SlackMessage $message)
    {
        return collect($message->attachments)->map(function($attachment) use ($message) {
            return array_filter([
                'color' => $attachment->color ?: $message->color(),
                'title' => $attachment->title,
                'text' => $attachment->content,
                'fallback' => $attachment->fallback,
                'title_link' => $attachment->url,
                'fields' => $this->fields($attachment),
                'mrkdwn_in' => $attachment->markdown,
                'footer' => $attachment->footer,
                'footer_icon' => $attachment->footerIcon,
                'ts' => $attachment->timestamp,
            ]);
        })->all();
    }

    /**
     * Format the attachment's fields.
     *
     * @param  SlackAttachment  $attachment
     * @return array
     */
    protected function fields(SlackAttachment $attachment)
    {
        return collect($attachment->fields)->map(function($value, $key) {
            if ($value instanceof SlackAttachmentField) {
                return $value->toArray();
            }

            return ['title' => $key, 'value' => $value, 'short' => true];
        })->values()->all();
    }
}
