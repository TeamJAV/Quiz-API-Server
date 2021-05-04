<?php


namespace App\Container;


use Illuminate\Support\Str;

class SettingLaunch
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function settings($key): array
    {
        return [
            'key' => $key,
            'token' => Str::random(16),
            'value' => $this->value
        ];
    }
}
