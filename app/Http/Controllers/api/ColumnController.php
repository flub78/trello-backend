<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class ColumnController
 * @package App\Http\Controllers\api
 */
class ColumnController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        Log::Debug('ColumnController@index');

        $elements = Column::all(); // SELECT * FROM columns

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
        Log::Debug("ColumnController@show $id");

            $element = Column::findOrFail($id); // SELECT * FROM columns WHERE id = $id 

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
        Log::Debug('ColumnController@store');

        $validator = Validator::make($request->all(), [
            "name" => 'required|string|max:128',
			"board_id" => 'required|string|max:128|exists:boards,name',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ColumnController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new Column;
        $element->name = $request->name;
		$element->board_id = $request->board_id;

            $element->save();


        $data = [
            'status' => 200,
                'column' => $element
        ];
        Log::Debug('ColumnController@store saved in database', $data);
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
        Log::Debug("ColumnController@update $id");

        $validator = Validator::make($request->all(), [
            "name" => 'string|max:128',
			"board_id" => 'string|max:128|exists:boards,name',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ColumnController@store validation failed', $data);

            return response()->json($data, 422);
        }

            $element = Column::findOrFail($id);
        if ($request->name) {
			$element->name = $request->name;
		}
		if ($request->board_id) {
			$element->board_id = $request->board_id;
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
        Log::Debug("ColumnController@delete $id");

            $element = Column::findOrFail($id);

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Column $id deleted",
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
