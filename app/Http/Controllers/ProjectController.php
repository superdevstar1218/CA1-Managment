<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Project;

class ProjectController extends Controller
{
    //
    public function index()
    {
        $projects = Project::all();
        return view('pages.projects', compact('projects'));
    }

    public function store(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json(array('result' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }

        Project::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        return response()->json(array('result' => true, 'success' => 'Project created'));
    }

    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
        $project = Project::find($id);
        $project->name = $data['name'];
        $project->description = $data['description'];
        $project->status = $data['status'];
        $project->save();

        return response()->json(array('result' => true, 'success' => 'Project updated'));
    }
}
