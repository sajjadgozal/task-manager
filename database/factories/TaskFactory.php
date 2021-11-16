<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class TaskFactory extends Factory
{
    private static $order = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'description'=>$this->faker->sentence(),
            'project_id'=> function(){
                if (Project::count()) {
                    return rand(1,Project::count());
                }
                return Project::factory()->create()->id;
            },
            'priority'   => self::$order++
        ];
    }
}
