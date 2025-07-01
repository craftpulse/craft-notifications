<?php
/**
 * Notifications plugin for Craft CMS 5.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craft-pulse.com
 * @copyright Copyright (c) 2025 CraftPulse
 */

namespace craftpulse\notifications\messages;

use Closure;

/**
 * Class SlackMessage
 */
class SlackMessage
{
    /**
     * The "level" of the notification (info, success, warning, error).
     *
     * @var string
     */
    public string $level = 'info';

    /**
     * The username to send the message from.
     *
     * @var string|null
     */
    public ?string $username;

    /**
     * The user icon for the message.
     *
     * @var string|null
     */
    public ?string $icon;

    /**
     * The channel to send the message on.
     *
     * @var string|null
     */
    public ?string $channel;

    /**
     * The text content of the message.
     *
     * @var string
     */
    public string $content;

    /**
     * The message's attachments.
     *
     * @var array
     */
    public array $attachments = [];

    /**
     * Additional request options for the Guzzle HTTP client.
     *
     * @var array
     */
    public array $http = [];

    /**
     * Indicate that the notification gives information about a successful operation.
     *
     * @return $this
     */
    public function success(): static
    {
        $this->level = 'success';

        return $this;
    }

    /**
     * Indicate that the notification gives information about a warning.
     *
     * @return $this
     */
    public function warning(): static
    {
        $this->level = 'warning';

        return $this;
    }

    /**
     * Indicate that the notification gives information about an error.
     *
     * @return $this
     */
    public function error(): static
    {
        $this->level = 'error';

        return $this;
    }

    /**
     * Set a custom user icon for the Slack message.
     *
     * @param string $username
     * @param string|null $icon
     * @return $this
     */
    public function from(string $username, ?string $icon = null): static
    {
        $this->username = $username;

        if (!is_null($icon)) {
            $this->icon = $icon;
        }

        return $this;
    }

    /**
     * Set the Slack channel the message should be sent to.
     *
     * @param string $channel
     * @return $this
     */
    public function to(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content of the Slack message.
     *
     * @param string $content
     * @return $this
     */
    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Define an attachment for the message.
     *
     * @param Closure $callback
     * @return $this
     */
    public function attachment(Closure $callback): static
    {
        $this->attachments[] = $attachment = new SlackAttachment();

        $callback($attachment);

        return $this;
    }

    /**
     * Get the color for the message.
     *
     * @return string
     */
    public function color(): string
    {
        switch ($this->level) {
            case 'success':
                return 'good';
            case 'error':
                return 'danger';
            case 'warning':
                return 'warning';
        }
    }

    /**
     * Set additional request options for the Guzzle HTTP client.
     *
     * @param  array  $options
     * @return $this
     */
    public function http(array $options): static
    {
        $this->http = $options;

        return $this;
    }
}
