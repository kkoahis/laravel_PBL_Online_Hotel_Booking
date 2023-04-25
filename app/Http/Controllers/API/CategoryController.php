<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;

class CategoryController extends BaseController
{
    //
    public function index()
    {
        $categories = Category::get();
        return $this->sendResponse(CategoryResource::collection($categories), 'Categories retrieved successfully.');
    }

    public function show($id)
    {
        $category = Category::with('hotel')->whereHas('hotel', function ($query) {
            $query->whereNull('deleted_at');
        })->find($id);

        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }

        return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description',
            // hotel delete_at must null
            'hotel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category = Category::create($input);

        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description',
            // 'hotel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category = Category::find($id);
        // if hotel has field deleted_at is null
        if ($category->hotel->deleted_at != null) {
            return $this->sendError('Validation Error.', 'Hotel id is not match');
        }
        $category->name = $input['name'];
        $category->description = $input['description'];
        // $category->hotel_id = $input['hotel_id'];
        $category->save();

        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
        if ($category->delete()) {
            return $this->sendResponse([], 'Category deleted successfully.');
        }
    }

    public function deleteCategoryByHotelId($id)
    {
        $category = Category::where('hotel_id', $id)->get();

        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
        if($category->count() > 0){
            foreach ($category as $item) {
                $item->delete();
            }
            return $this->sendResponse([], 'Category deleted successfully.');
        }
        else{
            return $this->sendError('Category not found.');
        }
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
        if ($category->restore()) {
            return $this->sendResponse(new CategoryResource($category), 'Category restored successfully.');
        }
    }

    public function restoreByHotelId($id)
    {
        $category = Category::onlyTrashed()->where('hotel_id', $id)->get();
        if (is_null($category)) {
            return $this->sendError('Hotel ID not found.');
        }
        if ($category->count() > 0) {
            foreach ($category as $item) {
                $item->restore();
            }
            return $this->sendResponse([], 'Category restored successfully.');
        } else {
            return $this->sendError('Hotel ID not found.');
        }
    }
}
