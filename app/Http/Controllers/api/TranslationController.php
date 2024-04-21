<?php

/**
 * TranslationController.php
 * 
 * String translation API
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

/**
 * Class TranslationController
 * @package App\Http\Controllers\api
 */
class TranslationController extends Controller {

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
     * Display all translations
     */
    public function index(Request $request) {
        try {
            Log::Debug('TranslationController@index');

            // Manage API language
            $this->set_locale($request);

            $data = [
                'status' => 200,
                'locale' => App::getLocale(),
            ];

            $tables = Schema::tableList();
            $dict = [];
            foreach ($tables as $table) {
                $dict[$table] = __($table . '.table');
                $fields = Schema::fieldList($table);

                $fields_trans = [];
                foreach ($fields as $field) {
                    $fields_trans[$field] = __($table . '.' . $field);
                }
                $data['fields'][$table] = $fields_trans;
            }
            $data['tables'] = $dict;


            return response()->json($data, 200);
        } catch (\Exception $e) {

            Log::Error('TranslationController@index', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error'),
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Display the translations for a table
     */
    public function show(Request $request, $id) {
        try {
            Log::Debug("TranslationsController@show $id");

            // Manage API language
            $this->set_locale($request);

            $data = [
                'status' => 200,
                'locale' => App::getLocale(),
            ];

            $table = $id;
            $fields = Schema::fieldList($table);

            $fields_trans = [];
            foreach ($fields as $field) {
                $fields_trans[$field] = __($table . '.' . $field);
            }
            $data['fields'] = $fields_trans;

            $data['table'] = __($table . '.table');
            $data['element'] = __($table . '.element');

            return response()->json($data, 200);
        } catch (\Exception $e) {

            Log::Error('TranslationController@show', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => __('api.internal_error'),
            ];

            return response()->json($data, 500);
        }
    }
}
