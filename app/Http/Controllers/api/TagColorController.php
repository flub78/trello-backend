<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TagColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class TagColorController
 * @package App\Http\Controllers\api
 */
class TagColorController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        Log::Debug('TagColorController@index');

        $elements = TagColor::all(); // SELECT * FROM tag_colors

            return response()->json($elements, 200);
        
        } catch (\Exception $e) {

            Log::Error('BoardController@index', ['message' => $e->getMessage()]);
        $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
        ];

            return response()->json($data, 500);
        }       
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
        Log::Debug("TagColorController@show $id");

            $element = TagColor::findOrFail($id); // SELECT * FROM tag_colors WHERE id = $id 

            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('BoardController@show', ['message' => $e->getMessage()]);
        $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
        ];

            return response()->json($data, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        Log::Debug('TagColorController@store');

        $validator = Validator::make($request->all(), [
            "name" => 'required|string|max:128',
			"color" => 'required|string|max:128',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('TagColorController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new TagColor;
        $element->name = $request->name;
		$element->color = $request->color;

            $element->save();


        $data = [
            'status' => 200,
                'tag_color' => $element
        ];
        Log::Debug('TagColorController@store saved in database', $data);
            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('BoardController@store', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
        Log::Debug("TagColorController@update $id");

        $validator = Validator::make($request->all(), [
            "name" => 'string|max:128',
			"color" => 'string|max:128',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('TagColorController@store validation failed', $data);

            return response()->json($data, 422);
        }

            $element = TagColor::findOrFail($id);
        if ($request->name) {
			$element->name = $request->name;
		}
		if ($request->color) {
			$element->color = $request->color;
		}

        $element->save();

            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('BoardController@update', ['message' => $e->getMessage()]);
        $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
        ];

            return response()->json($data, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
        Log::Debug("TagColorController@delete $id");

            $element = TagColor::findOrFail($id);

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "TagColor $id deleted",
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            Log::Error('BoardController@destroy', ['message' => $e->getMessage()]);
        $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
        ];

            return response()->json($data, 500);
        }

    }
}
