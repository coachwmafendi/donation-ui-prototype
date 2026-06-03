<?php

namespace App\Helpers;

class PublicId
{
    /**
     * Generate a unique 8-character uppercase public ID
     */
    public static function generate(): string
    {
        return strtoupper(\Str::random(8));
    }

    /**
     * Generate and ensure uniqueness against a model
     */
    public static function generateFor(string $modelClass, string $column = 'public_id'): string
    {
        do {
            $id = self::generate();
        } while ($modelClass::where($column, $id)->exists());

        return $id;
    }
}
