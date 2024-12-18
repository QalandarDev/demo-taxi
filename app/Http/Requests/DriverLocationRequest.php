<?php

namespace App\Http\Requests;

class DriverLocationRequest extends BaseRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'driver_id' => 'required|numeric',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric'
        ];
    }
}
