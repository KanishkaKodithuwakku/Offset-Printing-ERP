<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'title',
        'color',
        'background',
    ];

    /*
     * Validation rules if using Form Requests or manual validation
     */
    public static $rules = [
        'title' => 'required|string|max:100|unique:tags,title',
        'color' => 'required|string|size:6|regex:/^[a-fA-F0-9]{6}$/',
        'background' => 'required|string|size:6|regex:/^[a-fA-F0-9]{6}$/',
    ];

    public function entries()
    {
        return $this->hasMany(Entry::class, 'tag_id');
    }

    /*
     * Custom Methods
     */
    public static function listAll()
    {
        return self::orderBy('title')->pluck('title', 'id')->prepend('(None)', 0);
    }

    public static function fetchAll()
    {
        return self::all()->mapWithKeys(function ($tag) {
            return [$tag->id => [
                'id' => $tag->id,
                'title' => $tag->title,
                'color' => $tag->color,
                'background' => $tag->background,
            ]];
        });
    }
}
