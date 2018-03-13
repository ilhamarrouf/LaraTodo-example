<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Tag;
use App\Task;

class TaskController extends Controller
{
    public function index(int $task = null)
    {
        $tasks = auth()->user()->tasks()->oldest()->paginate();
        $edit = isset($task) ? auth()->user()->tasks()->find($task) : null;

        return view('task', compact('tasks', 'edit'));
    }

    public function store(StoreTaskRequest $request)
    {
        $task = auth()->user()->tasks()->create(
            $request->only('title', 'description')
        );

        $task->tags()->sync(collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        }));

        return redirect()->route('tasks')->withSuccess(trans('task.created'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (!auth()->user()->can('update', $task)) {
            return back()->withDanger(trans('auth.unauthorized'));
        }

        $task->update($request->only('title', 'description'));

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
