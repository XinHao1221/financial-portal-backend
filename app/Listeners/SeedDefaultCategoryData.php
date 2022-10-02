<?php

namespace App\Listeners;

use App\Models\Category;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SeedDefaultCategoryData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Default categories
        $categories = [
            'Food',
            'Transport',
            'Haircut',
            'Daily Necessities',
            'Shopping',
            'Apparel',
            'Communication',
            'Education',
            'Medical'
        ];

        $user = $event->user;

        $categoryData = [];

        // Form data
        foreach($categories as $category)
        {
            array_push($categoryData,[ 
                'name' => $category,
                'user_id' => $user->id,
            ]);
        }

        Category::insert($categoryData);
    }
}
