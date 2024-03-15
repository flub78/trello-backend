<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace Database\Factories;

use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;


class BoardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Board::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $count = Board::count ();
        $next = $count + 1;
        
        return [
            		'name' => $this->faker->word,
		'description' => $this->faker->word,
		'email' => $this->faker->word,
		'favorite' => $this->faker->boolean,
		'read_at' => $this->faker->dateTime(),
		'href' => $this->faker->word,
		'image' => $this->faker->word,
		'theme' => $this->faker->randomElement(['light', 'dark']),
		'lists' => $this->faker->word,
		'created_at' => $this->faker->dateTime(),
		'updated_at' => $this->faker->dateTime(),

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
    public function error_cases () {
        $scenarios = [];
        // $scenarios[] = ["fields" => [], "errors" => ["name" => "The name field is required."]];
        // $scenarios[] = ["fields" => ["name" => $bad_name], "errors" => ["name" => "The name must not be greater than 255 characters."]];
       return $scenarios;       
    }
}
