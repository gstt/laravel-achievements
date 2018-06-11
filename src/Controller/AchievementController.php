<?php

use App\Achievements\Model\AchievementDetails;
use Illuminate\Support\Facades\Input;

class AchievementController extends Controller
{
    public function index()
    {
        $name        = Input::get('name');
        $description = Input::get('description');
        $points      = Input::get('point');
        $type        = Input::get('type');
        $secret      = Input::get('secret');
        if($secret == 'on'){
            $secret = 1;
        }else{
            $secret = 0;
        }
        $achievement = new AchievementDetails();

        $achievement->name = $name;
        $achievement->description = $description;
        $achievement->points = $points;
        $achievement->type = $type;
        $achievement->secret = $secret;

        $achievement->save();
    }
}