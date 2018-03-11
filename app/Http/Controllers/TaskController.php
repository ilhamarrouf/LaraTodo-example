<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($task = null)
    {
        $tasks = auth()->user()->tasks()->oldest()->paginate(10);
        $edit = isset($task) ? auth()->user()->tasks()->find($task) : null;

        return view('task', compact('tasks', 'edit'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $task = auth()->user()->tasks()->create($data);

        $task->tags()->sync(collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        }));

        return redirect()->route('tasks')->withSuccess(trans('task.created'));
    }

    public function update(Request $request, Task $task)
    {
        if (!auth()->user()->can('update', $task)) {
            return back()->withDanger(trans('auth.unauthorized'));
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $task->update($data);

        $task->tags()->sync(collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        }));

        return redirect()->route('tasks')->withSuccess(trans('task.updated'));
    }

    public function destroy(Task $task)
    {   
        if (!auth()->user()->can('delete', $task)) {
            return back()->withDanger(trans('auth.unauthorized'));
        }

        $task->tags()->detach();
        $task->delete();

        return redirect()->route('tasks')->withSuccess(trans('task.deleted'));
    }
}
