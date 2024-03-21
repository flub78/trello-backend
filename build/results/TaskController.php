<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Helpers\UrlQuery;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class TaskController
 * @package App\Http\Controllers\api
 */
class TaskController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            Log::Debug('TaskController@index');

            $query_string = $request->server('QUERY_STRING');
            if ($query_string) {
                $queries = UrlQuery::queries($query_string);
            }
            $query = Task::query();

            if ($request->has('filter')) {
                $filters = $queries['filter'];

                foreach ($filters as $filter) {
                    Log::Debug('TaskController@index filter: ' . $filter);

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
                $elements = $query->get(); // SELECT * FROM tasks
                return response()->json($elements, 200);
            }
        
        } catch (\Exception $e) {

            Log::Error('TaskController@index', ['message' => $e->getMessage()]);
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
            Log::Debug("TaskController@show $id");

            $element = Task::find($id); // SELECT * FROM tasks WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(['status' => 404, 'message' => "Task $id not found"], 404);
            }

        } catch (\Exception $e) {

            Log::Error('TaskController@show', ['message' => $e->getMessage()]);
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
            Log::Debug('TaskController@store');

            $validator = Validator::make($request->all(), [
                "name" => 'required|string|max:128',
				"description" => '',
				"column_id" => 'required|exists:columns,id',
				"due_date" => 'date',
				"completed" => 'required|boolean',
				"image" => 'string|max:255',
				"href" => 'string|max:255',
				"favorite" => 'required|boolean',
				"watched" => 'required|boolean',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('TaskController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new Task;
            $element->name = $request->name;
			$element->description = $request->description;
			$element->column_id = $request->column_id;
			$element->due_date = $request->due_date;
			$element->completed = $request->completed;
			$element->image = $request->image;
			$element->href = $request->href;
			$element->favorite = $request->favorite;
			$element->watched = $request->watched;

            $element->save();


            $data = [
                'status' => 201,
                'task' => $element
            ];            
            Log::Debug('TaskController@store saved in database', $data);
            return response()->json($element, 201);

        } catch (\Exception $e) {

            Log::Error('TaskController@store', ['message' => $e->getMessage()]);
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
            Log::Debug("TaskController@update $id");

            $validator = Validator::make($request->all(), [
                "name" => 'string|max:128',
				"description" => '',
				"column_id" => 'exists:columns,id',
				"due_date" => 'date',
				"completed" => 'boolean',
				"image" => 'string|max:255',
				"href" => 'string|max:255',
				"favorite" => 'boolean',
				"watched" => 'boolean',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('TaskController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = Task::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Task $id not found"], 404);
            }

            if ($request->name) {
				$element->name = $request->name;
			}
			if ($request->description) {
				$element->description = $request->description;
			}
			if ($request->column_id) {
				$element->column_id = $request->column_id;
			}
			if ($request->due_date) {
				$element->due_date = $request->due_date;
			}
			if ($request->completed) {
				$element->completed = $request->completed;
			}
			if ($request->image) {
				$element->image = $request->image;
			}
			if ($request->href) {
				$element->href = $request->href;
			}
			if ($request->favorite) {
				$element->favorite = $request->favorite;
			}
			if ($request->watched) {
				$element->watched = $request->watched;
			}

            $element->save();

            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('TaskController@update', ['message' => $e->getMessage()]);
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
            Log::Debug("TaskController@delete $id");

            $element = Task::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "Task $id not found"], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "Task $id deleted",
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            Log::Error('TaskController@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }

    }
}
