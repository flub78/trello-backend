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
        Log::Debug('TagColorController@index');

        $elements = TagColor::all(); // SELECT * FROM tag_colors

        $data = [
            'status' => 200,
            'tag_colors' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("TagColorController@show $id");

        $element = TagColor::find($id); // SELECT * FROM tag_colors WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'TagColor not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'tag_color' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            'tag_color' => $element,
        ];
        Log::Debug('TagColorController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
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

        $element = TagColor::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'TagColor not found',
            ];

            return response()->json($data, 404);
        }

        if ($request->name) {
			$element->name = $request->name;
		}
		if ($request->color) {
			$element->color = $request->color;
		}

        $element->save();

        $data = [
            'status' => 200,
            'tag_color' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("TagColorController@delete $id");

        $element = TagColor::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'TagColor not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "TagColor $id deleted",
        ];

        return response()->json($data, 200);
    }
}
