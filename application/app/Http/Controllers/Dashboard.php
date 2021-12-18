<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskKind;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Project $project)
    {
        $request->validate([
            'keyword' => 'max:255',
        ]);

        $assigners = User::all();

        $assigner_id = $request->input('assigner_id');
        $keyword = $request->input('keyword');
        $tasks = Task::select(
            'tasks.*',
            'tasks.id as key,'
        )
            ->with('task_kind')
            ->with('task_status')
            ->with('assigner')
            ->with('user')
            ->with('project')
            //->with('優先度')
            ->join('projects', 'tasks.project_id', 'projects.id') 
            ->where('assigner_id', '=', $request->user()->id);
        if ($request->has('keyword') && $keyword != '') {
            $tasks
                ->join('users as search_users', 'tasks.created_user_id', 'search_users.id')
                ->join('task_kinds as search_task_kinds', 'tasks.task_kind_id', 'search_task_kinds.id')
                ->leftJoin('users as search_assigner', 'tasks.assigner_id', 'search_assigner.id');
            $tasks
                ->where(function ($tasks) use ($keyword) {
                    $tasks
                        ->where('search_task_kinds.name', 'like', '%'.$keyword.'%')
                        ->orWhere('projects.key', 'like', '%'.$keyword.'%')
                        ->orWhere('tasks.name', 'like', '%'.$keyword.'%')
                        ->orWhere('search_assigner.name', 'like', '%'.$keyword.'%')
                        ->orWhere('search_users.name', 'like', '%'.$keyword.'%');
                });
        }
        if ($request->has('assigner_id') && isset($assigner_id)) {
            $tasks->where('tasks.assigner_id', '=', $assigner_id);
        }
        $tasks = $tasks
            ->sortable('name')
            ->paginate(20)
            ->appends(['keyword' => $keyword]);

            return view('dashboard', compact('tasks'), [
            'project' => $project,
            'assigners' => $assigners,
            'assigner_id' => $assigner_id,
            'keyword' => $keyword,
        ]);
    }
}
