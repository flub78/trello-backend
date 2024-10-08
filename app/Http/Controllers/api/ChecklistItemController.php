<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Helpers\UrlQuery;
use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

/**
 * Class ChecklistItemController
 * @package App\Http\Controllers\api
 */
class ChecklistItemController extends Controller {
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
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        
        try {
            Log::Debug('ChecklistItemController@index');

            $query_string = $request->server('QUERY_STRING');
            if ($query_string) {
                $queries = UrlQuery::queries($query_string);
            }
            $query = ChecklistItem::query();

            // Manage API language
            $this->set_locale($request);

            // filtering
            if ($request->has('filter')) {
                $filters = $queries['filter'];

                foreach ($filters as $filter) {
                    Log::Debug('ChecklistItemController@index filter: ' . $filter);

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
                $elements = $query->get(); // SELECT * FROM checklist_items
                return response()->json($elements, 200);
            }
        
        } catch (\Exception $e) {

            Log::Error('ChecklistItemController@index', ['message' => $e->getMessage()]);
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
            Log::Debug("ChecklistItemController@show $id");

            // Manage API language
            $this->set_locale($request);

            $element = ChecklistItem::find($id); // SELECT * FROM checklist_items WHERE id = $id 

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

            Log::Error('ChecklistItemController@show', ['message' => $e->getMessage()]);
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
            Log::Debug('ChecklistItemController@store');

            // Manage API language
            $this->set_locale($request);

            $validator = Validator::make($request->all(), [
                "description" => 'required|string|max:128',
				"done" => 'required',
				"checklist_id" => 'required|exists:checklists,id',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => __('api.validation_error')
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
                'status' => 201,
                'checklist_item' => $element
            ];            
            Log::Debug('ChecklistItemController@store saved in database', $data);
            return response()->json($element, 201);

        } catch (\Exception $e) {

            Log::Error('ChecklistItemController@store', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error'),
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
            Log::Debug("ChecklistItemController@update $id");

            // Manage API language
            $this->set_locale($request);

            $validator = Validator::make($request->all(), [
                "description" => 'string|max:128',
				"done" => '',
				"checklist_id" => 'exists:checklists,id',

            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => __('api.validation_error')
                ];
                Log::Debug('ChecklistItemController@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = ChecklistItem::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => __('api.not_found', ['elt' => $id])], 404);                
            }

            if ($request->exists('description')) {
				$element->description = $request->description;
			}
			if ($request->exists('done')) {
				$element->done = $request->done;
			}
			if ($request->exists('checklist_id')) {
				$element->checklist_id = $request->checklist_id;
			}

            $element->save();

            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('ChecklistItemController@update', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error')
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
            Log::Debug("ChecklistItemController@delete $id");

            // Manage API language
            $this->set_locale($request);
            
            $element = ChecklistItem::find($id);
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

            Log::Error('ChecklistItemController@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error')
            ];

            return response()->json($data, 500);
        }

    }
}
