<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category_name = Category::query()->findOrFail($id, ['name'])['name'];
        return view('category',
        [
            'category_id' => $id,
            'category_name' => $category_name,
        ]);
    }

    public function createNewCategory(Request $request)
    {
        $request->validate(
        [
            'name' => 'required|unique:category',
            'parent_id' => 'required'
        ]);
        Category::query()->create(
        [
            'name' => $request->name,
            'parent_id' => $request->parent_id == -1 ? null : $request->parent_id
        ]);
        return redirect('admin/categories');
    }


    /**
     * AJAX Live Searching
     *
     * @param Request $request
     */
    public function search(Request $request)
    {
        if ($request->ajax())
        {
            $query = $request->get('query');
            $category_id = $request->get('category_id');

            $products = Product::findByCategoryID($category_id, $query);

            if ($products->count() == 0)
            {
                echo json_encode
                (
                    [
                        'products' =>  __('general.bad_search'),
                        'pages_html' => ''
                    ]
                );
                return;
            }
            $products_paginator = $products->paginate(8);

            $pages = $this->getPages($products_paginator, $category_id, $query);

            $products_result = [];

            $conversation_url = url('conversation/talk') . '/';

            foreach ($products_paginator as $product)
            {
                $products_result[] =
                [
                    'name' => $product->name,
                    'img' => asset($product->image_paths),
                    'description' => $product->description,
                    'author' => $product->author,
                    'price' => $product->price,
                    'conversation' => $conversation_url . $product->author_id
                ];
            }
            echo json_encode
            (
                [
                    'products' => $products_result,
                    'pages' => $pages
                ]
            );
        }
    }

    private function getPages(LengthAwarePaginator $paginator, $category_id, $query)
    {
        $start_num = ($paginator->currentPage() < 4) ? 1 : $paginator->currentPage() - 2;
        $end_num = ($paginator->currentPage() + 3 > $paginator->lastPage()) ? $paginator->lastPage() : $paginator->currentPage() + 2;
        $pages_arr = [];

        if ($paginator->currentPage() != $paginator->lastPage())
            $pages_arr['&raquo;'] = "$category_id?page=" . ($paginator->currentPage() + 1) . "&search_query=$query";

        for ($page_number = $start_num; $page_number < $paginator->currentPage(); $page_number++)
            $pages_arr[$page_number] = "$category_id?page=$page_number&search_query=$query";

        if ($paginator->lastPage() != 1)
            $pages_arr[$paginator->currentPage()] = "$category_id?page=" . $paginator->currentPage() . "&search_query=$query";

        for ($page_number = $paginator->currentPage() + 1; $page_number <= $end_num; $page_number++)
            $pages_arr[$page_number] = "$category_id?page=$page_number&search_query=$query";

        if ($paginator->currentPage() != 1)
            $pages_arr['&laquo;'] = "$category_id?page=" . ($paginator->currentPage() - 1) . "&search_query=$query";

        return $pages_arr;
    }
}
