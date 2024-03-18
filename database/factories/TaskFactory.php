<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories;

use App\Models\column;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $count = Task::count();
        $next = $count + 1;

        return [
            'name' => $this->faker->unique()->name,
            'description' => $this->faker->text,
            'column_id' => Column::inRandomOrder()->first()->id,
            'due_date' => $this->faker->date(),
            'completed' => $this->faker->boolean,
            'image' => $this->faker->sentence(17),
            'href' => $this->faker->sentence(17),
            'favorite' => $this->faker->boolean,
            'watched' => $this->faker->boolean,

        ];
    }

    /**
     * return a list of erroneous fields and associated expected errors
     * [
     *      ["fieds" => [],
     *       "errors" => ["name" => "The name field is required."]
     *      ],
     *      ["fields" => ['name' => $too_long_name, 'email' => 'incorrect_email'],
     *       "errors" => ['name' => 'The name must not be greater than 255 characters.', 'email' => 'The email must be a valid email address.']
     *      ]
     * ]
     * @return string[]
     */
    public function error_cases()
    {
        $scenarios = [];
        // $scenarios[] = ["fields" => [], "errors" => ["name" => "The name field is required."]];
        // $scenarios[] = ["fields" => ["name" => $bad_name], "errors" => ["name" => "The name must not be greater than 255 characters."]];
        return $scenarios;
    }
}
