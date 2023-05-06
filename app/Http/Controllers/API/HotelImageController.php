<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ImageStoreRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\HotelImageResource;
use App\Models\Hotel;
use Illuminate\Support\Facades\Validator;

class HotelImageController extends BaseController
{
    //
    public function index()
    {
        $hotelImage = HotelImage::get();
        return $this->sendResponse(HotelImageResource::collection($hotelImage), 'Hotels-image retrieved successfully.');
    }

    public function show($id)
    {
        $hotelImage = HotelImage::find($id);

        if (is_null($hotelImage)) {
            return $this->sendError('Hotel image not found.');
        }

        return $this->sendResponse(new HotelImageResource($hotelImage), 'Hotel image retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            // if hotel is soft deleted, then send error response
            'hotel_id' => 'required|exists:hotel,id,deleted_at,NULL',
            'image_url' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $hotelImage = HotelImage::create($input);

        return $this->sendResponse(new HotelImageResource($hotelImage), 'Hotel image created successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'hotel_id' => 'required',
            'image_url' => 'required',
            'image_description'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $hotelImage = HotelImage::find($id);
        // $hotelImage = HotelImage::find($id);
        if (is_null($hotelImage)) {
            return $this->sendError('Hotel image not found.');
        }

        // if hotel is soft deleted, then send error response
        $hotel = Hotel::find($input['hotel_id']);
        if (is_null($hotel)) {
            return $this->sendError('Hotel ID not found.');
        }

        $hotelImage->hotel_id = $input['hotel_id'];
        $hotelImage->image_url = $input['image_url'];
        $hotelImage->image_description = $input['image_description'];

        $hotelImage->save();
        if (($hotelImage)->save()) {
            return $this->sendResponse(new HotelImageResource($hotelImage), 'Hotel image updated successfully.');
        } else {
            return $this->sendError('Hotel image not updated.');
        }
    }

    public function destroy($id)
    {
        $hotelImage = HotelImage::find($id);

        if (is_null($hotelImage)) {
            return $this->sendError('Hotel image not found.');
        }
        if ($hotelImage->delete()) {
            return $this->sendResponse([], 'Hotel image deleted successfully.');
        } else {
            return $this->sendError('Hotel image not deleted.');
        }
    }

    public function restoreByHotelId($id){
        $hotelImage = HotelImage::onlyTrashed()->where('hotel_id', $id)->restore();
        if ($hotelImage) {
            return $this->sendResponse([], 'Hotel image restored successfully.');
        } else {
            return $this->sendError('Hotel image not restored.');
        }
    }

    public function deleteImageByHotelId($id)
    {
        $hotelImage = HotelImage::where('hotel_id', $id)->get();

        if (is_null($hotelImage)) {
            return $this->sendError('Hotel id not found.');
        }
        if (count($hotelImage) > 0) {
            foreach ($hotelImage as $key => $value) {
                $value->delete();
            }
            return $this->sendResponse([], 'Hotel image deleted successfully.');
        } else {
            return $this->sendError('Hotel image not found.');
        }
    }


    public function getImageByHotelId($id)
    {
        $hotelImage = HotelImage::where('hotel_id', $id)->get();

        if (is_null($hotelImage)) {
            return $this->sendError('Hotel image not found.');
        }
        if (count($hotelImage) > 0) {
            return $this->sendResponse(HotelImageResource::collection($hotelImage), 'Hotel image retrieved successfully.');
        } else {
            return $this->sendError('Hotel image not found.');
        }
    }
}
