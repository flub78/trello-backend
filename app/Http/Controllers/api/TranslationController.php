<?php

/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Controllers\api;

use App\Helpers\UrlQuery;
use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;


/**
 * Class TranslationController
 * @package App\Http\Controllers\api
 */
class TranslationController extends Controller {

    //

    protected function set_locale(Request $request) {
        if ($request->has('lang')) {
            $locale = $request->input('lang');

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
            Log::Debug('TranslationController@index');

            $query_string = $request->server('QUERY_STRING');
            if ($query_string) {
                $queries = UrlQuery::queries($query_string);
            }
            $query = Translation::query();

            // Manage API language
            $this->set_locale($request);
        } catch (\Exception $e) {

            Log::Error('BoardController@index', ['message' => $e->getMessage()]);
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
            Log::Debug("TranslationsController@show $id");

            // Manage API language
            $this->set_locale($request);

            $element = Board::find($id); // SELECT * FROM boards WHERE id = $id 

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

            Log::Error('BoardController@show', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error'),
            ];

            return response()->json($data, 500);
        }
    }
}
