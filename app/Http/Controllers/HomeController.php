<?php

namespace App\Http\Controllers;

use App\Models\Ball;
use App\Models\BallsType;
use App\Models\Bucket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {

        $numberOfBalls = Ball::select('name', 'volume', 'number_of_balls as numberOfBalls')->orderBy('id', 'desc')->get();
        if (!empty($numberOfBalls)) {
            $numberOfBalls = $numberOfBalls;
        }else{
            $numberOfBalls = [];
        }
        $ball_results = BallsType::orderBy('id', 'desc')->get();
        $bucket_results = Bucket::orderBy('id', 'desc')->get();
        return view('index', compact('ball_results', 'numberOfBalls', 'bucket_results'));
    }
    public function BucketForm(Request $request)
    {

        $request->validate([
            'bucket_name' => ['required'],
            'bucket_volume' => ['required', 'numeric'],
        ]);
        $obj = new Bucket();
        $obj->name = $request->bucket_name;
        $obj->volume = $request->bucket_volume;
        if ($obj->save()) {
            Session()->flash('success', "Bucket has been added successfully.");
            return Redirect()->route('home');
        } else {
            Session()->flash('error', "Something went wrong.");
            return Redirect()->route('home');
        }
    }
    public function BallTypeForm(Request $request)
    {
        $request->validate([
            'ball_name' => ['required', 'string'],
            'ball_volume' => ['required', 'numeric'],
        ]);
        $obj = new BallsType();
        $obj->name = $request->ball_name;
        $obj->volume = $request->ball_volume;
        if ($obj->save()) {
            Session()->flash('success', "Ball has been added successfully.");
            return Redirect()->route('home');
        } else {
            Session()->flash('error', "Something went wrong.");
            return Redirect()->route('home');
        }
    }
    public function BallForm(Request $request)
    {
        Ball::orderBy('id','desc')->delete();
        foreach ($request->balls as $key => $value) {
            $obj = new Ball();
            $obj->name = $value['name'];
            $obj->volume = $value['volume'];
            $obj->number_of_balls = $value['numberOfBalls'];
            $obj->save();
        }
        Session()->flash('success', "Ball has been added successfully.");
        return Redirect()->route('home');
    }
}
