<?php

namespace App\Listeners;

use App\Events\CategorySaving as EventCategorySaving;

class CategorySaving
{
    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param EventCategorySaving $event
     * @return void
     */
    public function handle(EventCategorySaving $event): void
    {
        $event->model->slug = str_slug($event->model->name, '-');
    }
}
