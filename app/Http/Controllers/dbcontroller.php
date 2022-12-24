<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\temperature_data; 
use App\Models\humidity_data;
use App\Models\water_level;
use App\Models\alert_data;

class dbcontroller extends Controller
{
    public function __construct()
    {
        $this->temperature_data = new temperature_data();
        $this->humidity_data = new humidity_data();
        $this->water_level = new water_level();
        $this-> alert_data= new alert_data();
    }

    public function getTemperature()
    {
        $blocks = DB::table('temperature_data')
        ->select('value')
        ->latest('date_time')
        ->limit(10)
        ->pluck('value');

        $blocks2 = DB::table('temperature_data')
        ->select('value','date_time')
        ->latest('date_time')
        ->limit(10)
        ->pluck('date_time');

        return (compact('blocks','blocks2'));
    }

    public function getHumidity()
    {
        $blocks = DB::table('humidity_data')
        ->select('value')
        ->latest('date_time')
        ->limit(10)
        ->pluck('value');

        $blocks2 = DB::table('humidity_data')
        ->select('value','date_time')
        ->latest('date_time')
        ->limit(10)
        ->pluck('date_time');

        return (compact('blocks','blocks2'));
    }

    public function getWaterLevel()
    {
        $blocks = DB::table('water_level')
        ->select('value')
        ->latest('date_time')
        ->limit(10)
        ->pluck('value');

        $blocks2 = DB::table('water_level')
        ->select('value','date_time')
        ->latest('date_time')
        ->limit(10)
        ->pluck('date_time');

        return (compact('blocks','blocks2'));
    }

    public function getAlert()
    {
        $blocks = DB::table('alert')
        ->select('deviceID')
        ->latest('date')
        ->pluck('deviceID');

        $blocks2 = DB::table('alert')
        ->select('deviceID','date')
        ->latest('date')
        ->pluck('date');

        return (compact('blocks','blocks2'));
    }
}
