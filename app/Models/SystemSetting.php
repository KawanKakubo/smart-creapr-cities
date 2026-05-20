<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key, parsing boolean strings.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $val = $setting->value;
            if ($val === 'true') return true;
            if ($val === 'false') return false;
            return $val;
        }
        return $default;
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $valStr = is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
        self::updateOrCreate(['key' => $key], ['value' => $valStr]);
    }
}
