<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{{class}};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class {{class}}Controller
 * @package App\Http\Controllers\api
 */
class {{class}}Controller extends Controller
{
    //

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            Log::Debug('{{class}}Controller@index');

            $query = {{class}}::query();

            if ($request->has('filter')) {
                $filters = explode(',', $request->input('filter'));

                foreach ($filters as $filter) {
                    list($criteria, $value) = explode(':', $filter, 2);

                    // return $query->where('name_en', 'LIKE', '%' . $keywords . '%');

                    $operator_found = false;
                    foreach (['<=', '>=', '<', '>'] as $op) {
                        if (Str::startsWith($value, $op)) {
                            $value = ltrim($value, $op);
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
                $elements = $query->get(); // SELECT * FROM {{element}}s
                return response()->json($elements, 200);
            }
        
        } catch (\Exception $e) {

            Log::Error('{{class}}Controller@index', ['message' => $e->getMessage()]);
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
            Log::Debug("{{class}}Controller@show $id");

            $element = {{class}}::find($id); // SELECT * FROM {{element}}s WHERE id = $id 

            if ($element) {
                return response()->json($element, 200);
            } else {
                return response()->json(['status' => 404, 'message' => "{{class}} $id not found"], 404);
            }

        } catch (\Exception $e) {

            Log::Error('{{class}}Controller@show', ['message' => $e->getMessage()]);
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
            Log::Debug('{{class}}Controller@store');

            $validator = Validator::make($request->all(), [
                {{#cg}} create_validation_rules 4 {{/cg}}
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('{{class}}Controller@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new {{class}};
            {{#cg}} create_set_attributes {{/cg}}
            $element->save();


            $data = [
                'status' => 201,
                '{{element}}' => $element
            ];            
            Log::Debug('{{class}}Controller@store saved in database', $data);
            return response()->json($element, 201);

        } catch (\Exception $e) {

            Log::Error('{{class}}Controller@store', ['message' => $e->getMessage()]);
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
            Log::Debug("{{class}}Controller@update $id");

            $validator = Validator::make($request->all(), [
                {{#cg}} update_validation_rules {{/cg}}
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('{{class}}Controller@store validation failed', $data);

                return response()->json($data, 422);
            }

            $element = {{class}}::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "{{class}} $id not found"], 404);
            }

            {{#cg}} update_set_attributes {{/cg}}
            $element->save();

            return response()->json($element, 200);

        } catch (\Exception $e) {

            Log::Error('{{class}}Controller@update', ['message' => $e->getMessage()]);
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
            Log::Debug("{{class}}Controller@delete $id");

            $element = {{class}}::find($id);
            if (!$element) {
                return response()->json(['status' => 404, 'message' => "{{class}} $id not found"], 404);
            }

            $element->delete();

            $data = [
                'status' => 200,
                'message' => "{{class}} $id deleted",
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            Log::Error('{{class}}Controller@destroy', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }

    }
}
