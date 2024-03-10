<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class ChecklistItemController
 * @package App\Http\Controllers\api
 */
class ChecklistItemController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::Debug('ChecklistItemController@index');

        $elements = ChecklistItem::all(); // SELECT * FROM checklist_items

        $data = [
            'status' => 200,
            'checklist_items' => $elements,
        ];

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::Debug("ChecklistItemController@show $id");

        $element = ChecklistItem::find($id); // SELECT * FROM checklist_items WHERE id = $id

        if (!$element) {
            // 404 Not Found
            $data = [
                'status' => 404,
                'message' => 'ChecklistItem not found',
            ];

            return response()->json($data, 404);
        }

        // 200 OK
        $data = [
            'status' => 200,
            'checklist_item' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::Debug('ChecklistItemController@store');

        $validator = Validator::make($request->all(), [
            "description" => 'required|string|max:128',
			"done" => 'required',
			"checklist_id" => 'required',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ChecklistItemController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = new ChecklistItem;
        $element->description = $request->description;
		$element->done = $request->done;
		$element->checklist_id = $request->checklist_id;


        $element->save();

        $data = [
            'status' => 200,
            'checklist_item' => $element,
        ];
        Log::Debug('ChecklistItemController@store saved in database', $data);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        Log::Debug("ChecklistItemController@update $id");

        $validator = Validator::make($request->all(), [
            "description" => 'string|max:128',
			"done" => '',
			"checklist_id" => '',

        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ];
            Log::Debug('ChecklistItemController@store validation failed', $data);

            return response()->json($data, 422);
        }

        $element = ChecklistItem::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'ChecklistItem not found',
            ];

            return response()->json($data, 404);
        }

        if ($request->description) {
			$element->description = $request->description;
		}
		if ($request->done) {
			$element->done = $request->done;
		}
		if ($request->checklist_id) {
			$element->checklist_id = $request->checklist_id;
		}

        $element->save();

        $data = [
            'status' => 200,
            'checklist_item' => $element,
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::Debug("ChecklistItemController@delete $id");

        $element = ChecklistItem::find($id);

        if (!$element) {
            $data = [
                'status' => 404,
                'message' => 'ChecklistItem not found',
            ];

            return response()->json($data, 404);
        }

        $element->delete();

        $data = [
            'status' => 200,
            'message' => "ChecklistItem $id deleted",
        ];

        return response()->json($data, 200);
    }
}
