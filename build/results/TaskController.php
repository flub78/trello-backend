<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaskController
 * @package App\Http\Controllers\api
 */
class TaskController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::Debug('TaskController@index');

        $elements = Task::all(); // SELECT * FROM tasks

        $data = [
            'status' => 200,
            'tasks' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("TaskController@show $id");

        $element = Task::find($id); // SELECT * FROM tasks WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'Task not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'task' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::Debug('TaskController@store');

        $validator = Validator::make($request->all(), [
            "name" => 'required|string|max:128',
			"description" => '',
			"column_id" => 'required|exists:columns,id',
			"due_date" => 'date',
			"completed" => 'required|boolean',
			"image" => 'string|max:255',
			"href" => 'string|max:255',
			"favorite" => 'required|boolean',
			"watched" => 'required|boolean',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('TaskController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new Task;
        $element->name = $request->name;
		$element->description = $request->description;
		$element->column_id = $request->column_id;
		$element->due_date = $request->due_date;
		$element->completed = $request->completed;
		$element->image = $request->image;
		$element->href = $request->href;
		$element->favorite = $request->favorite;
		$element->watched = $request->watched;


        $element->save();

        $data = [
            'status' => 200,
            'task' => $element,
        ];
        Log::Debug('TaskController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        Log::Debug("TaskController@update $id");

        $validator = Validator::make($request->all(), [
            "name" => 'string|max:128',
			"description" => '',
			"column_id" => 'exists:columns,id',
			"due_date" => 'date',
			"completed" => 'boolean',
			"image" => 'string|max:255',
			"href" => 'string|max:255',
			"favorite" => 'boolean',
			"watched" => 'boolean',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('TaskController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = Task::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'Task not found',
            ];

            return response()->json($data, 404);
        }

        if ($request->name) {
			$element->name = $request->name;
		}
		if ($request->description) {
			$element->description = $request->description;
		}
		if ($request->column_id) {
			$element->column_id = $request->column_id;
		}
		if ($request->due_date) {
			$element->due_date = $request->due_date;
		}
		if ($request->completed) {
			$element->completed = $request->completed;
		}
		if ($request->image) {
			$element->image = $request->image;
		}
		if ($request->href) {
			$element->href = $request->href;
		}
		if ($request->favorite) {
			$element->favorite = $request->favorite;
		}
		if ($request->watched) {
			$element->watched = $request->watched;
		}

        $element->save();

        $data = [
            'status' => 200,
            'task' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("TaskController@delete $id");

        $element = Task::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'Task not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "Task $id deleted",
        ];

        return response()->json($data, 200);
    }
}
