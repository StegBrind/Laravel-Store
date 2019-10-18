<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * @param $category_id
     * @param string $name_search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static public function findByCategoryID($category_id, $name_search = '')
    {
        return self::query()->where('category_id', $category_id)->
        where('name', 'like', '%' . $name_search . '%')->orderByDesc('created_at');
    }

    /**
     * Counting products in category
     */
    static public function boot()
    {
        parent::boot();

        self::creating(function($model)
        {
            Category::query()->find($model->category_id)->increment('count_products');
        });

        self::deleting(function($model)
        {
            Category::query()->find($model->category_id)->decrement('count_products');
        });
    }
}
