<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderByDesc('id')->get();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        // Visualizziamo la presenza dei dati
        // dd($request->all());

        // validiamo i dati
        $val_data = $request->validated();


        // check if
        if ($request->hasFile('cover_image')) {
            // Import img in storage/app/public
            $cover_image = Storage::put('uploads', $val_data['cover_image']);

            // check img
            // dd($cover_image);

            // Img Validate
            $val_data['cover_image'] = $cover_image;
        }


        // Creazione slug
        $project_slug = Str::slug($val_data['title']);
        // dd($project_slug);
        $val_data['slug'] = $project_slug;
        // dd($val_data);


        // // Creazione dei nuovi dati validati
        $project = Project::create($val_data);

        // Ritorniamo allo rotta index
        return to_route('admin.projects.index')->with('message', "Project id: $project->id added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        // Visualizziamo al presenza dei dati
        // dd($request->all());

        // validiamo i dati
        $val_data = $request->validated();
        // dd($val_data);

        // check if
        if ($request->hasFile('cover_image')) {
            if ($project->cover_image) {
                Storage::delete($project->cover_image);
            }
            // Import img in storage/app/public
            $cover_image = Storage::put('uploads', $val_data['cover_image']);

            // check img
            // dd($cover_image);

            // Img Validate
            $val_data['cover_image'] = $cover_image;
        }

        // Creazione slug
        $project_slug = Str::slug($val_data['title']);
        // dd($project_slug);

        $val_data['slug'] = $project_slug;
        // dd($val_data);

        // Creazione  Project
        $project->update($val_data);

        // Ritorniamo allo rotta index
        return to_route('admin.projects.index')->with('message', "Project id: $project->id update successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return to_route('admin.projects.index')->with('message', "Project id: $project->id Deleted successfully");
    }
}
