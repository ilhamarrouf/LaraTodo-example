<?php

namespace App\Http\Controllers;

use Auth;
use App\Tag;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($task = null)
    {
        $tasks = auth()->user()->tasks()->oldest()->paginate(10);
        $edit = isset($task) ? auth()->user()->tasks()->find($task) : null;

        $tasksTrashed = Auth::user()->tasks()->onlyTrashed()->get();
        return view('task', [
            'tasks' => $tasks,
            'edit' => $edit,
        ], compact('tasksTrashed'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $task = auth()->user()->tasks()->create($data);

        $tags = collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        });

        $task->tags()->sync($tags);

        return back()->withSuccess(
            'Berhasil menambahkan tugas'
        );
    }

    public function update(Request $request, Task $task)
    {
        if (!auth()->user()->can('update', $task)) {
            return back()->withDanger(
                'Terjadi kesalahan!'
            );
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $task->update($data);

        $tags = collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        });

        $task->tags()->sync($tags);

        return redirect()->route('tasks')->withSuccess(
            'Berhasil memperbarui tugas'
        );
    }

    public function destroy(Task $task)
    {
        if (!auth()->user()->can('delete', $task)) {
            return back()->withDanger(
                'Terjadi kesalahan!'
            );
        }

        $task->tags()->detach();
        $task->delete();

        return back()->withSuccess(
            'Berhasil menghapus tugas'
        );

    }

    public function history(Task $task = null)
    {
        $tasks = auth()->user()->tasks()->oldest()->paginate(10);

        $tasksTrashed = Auth::user()->tasks()->onlyTrashed()->get();
        return view('history', compact('tasksTrashed', 'tasks'));
    }

    public function restoreAll()
    {
        Auth::user()->tasks()->onlyTrashed()->restore();

        return redirect('tasks')->withSuccess('Semua history berhasil dikembalikan');
    }

}
