<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class MapApiController extends Controller
{
    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function placeApiAutocomplete(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $mapApiKey = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $mapApiKey);
        return $response->json();
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function distanceApi(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $mapApiKey = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $mapApiKey);
        return $response->json();
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function placeApiDetails(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $mapApiKey = Helpers::get_business_settings('map_api_server_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $mapApiKey);
        return $response->json();
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function geocodeApi(Request $request): mixed
    {
        $request->lat = 32.190284;
        $request->lng = 71.820646;

//        $validator = Validator::make($request->all(), [
//            'lat' => 'required',
//            'lng' => 'required',
//        ]);
//        if ($validator->errors()->count() > 0) {
//            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
//        }
        $mapApiKey = 'AIzaSyAg5px54zaZdj6aXgTs8VNkkkkWfGO_fck';
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . 32.190284 . ',' . 71.820646 . '&key=' . $mapApiKey);
        return $response->json();
    }
}
