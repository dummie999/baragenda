<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
    public function index(Request $request)
    {   
        //TODO: zoek uit hoe je alle commissies opvraagt
        $skills = Skill::all();
        return view('skills.index', compact('skills'));
    }
}
