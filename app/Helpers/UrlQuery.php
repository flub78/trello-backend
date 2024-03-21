<?php
namespace App\Helpers;

class UrlQuery
{

    /**
     * Parse an URL query string and return an associative array
     * with the keys pointing on arrays of values.
     *
     * It is a workaround, I did not find a way to support multiple parameters with Laravel Request
     *
     * For example, I want the REST API to support multiple filter conditions for
     * range selection. The URL query string would look like this:
     * filter=name:%3Etask%202&filter=name:%3C=Task%205
     * filter=name:>task 2&filter=name:<=Task 5
     */
    public static function queries(string $input): array
    {
        $queries = explode('&', $input);
        $result = [];
        foreach ($queries as $query) {
            $q = urldecode($query);

            $pattern = '/^(.+?)=(.*)$/i';
            if (preg_match($pattern, $q, $matches)) {
                $key = $matches[1];
                $value = $matches[2];
                // echo 'key: ' . $key . ' value: ' . $value . '<br>';
                if (array_key_exists($key, $result)) {
                    $result[$key][] = $value;
                } else {
                    $result[$key] = [$value];
                }
            }
        }
        return $result;
    }
}
