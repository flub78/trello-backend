<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class TagController
 * @package App\Http\Controllers\api
 */
class TagController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::Debug('TagController@index');

        $elements = Tag::all(); // SELECT * FROM tags

        $data = [
            'status' => 200,
            'tags' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("TagController@show $id");

        $element = Tag::find($id); // SELECT * FROM tags WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'Tag not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'tag' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::Debug('TagController@store');

        $validator = Validator::make($request->all(), [
            "task_id" => 'required|exists:tasks,id',
			"task_color_id" => 'required|exists:tag_colors,id',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('TagController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new Tag;
        $element->task_id = $request->task_id;
		$element->task_color_id = $request->task_color_id;


        $element->save();

        $data = [
            'status' => 200,
            'tag' => $element,
        ];
        Log::Debug('TagController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        Log::Debug("TagController@update $id");

        $validator = Validator::make($request->all(), [
            "task_id" => 'exists:tasks,id',
			"task_color_id" => 'exists:tag_colors,id',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('TagController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = Tag::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'Tag not found',
            ];

            return response()->json($data, 404);
        }

        if ($request->task_id) {
			$element->task_id = $request->task_id;
		}
		if ($request->task_color_id) {
			$element->task_color_id = $request->task_color_id;
		}

        $element->save();

        $data = [
            'status' => 200,
            'tag' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("TagController@delete $id");

        $element = Tag::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'Tag not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "Tag $id deleted",
        ];

        return response()->json($data, 200);
    }
}
