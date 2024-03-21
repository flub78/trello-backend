<?php
namespace App\Helpers;

class UrlQuery
{
    private $data = null;
    public function __construct($data)
    {
        $this->data = $data;
    }

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
                # echo 'key: ' . $key . ' value: ' . $value . '<br>';
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
