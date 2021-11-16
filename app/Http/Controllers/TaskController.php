<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReorderRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskListRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(TaskListRequest $request)
    {

        $current_project = Arr::exists( $request->validated() , 'project_id') ?  Project::find((int) $request->validated()['project_id'] ) : null ;

        if ($current_project) {

            $tasks =$current_project->tasks;

        }else{

            $tasks = Task::with('project')->get()->sortBy('priority');
        }

        $projects = Project::all();

        $this->reorderAll($tasks);

        return view('Task.index',compact('tasks' , 'projects' , 'current_project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('Task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $priority = Task::count() + 1 ;

        Task::create([
                'title' => $validated['title'],
                'description'=> $validated['description'],
                'project_id'=> Arr::exists($validated, 'project_id')? $validated['project_id']:null ,
                'priority' => $priority,
            ]);

        return redirect()->route('task.index');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('Task.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('Task.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update([
            'title' => Arr::exists($validated, 'title')? $validated['title']:$task->title ,
            'description'=> Arr::exists($validated, 'description')? $validated['description']:$task->description,
            'finished_at'=>Arr::exists($validated, 'done')? Carbon::now() :null ,
        ]);

        return redirect()->route('task.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index');
    }

    /**
     * @param ReorderRequest $request
     */
    public function reorder(ReorderRequest $request)
    {
        $validated = $request->validated();

        $task = Task::where('priority',  $validated['oldIndex'] + 1)->first();

        if ($validated['newIndex'] > $validated['oldIndex'] ) {

            Task::whereBetween('priority', [ $validated['oldIndex'] + 1  , $validated['newIndex'] + 1 ])
                ->decrement('priority');

        }else{

            Task::whereBetween('priority', [ $validated['newIndex'] + 1 , $validated['oldIndex'] + 1])
                ->increment('priority');

        }

        $task->update(['priority'=>$validated['newIndex'] + 1]);

    }

    /**
     * @param $records
     */
    public function reorderAll($records)
    {

        $order = 1;

        foreach($records as $row) {
            $row->priority = $order;
            $row->update();
            $order++;
        }
    }


}
