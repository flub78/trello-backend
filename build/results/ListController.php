<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\List;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class ListController
 * @package App\Http\Controllers\api
 */
class ListController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::Debug('ListController@index');

        $elements = List::all(); // SELECT * FROM lists

        $data = [
            'status' => 200,
            'lists' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("ListController@show $id");

        $element = List::find($id); // SELECT * FROM lists WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'List not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'list' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::Debug('ListController@store');

        $validator = Validator::make($request->all(), [
            "name" => 'required|string|max:128',
			"board_id" => 'required',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ListController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new List;
        $element->name = $request->name;
		$element->board_id = $request->board_id;


        $element->save();

        $data = [
            'status' => 200,
            'list' => $element,
        ];
        Log::Debug('ListController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        Log::Debug("ListController@update $id");

        $validator = Validator::make($request->all(), [
            "name" => 'string|max:128',
			"board_id" => '',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ListController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = List::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'List not found',
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
            'list' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("ListController@delete $id");

        $element = List::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'List not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "List $id deleted",
        ];

        return response()->json($data, 200);
    }
}
