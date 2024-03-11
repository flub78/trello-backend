<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaskCommentController
 * @package App\Http\Controllers\api
 */
class TaskCommentController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::Debug('TaskCommentController@index');

            $elements = TaskComment::all(); // SELECT * FROM task_comments

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
            Log::Debug("TaskCommentController@show $id");

            $element = TaskComment::findOrFail($id); // SELECT * FROM task_comments WHERE id = $id 

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
            Log::Debug('TaskCommentController@store');

            $validator = Validator::make($request->all(), [
                "text" => '',
			"from_email" => 'required|string|max:128|email',
			"task_id" => 'required|exists:tasks,id',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('TaskCommentController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new TaskComment;
            $element->text = $request->text;
		$element->from_email = $request->from_email;
		$element->task_id = $request->task_id;

            $element->save();


            $data = [
                'status' => 200,
                'task_comment' => $element
            ];            
            Log::Debug('TaskCommentController@store saved in database', $data);
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
            Log::Debug("TaskCommentController@update $id");

            $validator = Validator::make($request->all(), [
                "text" => '',
			"from_email" => 'string|max:128|email',
			"task_id" => 'exists:tasks,id',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('TaskCommentController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = TaskComment::findOrFail($id);
            if ($request->text) {
			$element->text = $request->text;
		}
		if ($request->from_email) {
			$element->from_email = $request->from_email;
		}
		if ($request->task_id) {
			$element->task_id = $request->task_id;
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
            Log::Debug("TaskCommentController@delete $id");

            $element = TaskComment::findOrFail($id);

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "TaskComment $id deleted",
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
