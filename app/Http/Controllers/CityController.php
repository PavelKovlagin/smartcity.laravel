<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use DB;
use App;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCities()
    {
        $cities = App\City::selectCities()->paginate(10);
        return view('cities.cities', [
            'title' => 'Города',
            'cities' => $cities,
        ]);
    }

    public function addCity(Request $request)
    {
        $authUser = App\User::selectAuthUser();
        if ($authUser->levelRights >= 3) {
            App\City::insertCity($request);
            return redirect('cities');
        } else {
            return "lol net";
        }
    }

    public function showCity($city_id) {
        $city = App\City::selectCityId($city_id)->first();
        $authUser = App\User::selectAuthUser();
        return view("cities.city", [
            'authUser' => $authUser,
            'city' => $city
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }
}
