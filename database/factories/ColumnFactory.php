<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories;

use App\Models\board;
use App\Models\Column;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ColumnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Column::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $count = Column::count();
        $next = $count + 1;

        return [
            'name' => $this->faker->unique()->name,
            'board_id' => board::inRandomOrder()->first()->name,
            'tasks' => $this->faker->csv_string(10),

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
