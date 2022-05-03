<?php

return [
    /**
     * This is where you define your notifications for example if you want to trigger a notification
     * every time a blogpost is added, your notifications array could look like this:
    * 'notifications' => [
    *     [
    *         'elementType' => \craft\elements\Entry::class,
    *         'event' => \craft\elements\Entry::EVENT_AFTER_SAVE,
    *         'notification' => \app\notifications\BlogPostAdded::class,
    *     ],
    * ],
     *
     * In your BlogPostAdded class' via($event) function you could then check if the entry that was saved is a new entry
     * through the event properties
     */
    'notifications' => [],
];
