<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class ChecklistController
 * @package App\Http\Controllers\api
 */
class ChecklistController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        Log::Debug('ChecklistController@index');

        $elements = Checklist::all(); // SELECT * FROM checklists

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
        Log::Debug("ChecklistController@show $id");

            $element = Checklist::findOrFail($id); // SELECT * FROM checklists WHERE id = $id 

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
        Log::Debug('ChecklistController@store');

        $validator = Validator::make($request->all(), [
            "name" => 'required|string|max:128',
			"description" => 'required|string|max:128',
			"task_id" => 'required|exists:tasks,id',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ChecklistController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new Checklist;
        $element->name = $request->name;
		$element->description = $request->description;
		$element->task_id = $request->task_id;

            $element->save();


        $data = [
            'status' => 200,
                'checklist' => $element
        ];
        Log::Debug('ChecklistController@store saved in database', $data);
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
        Log::Debug("ChecklistController@update $id");

        $validator = Validator::make($request->all(), [
            "name" => 'string|max:128',
			"description" => 'string|max:128',
			"task_id" => 'exists:tasks,id',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ChecklistController@store validation failed', $data);

            return response()->json($data, 422);
        }

            $element = Checklist::findOrFail($id);
        if ($request->name) {
			$element->name = $request->name;
		}
		if ($request->description) {
			$element->description = $request->description;
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
        Log::Debug("ChecklistController@delete $id");

            $element = Checklist::findOrFail($id);

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Checklist $id deleted",
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
