<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Helpers\UrlQuery;
use App\Http\Controllers\Controller;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    public function index(Request $request)
    {
        try {
            Log::Debug('ChecklistController@index');

            $query_string = $request->server('QUERY_STRING');
            if ($query_string) {
                $queries = UrlQuery::queries($query_string);
            }
            $query = Checklist::query();

            if ($request->has('filter')) {
                $filters = $queries['filter'];

                foreach ($filters as $filter) {
                    Log::Debug('ChecklistController@index filter: ' . $filter);

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
                $elements = $query->get(); // SELECT * FROM checklists
                return response()->json($elements, 200);
            }
        
        } catch (\Exception $e) {

            Log::Error('ChecklistController@index', ['message' => $e->getMessage()]);
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

            $element = Checklist::find($id); // SELECT * FROM checklists WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(['status' => 404, 'message' => "Checklist $id not found"], 404);
            }

        } catch (\Exception $e) {

            Log::Error('ChecklistController@show', ['message' => $e->getMessage()]);
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
                'status' => 201,
                'checklist' => $element
            ];            
            Log::Debug('ChecklistController@store saved in database', $data);
            return response()->json($element, 201);

        } catch (\Exception $e) {

            Log::Error('ChecklistController@store', ['message' => $e->getMessage()]);
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

            $element = Checklist::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Checklist $id not found"], 404);
            }

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

            Log::Error('ChecklistController@update', ['message' => $e->getMessage()]);
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

            $element = Checklist::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Checklist $id not found"], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Checklist $id deleted",
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            Log::Error('ChecklistController@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }

    }
}
