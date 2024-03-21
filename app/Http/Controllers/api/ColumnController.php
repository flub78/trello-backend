<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Helpers\UrlQuery;
use App\Http\Controllers\Controller;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    public function index(Request $request)
    {
        try {
            Log::Debug('ColumnController@index');

            if (array_key_exists('QUERY_STRING', $_SERVER)) {
                $queries = UrlQuery::queries($_SERVER['QUERY_STRING']);
            }
            $query = Column::query();

            if ($request->has('filter')) {
                $filters = $queries['filter'];

                foreach ($filters as $filter) {
                    Log::Debug('ColumnController@index filter: ' . $filter);

                    list($criteria, $value) = explode(':', $filter, 2);

                    $operator_found = false;
                    foreach (['<=', '>=', '<', '>', '~='] as $op) {
                        if (Str::startsWith($value, $op)) {
                            if ($op == '~=') {
                                $op = 'LIKE';
                                $value = substr($value, 2);
                                $value = '%' . $value . '%';

                            } else {                            
                                $value = ltrim($value, $op);
                            }
                            $query->where($criteria, $op, $value);
                            $operator_found = true;
                            break;
                        }
                    }
                    if (!$operator_found) {
                        $query->where($criteria, $value);
                    }

                }
            }

            if ($request->has('sort')) {
                $sorts = explode(',', $request->input('sort'));
                Log::Debug('sorting by', $sorts);

                foreach ($sorts as $sortCol) {
                    $sortDir = Str::startsWith($sortCol, '-') ? 'desc' : 'asc';
                    $sortCol = ltrim($sortCol, '-');

                    $query->orderBy($sortCol, $sortDir);
                }
            }

            if ($request->has('per_page') || $request->has('page')) {
                // request a specific page
                $page = $request->page;
                $per_page = $request->per_page;

                return $query->paginate($per_page);

            } else {
                $elements = $query->get(); // SELECT * FROM columns
                return response()->json($elements, 200);
            }
        
        } catch (\Exception $e) {

            Log::Error('ColumnController@index', ['message' => $e->getMessage()]);
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

            $element = Column::find($id); // SELECT * FROM columns WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(['status' => 404, 'message' => "Column $id not found"], 404);
            }

        } catch (\Exception $e) {

            Log::Error('ColumnController@show', ['message' => $e->getMessage()]);
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
				"tasks" => ["string", "max:255", "regex:/\'(.+?)\'|\"(.+?)\"/"],

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
			$element->tasks = $request->tasks;

            $element->save();


            $data = [
                'status' => 201,
                'column' => $element
            ];            
            Log::Debug('ColumnController@store saved in database', $data);
            return response()->json($element, 201);

        } catch (\Exception $e) {

            Log::Error('ColumnController@store', ['message' => $e->getMessage()]);
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
            Log::Debug("ColumnController@update $id");

            $validator = Validator::make($request->all(), [
                "name" => 'string|max:128',
				"board_id" => 'string|max:128|exists:boards,name',
				"tasks" => ["string", "max:255", "regex:/\'(.+?)\'|\"(.+?)\"/"],

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
                return response()->json(['status' => 404, 'message' => "Column $id not found"], 404);
            }

            if ($request->name) {
				$element->name = $request->name;
			}
			if ($request->board_id) {
				$element->board_id = $request->board_id;
			}
			if ($request->tasks) {
				$element->tasks = $request->tasks;
			}

            $element->save();

            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('ColumnController@update', ['message' => $e->getMessage()]);
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

            $element = Column::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Column $id not found"], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Column $id deleted",
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            Log::Error('ColumnController@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }

    }
}
