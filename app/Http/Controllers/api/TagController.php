<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

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
        try {
            Log::Debug('TagController@index');

            $elements = Tag::all(); // SELECT * FROM tags

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
            Log::Debug("TagController@show $id");

            $element = Tag::find($id); // SELECT * FROM tags WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(['status' => 404, 'message' => "Tag $id not found"], 404);
            }

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
                'tag' => $element
            ];            
            Log::Debug('TagController@store saved in database', $data);
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
    public function update(Request $request, $id)
    {
        try {
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
                return response()->json(['status' => 404, 'message' => "Tag $id not found"], 404);
            }

            if ($request->task_id) {
				$element->task_id = $request->task_id;
			}
			if ($request->task_color_id) {
				$element->task_color_id = $request->task_color_id;
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
            Log::Debug("TagController@delete $id");

            $element = Tag::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Tag $id not found"], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Tag $id deleted",
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
