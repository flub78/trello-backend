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
use Illuminate\Support\Facades\App;

/**
 * Class TaskController
 * @package App\Http\Controllers\api
 */
class TaskController extends Controller {
    //
    protected function set_locale(Request $request) {
        if ($request->has('lang')) {
            $locale = strtolower($request->input('lang'));
            if ($locale == 'gb') {
                $locale = 'en';
            }

            if (in_array($locale, ['en', 'fr'])) {
                App::setLocale($locale);
            } else {
                throw new \Exception('lang = ' . $locale . ' not supported');
            }
        }
    }
    /**
     * Display a list of the resource.
     */
    public function index(Request $request) {

        try {
            Log::Debug('TaskController@index');

            $query_string = $request->server('QUERY_STRING');
            if ($query_string) {
                $queries = UrlQuery::queries($query_string);
            }
            $query = Task::query();

            $query->join('columns', 'tasks.column_id', '=', 'columns.id');
            $query->select('tasks.*', 'columns.id as column_id_image');

            // Manage API language
            $this->set_locale($request);

            // filtering
            if ($request->has('filter')) {
                $filters = $queries['filter'];

                foreach ($filters as $filter) {
                    Log::Debug('TaskController@index filter: ' . $filter);

                    list($criteria, $value) = explode(':', $filter, 2);

                    // Should I appen the table name before the criteria ?
                    // name should become tables.name, etc.
                    // That could fix the amiguity when several tables in the join have fields with the same name...

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

            // sorting
            if ($request->has('sort')) {
                $sorts = explode(',', $request->input('sort'));
                Log::Debug('sorting by', $sorts);

                foreach ($sorts as $sortCol) {
                    $sortDir = Str::startsWith($sortCol, '-') ? 'desc' : 'asc';
                    $sortCol = ltrim($sortCol, '-');

                    $query->orderBy($sortCol, $sortDir);
                }
            }

            // pagination
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
                'error' => __('api.internal_error'),
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id) {
        try {
            Log::Debug("TaskController@show $id");

            // Manage API language
            $this->set_locale($request);

            $element = Task::find($id); // SELECT * FROM tasks WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(
                    [
                        'status' => 404,
                        'message' => __('api.not_found', ['elt' => $id])
                    ],
                    404
                );
            }
        } catch (\Exception $e) {

            Log::Error('TaskController@show', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error')
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            Log::Debug('TaskController@store');

            // Manage API language
            $this->set_locale($request);

            $validator = Validator::make($request->all(), [
                "name" => 'required|string|max:128',
                "description" => '',
                "column_id" => 'required|exists:columns,id',
                "due_date" => 'date',
                "completed" => 'required|boolean',
                "href" => 'string|max:255',
                "favorite" => 'required|boolean',
                "watched" => 'required|boolean',
                "image" => 'required|string|max:255',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => __('api.validation_error')
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
            $element->href = $request->href;
            $element->favorite = $request->favorite;
            $element->watched = $request->watched;
            $element->image = $request->image;

            $element->save();


            $data = [
                'status' => 201,
                'task' => $element
            ];
            Log::Debug('TaskController@store saved in database', $data);
            return response()->json($element, 201);
        } catch (\Exception $e) {

            $message = $e->getMessage();
            Log::Error('TaskController@store', ['message' => $message]);

            $status = 500;
            $error = __('api.internal_error');

            if (Str::contains($message, 'Integrity constraint violation')) {
                $status = 422;

                if (Str::contains($message, 'Duplicate entry')) {
                    $pattern = '/^.*Duplicate entry (.*)for key (.*)\(Connection: (.*), SQL.*$/';
                    if (preg_match($pattern, $message, $matches)) {
                        $message = __('api.duplicate_entry') . " " .  $matches[1] . " "
                            . __('api.for_index') . " " . $matches[2];
                    }
                }
            }

            $data = [
                'status' => $status,
                'error' => $error,
                'message' => $message
            ];

            return response()->json($data, $status);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        try {
            Log::Debug("TaskController@update $id");

            // Manage API language
            $this->set_locale($request);

            $validator = Validator::make($request->all(), [
                "name" => 'string|max:128',
                "description" => '',
                "column_id" => 'exists:columns,id',
                "due_date" => 'date',
                "completed" => 'boolean',
                "href" => 'string|max:255',
                "favorite" => 'boolean',
                "watched" => 'boolean',
                "image" => 'string|max:255',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => __('api.validation_error')
                ];
                Log::Debug('TaskController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = Task::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => __('api.not_found', ['elt' => $id])], 404);
            }

            if ($request->exists('name')) {
                $element->name = $request->name;
            }
            if ($request->exists('description')) {
                $element->description = $request->description;
            }
            if ($request->exists('column_id')) {
                $element->column_id = $request->column_id;
            }
            if ($request->exists('due_date')) {
                $element->due_date = $request->due_date;
            }
            if ($request->exists('completed')) {
                $element->completed = $request->completed;
            }
            if ($request->exists('href')) {
                $element->href = $request->href;
            }
            if ($request->exists('favorite')) {
                $element->favorite = $request->favorite;
            }
            if ($request->exists('watched')) {
                $element->watched = $request->watched;
            }
            if ($request->exists('image')) {
                $element->image = $request->image;
            }

            $element->save();

            return response()->json($element, 200);
        } catch (\Exception $e) {

            $message = $e->getMessage();
            Log::Error('TaskController@update', ['message' => $message]);

            $status = 500;
            $error = __('api.internal_error');

            if (Str::contains($message, 'Integrity constraint violation')) {
                $status = 422;

                if (Str::contains($message, 'Duplicate entry')) {
                    $pattern = '/^.*Duplicate entry (.*)for key (.*)\(Connection: (.*), SQL.*$/';
                    if (preg_match($pattern, $message, $matches)) {
                        $message = __('api.duplicate_entry') . " " .  $matches[1] . " "
                            . __('api.for_index') . " " . $matches[2];
                    }
                }
            }

            $data = [
                'status' => $status,
                'error' => $error,
                'message' => $message
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id) {
        try {
            Log::Debug("TaskController@delete $id");

            // Manage API language
            $this->set_locale($request);

            $element = Task::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => __('api.not_found', ['elt' => $id])], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => __('api.element_deleted', ['elt' => $id])
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {

            Log::Error('TaskController@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error')
            ];

            return response()->json($data, 500);
        }
    }
}
