<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class BoardController
 * @package App\Http\Controllers\api
 */
class BoardController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            Log::Debug('BoardController@index');

            $query = Board::query();

            if ($request->has('filter')) {
                #echo "Filter: " . $request->filter;
            }

            if ($request->has('sort')) {
                #echo "Sort: " . $request->sort;
            }

            if ($request->has('per_page') || $request->has('page')) {
                // request a specific page
                $page = $request->page;
                $per_page = $request->per_page;

                return $query->paginate($per_page);

            } else {
                $elements = $query->get(); // SELECT * FROM boards
                return response()->json($elements, 200);
            }

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
            Log::Debug("BoardController@show $id");

            $element = Board::find($id); // SELECT * FROM boards WHERE id = $id

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(['status' => 404, 'message' => "Board $id not found"], 404);
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
            Log::Debug('BoardController@store');

            $validator = Validator::make($request->all(), [
                "name" => 'required|string|max:128',
                "description" => 'string|max:255',
                "email" => 'required|string|max:128|email',
                "favorite" => 'required|boolean',
                "href" => 'string|max:255',
                "image" => 'string|max:255',
                "theme" => 'in:light,dark',
                "lists" => ["string", "max:255", "regex:/\'(.+?)\'|\"(.+?)\"/"],

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('BoardController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new Board;
            $element->name = $request->name;
            $element->description = $request->description;
            $element->email = $request->email;
            $element->favorite = $request->favorite;
            $element->href = $request->href;
            $element->image = $request->image;
            $element->theme = $request->theme;
            $element->lists = $request->lists;

            $element->save();

            $data = [
                'status' => 201,
                'board' => $element,
            ];
            Log::Debug('BoardController@store saved in database', $data);
            return response()->json($element, 201);

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
            Log::Debug("BoardController@update $id");

            $validator = Validator::make($request->all(), [
                "name" => 'string|max:128',
                "description" => 'string|max:255',
                "email" => 'string|max:128|email',
                "favorite" => 'boolean',
                "href" => 'string|max:255',
                "image" => 'string|max:255',
                "theme" => 'in:light,dark',
                "lists" => ["string", "max:255", "regex:/\'(.+?)\'|\"(.+?)\"/"],

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('BoardController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = Board::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Board $id not found"], 404);
            }

            if ($request->name) {
                $element->name = $request->name;
            }
            if ($request->description) {
                $element->description = $request->description;
            }
            if ($request->email) {
                $element->email = $request->email;
            }
            if ($request->favorite) {
                $element->favorite = $request->favorite;
            }
            if ($request->href) {
                $element->href = $request->href;
            }
            if ($request->image) {
                $element->image = $request->image;
            }
            if ($request->theme) {
                $element->theme = $request->theme;
            }
            if ($request->lists) {
                $element->lists = $request->lists;
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
            Log::Debug("BoardController@delete $id");

            $element = Board::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Board $id not found"], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Board $id deleted",
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
