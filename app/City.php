<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class City extends Model
{
    protected static function selectCities() {
        $cities = DB::table('cities')
        ->select(
            'id as city_id',
            'name as city_name',
            'longitude as city_longitude',
            'latitude as city_latitude'
        );
        return $cities;
    }

    protected static function selectCityId($city_id) {
        $city = City::selectCities()
        ->where('id', '=', $city_id);
        return $city;
    } 

    protected static function selectCityName($city_name) {
        $city = City::selectCities()
        ->where('name', '=', $city_name);
        return $city;
    }

    protected static function updateCity($request) {
        DB::table('cities')
        ->where('id', '=', $request->city_id)
        ->update(array('longitude' => $request->longitude,
                        'latitude' => $request->latitude));
    }

    protected static function insertCity($request) {
        if (City::selectCityName($request->name)->get() <> null) {
            $city = new City;
            $city->name = $request->city_name;
            $city->longitude = $request->city_longitude;
            $city->latitude = $request->city_latitude;
            $city->save();
            return $city->id;
        } else {
            return false;
        }
    }
}
