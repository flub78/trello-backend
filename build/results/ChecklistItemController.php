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
        try {
            Log::Debug('ChecklistItemController@index');

            $elements = ChecklistItem::all(); // SELECT * FROM checklist_items

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
            Log::Debug("ChecklistItemController@show $id");

            $element = ChecklistItem::findOrFail($id); // SELECT * FROM checklist_items WHERE id = $id 

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
            Log::Debug('ChecklistItemController@store');

            $validator = Validator::make($request->all(), [
                "description" => 'required|string|max:128',
			"done" => 'required|boolean',
			"checklist_id" => 'required|exists:checklists,id',

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
                'checklist_item' => $element
            ];            
            Log::Debug('ChecklistItemController@store saved in database', $data);
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
            Log::Debug("ChecklistItemController@update $id");

            $validator = Validator::make($request->all(), [
                "description" => 'string|max:128',
			"done" => 'boolean',
			"checklist_id" => 'exists:checklists,id',

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

            $element = ChecklistItem::findOrFail($id);
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
            Log::Debug("ChecklistItemController@delete $id");

            $element = ChecklistItem::findOrFail($id);

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "ChecklistItem $id deleted",
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
