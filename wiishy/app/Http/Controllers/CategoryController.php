<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\gift;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

/**
 * This class is for category management
 */
class CategoryController extends Controller
{

    /**
     * all parent categories
     * 
     * @return Illuminate\Http\Respons
     */
    public function categories()
    {
        $categories = Category::where('parent_id', 0)->get();
        return response([
            'status' => 'success',
            'categories' => $categories
        ] , 200);
    }

    
    /**
     * all sub categories
     * 
     * @param int $id id of a paret_category
     * @return Illuminate\Http\Respons
     */
    public function subCategories($id)
    {
        $categories = Category::where('parent_id', $id)->get();
        return response([
            'status' => 'success',
            'categories' => $categories
        ] , 200);
    }

    /**
     * add a category to a gift
     * 
     * @param int $gift_id
     * @param int $category_id
     * @return Illuminate\Http\Respons
     */
    public function addGiftCategory($gift_id, $category_id)
    {
        try{
            $gift = gift::findOrFail($gift_id);
            $category = Category::findOrFail($category_id);
        }
        catch(\Exception $exception){
            return response([
                'status' => 'Error',
                'message '=> 'not found'
            ] , 400);
        }

        $gift->categories()->attach($category);

        return response([
            'status' => 'success',
            'message' => 'The desired category has been successfully added to the gift'
        ] , 200);
    }

    /**
     * add a category to a user
     * 
     * @param int $user_id
     * @param int $category_id
     * @return Illuminate\Http\Respons
     */
    public function addUserCategory($gift_id, $category_id)
    {
        try{
            $user = User::findOrFail($gift_id);
            $category = Category::findOrFail($category_id);
        }
        catch(\Exception $exception){
            return response([
                'status' => 'Error',
                'message' => 'not found'
            ] , 400);
        }

        $user->categories()->attach($category);

        return response([
            'status' => 'success',
            'message' => 'The desired category has been successfully added to your favorites'
        ], 200);
    }

    /**
     * موقت برای پر کردن دیتابیس
     */
    public function addParent(Request $req)
    {
        $req = collect($req)->toArray();
        // try {
            if(Category::where('name', $req['name'])->first()) {
                return response([
                    'status' => 'Error',
                    'message' => 'this category is already exist'
                ]);
            }
                
            $parentCategory = Category::create($req);
       /*  } catch (\Exception $exception) {
            return response([
                'status' => 'Error',
                'message' => 'faild to add',
            ], 400);
        } */
        return response([
            'status' => 'success',
            'parent_category' => $parentCategory
        ], 200);
    }

    /**
     * موقت
     */
    public function addSubCategoryt(Request $req)
    {
        $req->validate([
            'parent_id' => 'required',
            'name' => 'required',
        ]);
        $req = collect($req)->toArray();
        if(!Category::where('id', $req['parent_id'])->first()) {
            return response([
                'status' => 'Error',
                'message' => 'this parent category is not exist'
            ]);
        }
        // try {
            if(Category::where(['name' => $req['name'], 'parent_id' => $req['parent_id']])->first()) {
                return response([
                    'status' => 'Error',
                    'message' => 'this category is already exist'
                ]);
            }
                
            $parentCategory = Category::create($req);
       /*  } catch (\Exception $exception) {
            return response([
                'status' => 'Error',
                'message' => 'faild to add',
            ], 400);
        } */
        return response([
            'status' => 'success',
            'parent_category' => $parentCategory
        ], 200);
    }


}
