<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Category extends Model
{
    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }
    public function products(){
        return $this->hasMany(Product::class);
    }

    /**
     * ParentOrNotCategory
     *
     * Check is the category is child category of that category
     * @param int $parent_id
     * @param int $child_id
     */
    public static function ParentOrNotCategory($parent_id, $child_id): bool
    {
        $categories = Category::where('id', $child_id)->where('parent_id', $parent_id)->get();
        return !!count($categories);
    }
}
