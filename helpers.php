<?php

function post($key): string | array | false
{
    return $_POST[$key] ?? false;
}

function get($key, $default = ''): string | array
{
    return $_GET[$key] ?? $default;
}

function session($key, $default = '') {
    $keys = explode('.', $key);
    $value = $_SESSION;
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    return $value;
}

function printData($data, $html = 'pre'): void
{
    if ($html == 'pre') {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    } else if ($html == 'h1') {
        echo '<h1>';
        print_r($data);
        echo '</h1>';
    }
}