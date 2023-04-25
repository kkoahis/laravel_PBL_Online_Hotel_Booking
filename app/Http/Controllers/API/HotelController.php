<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class HotelController extends BaseController
{
    //
    public function index()
    {
        $hotels = Hotel::all();

        return $this->sendResponse(HotelResource::collection($hotels), 'Hotels retrieved successfully.');
    }

    public function show($id)
    {
        $hotel = Hotel::find($id);

        if (is_null($hotel)) {
            return $this->sendError('Hotel not found.');
        }

        return $this->sendResponse(new HotelResource($hotel), 'Hotel retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'hotline' => 'required',
            'email' => 'required',
            'description',
            'room_total' => 'required',
            'parking_slot' => 'required',
            'bathrooms' => 'required',
            // create_by user admin
            'created_by' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $hotel = Hotel::create($input);

        return $this->sendResponse(new HotelResource($hotel), 'Hotel created successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'hotline' => 'required',
            'email' => 'required',
            'description',
            'room_total' => 'required',
            'parking_slot' => 'required',
            'bathrooms' => 'required',
            // create_by user admin
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }   

        $hotel = Hotel::findOrFail($id);
        if (is_null($hotel)) {
            return $this->sendError('Hotel not found.');
        }
        $hotel->name = $input['name'];
        $hotel->address = $input['address'];
        $hotel->hotline = $input['hotline'];
        $hotel->email = $input['email'];
        $hotel->description = $input['description'];
        $hotel->room_total = $input['room_total'];
        $hotel->parking_slot = $input['parking_slot'];
        $hotel->bathrooms = $input['bathrooms'];
        if ($hotel->save()) {
            return $this->sendResponse(new HotelResource($hotel), 'Hotel updated successfully.');
        }
        else {
            return $this->sendError('Hotel not updated.');
        }
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        if (is_null($hotel)) {
            return $this->sendError('Hotel not found.');
        }
        if ($hotel->delete()) {
            return $this->sendResponse([], 'Hotel deleted successfully.');
        }
        else {
            return $this->sendError('Hotel not deleted.');
        }
    }
}
