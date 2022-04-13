<?php

namespace percipiolondon\notifications\messages;

use Carbon\Carbon;
use Closure;

/**
 * Class SlackAttachment
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 */
class SlackAttachment
{
    /**
     * The attachment's title.
     *
     * @var string
     */
    public string $title;

    /**
     * The attachment's URL.
     *
     * @var string
     */
    public string $url;

    /**
     * The attachment's text content.
     *
     * @var string
     */
    public string $content;

    /**
     * A plain-text summary of the attachment.
     *
     * @var string
     */
    public string $fallback;

    /**
     * The attachment's color.
     *
     * @var string
     */
    public string $color;

    /**
     * The attachment's fields.
     *
     * @var array
     */
    public array $fields;

    /**
     * The fields containing markdown.
     *
     * @var array
     */
    public array $markdown;

    /**
     * The attachment's footer.
     *
     * @var string
     */
    public string $footer;

    /**
     * The attachment's footer icon.
     *
     * @var string
     */
    public string $footerIcon;

    /**
     * The attachment's timestamp.
     *
     * @var int
     */
    public int $timestamp;

    /**
     * Set the title of the attachment.
     *
     * @param string $title
     * @param string|null $url
     * @return $this
     */
    public function title(string $title, ?string $url = null): static
    {
        $this->title = $title;
        $this->url = $url;

        return $this;
    }

    /**
     * Set the content (text) of the attachment.
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
     * A plain-text summary of the attachment.
     *
     * @param string $fallback
     * @return $this
     */
    public function fallback(string $fallback): static
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Set the color of the attachment.
     *
     * @param string $color
     * @return $this
     */
    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Add a field to the attachment.
     *
     * @param array|Closure $title
     * @param string $content
     * @return $this
     */
    public function field(array|Closure $title, string $content = ''): static
    {
        if (is_callable($title)) {
            $callback = $title;

            $callback($attachmentField = new SlackAttachmentField());

            $this->fields[] = $attachmentField;

            return $this;
        }

        $this->fields[$title] = $content;

        return $this;
    }

    /**
     * Set the fields of the attachment.
     *
     * @param  array  $fields
     * @return $this
     */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set the fields containing markdown.
     *
     * @param  array  $fields
     * @return $this
     */
    public function markdown(array $fields): static
    {
        $this->markdown = $fields;

        return $this;
    }

    /**
     * Set the footer content.
     *
     * @param string $footer
     * @return $this
     */
    public function footer(string $footer): static
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Set the footer icon.
     *
     * @param string $icon
     * @return $this
     */
    public function footerIcon(string $icon): static
    {
        $this->footerIcon = $icon;

        return $this;
    }

    /**
     * Set the timestamp.
     *
     * @param  Carbon  $timestamp
     * @return $this
     */
    public function timestamp(Carbon $timestamp): static
    {
        $this->timestamp = $timestamp->getTimestamp();

        return $this;
    }
}
