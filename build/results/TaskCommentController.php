<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Helpers\UrlQuery;
use App\Http\Controllers\Controller;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

/**
 * Class TaskCommentController
 * @package App\Http\Controllers\api
 */
class TaskCommentController extends Controller {
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
            Log::Debug('TaskCommentController@index');

            $query_string = $request->server('QUERY_STRING');
            if ($query_string) {
                $queries = UrlQuery::queries($query_string);
            }
            $query = TaskComment::query();

            $query->join('tasks', 'task_comments.task_id', '=', 'tasks.id');
			$query->select('task_comments.*', 'tasks.id as task_id_image');

            // Manage API language
            $this->set_locale($request);

            // filtering
            if ($request->has('filter')) {
                $filters = $queries['filter'];

                foreach ($filters as $filter) {
                    Log::Debug('TaskCommentController@index filter: ' . $filter);

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
                $elements = $query->get(); // SELECT * FROM task_comments
                return response()->json($elements, 200);
            }
        
        } catch (\Exception $e) {

            Log::Error('TaskCommentController@index', ['message' => $e->getMessage()]);
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
    public function show(Request $request, $id)
    {
        try {
            Log::Debug("TaskCommentController@show $id");

            // Manage API language
            $this->set_locale($request);

            $element = TaskComment::find($id); // SELECT * FROM task_comments WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(
                    [
                        'status' => 404,
                        'message' => __('api.not_found', ['elt' => $id])
                    ], 404);
            }

        } catch (\Exception $e) {

            Log::Error('TaskCommentController@show', ['message' => $e->getMessage()]);
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
    public function store(Request $request)
    {
        try {
            Log::Debug('TaskCommentController@store');

            // Manage API language
            $this->set_locale($request);

            $validator = Validator::make($request->all(), [
                "text" => '',
				"from_email" => 'required|string|max:128|email',
				"task_id" => 'required|exists:tasks,id',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => __('api.validation_error')
                ];
                Log::Debug('TaskCommentController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new TaskComment;
            $element->text = $request->text;
			$element->from_email = $request->from_email;
			$element->task_id = $request->task_id;

            $element->save();


            $data = [
                'status' => 201,
                'task_comment' => $element
            ];            
            Log::Debug('TaskCommentController@store saved in database', $data);
            return response()->json($element, 201);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            Log::Error('TaskCommentController@store', ['message' => $message]);

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
    public function update(Request $request, $id)
    {
        try {
            Log::Debug("TaskCommentController@update $id");

            // Manage API language
            $this->set_locale($request);

            $validator = Validator::make($request->all(), [
                "text" => '',
				"from_email" => 'string|max:128|email',
				"task_id" => 'exists:tasks,id',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => __('api.validation_error')
                ];
                Log::Debug('TaskCommentController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = TaskComment::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => __('api.not_found', ['elt' => $id])], 404);                
            }

            if ($request->exists('text')) {
				$element->text = $request->text;
			}
			if ($request->exists('from_email')) {
				$element->from_email = $request->from_email;
			}
			if ($request->exists('task_id')) {
				$element->task_id = $request->task_id;
			}

            $element->save();

            return response()->json($element, 200);

        } catch (\Exception $e) {

            $message = $e->getMessage();
            Log::Error('TaskCommentController@update', ['message' => $message]);

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
    public function destroy(Request $request, $id)
    {
        try {
            Log::Debug("TaskCommentController@delete $id");

            // Manage API language
            $this->set_locale($request);
            
            $element = TaskComment::find($id);
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

            Log::Error('TaskCommentController@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error')
            ];

            return response()->json($data, 500);
        }

    }
}
