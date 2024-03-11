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
        Log::Debug('ColumnController@index');

        $elements = Column::all(); // SELECT * FROM columns

        $data = [
            'status' => 200,
            'columns' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("ColumnController@show $id");

        $element = Column::find($id); // SELECT * FROM columns WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'Column not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'column' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::Debug('ColumnController@store');

        $validator = Validator::make($request->all(), [
            "name" => 'required|string|max:128',
			"board_id" => 'required|exists:boards,id',

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
            'column' => $element,
        ];
        Log::Debug('ColumnController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        Log::Debug("ColumnController@update $id");

        $validator = Validator::make($request->all(), [
            "name" => 'string|max:128',
			"board_id" => 'exists:boards,id',

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

        $element = Column::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'Column not found',
            ];

            return response()->json($data, 404);
        }

        if ($request->name) {
			$element->name = $request->name;
		}
		if ($request->board_id) {
			$element->board_id = $request->board_id;
		}

        $element->save();

        $data = [
            'status' => 200,
            'column' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("ColumnController@delete $id");

        $element = Column::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'Column not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "Column $id deleted",
        ];

        return response()->json($data, 200);
    }
}
