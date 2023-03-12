<?php

namespace percipiolondon\notifications\messages;

use JetBrains\PhpStorm\ArrayShape;

/**
 * Class SlackAttachmentField
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 */
class SlackAttachmentField
{
    /**
     * The title field of the attachment field.
     *
     * @var string
     */
    protected string $title;

    /**
     * The content of the attachment field.
     *
     * @var string
     */
    protected string $content;

    /**
     * Whether the content is short.
     *
     * @var bool
     */
    protected bool $short = true;

    /**
     * Set the title of the field.
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the content of the field.
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
     * Indicates that the content should not be displayed side-by-side with other fields.
     *
     * @return $this
     */
    public function long(): static
    {
        $this->short = false;

        return $this;
    }

    /**
     * Get the array representation of the attachment field.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->content,
            'short' => $this->short,
        ];
    }
}
