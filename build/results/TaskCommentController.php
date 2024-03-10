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
        Log::Debug('TaskCommentController@index');

        $elements = TaskComment::all(); // SELECT * FROM task_comments

        $data = [
            'status' => 200,
            'task_comments' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("TaskCommentController@show $id");

        $element = TaskComment::find($id); // SELECT * FROM task_comments WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'TaskComment not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'task_comment' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::Debug('TaskCommentController@store');

        $validator = Validator::make($request->all(), [
            "text" => '',
			"from_email" => 'required|string|max:128|email',
			"task_id" => 'required',

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
            'task_comment' => $element,
        ];
        Log::Debug('TaskCommentController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        Log::Debug("TaskCommentController@update $id");

        $validator = Validator::make($request->all(), [
            "text" => '',
			"from_email" => 'string|max:128|email',
			"task_id" => '',

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

        $element = TaskComment::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'TaskComment not found',
            ];

            return response()->json($data, 404);
        }

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

        $data = [
            'status' => 200,
            'task_comment' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("TaskCommentController@delete $id");

        $element = TaskComment::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'TaskComment not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "TaskComment $id deleted",
        ];

        return response()->json($data, 200);
    }
}
