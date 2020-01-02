<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'name', 'count_products', 'parent_id'
    ];

    /**
     * Recalculating products before deleting category
     */
    static public function boot()
    {
        parent::boot();
        self::deleting(function ($model)
        {
            Product::query()->where('category_id', '=', $model->id)->delete();
        });
    }
}
