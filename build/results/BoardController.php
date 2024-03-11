<?php

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
    public function index()
    {
        try {
            Log::Debug('BoardController@index');

            $elements = Board::all(); // SELECT * FROM boards

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
            Log::Debug("BoardController@show $id");

            $element = Board::findOrFail($id); // SELECT * FROM boards WHERE id = $id 

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
            Log::Debug('BoardController@store');

            $validator = Validator::make($request->all(), [
                "name" => 'required|string|max:128',
			"description" => 'string|max:255',
			"email" => 'required|string|max:128|email',
			"favorite" => 'required|boolean',
			"href" => 'string|max:255',
			"image" => 'string|max:255',
			"theme" => 'in:light,dark',

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

            $element->save();


            $data = [
                'status' => 200,
                'board' => $element
            ];            
            Log::Debug('BoardController@store saved in database', $data);
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
            Log::Debug("BoardController@update $id");

            $validator = Validator::make($request->all(), [
                "name" => 'string|max:128',
			"description" => 'string|max:255',
			"email" => 'string|max:128|email',
			"favorite" => 'boolean',
			"href" => 'string|max:255',
			"image" => 'string|max:255',
			"theme" => 'in:light,dark',

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

            $element = Board::findOrFail($id);
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

            $element = Board::findOrFail($id);

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
