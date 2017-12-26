<?php

namespace App\Http\Controllers;

use Mail;
use App\Tag;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($task = null)
    {
        $tasks = auth()->user()->tasks()->oldest()->paginate(10);
        $edit = isset($task) ? auth()->user()->tasks()->find($task) : null;

        return view('task', [
            'tasks' => $tasks,
            'edit' => $edit,
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'finished_at' => 'required',
            'assign_to'   => 'required',
        ]);


        $task = auth()->user()->tasks()->create($data);

        $tags = collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        });

        $task->tags()->sync($tags);

        $send = [
            'assign_to' => $request->assign_to,
            'subject' => $request->subject,
            'bodyMessage' => $request->message,

        ];

        Mail::send('mail.mail', $send, function($message) use ($send) {
            $message->from('test66267@gmail.com', 'Test abc');
            $message->to($send['assign_to']);
            $message->subject($send['subject']);
        });

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
            'finished_at' => 'required',
            'assign_to' => 'required',
        ]);

        $task->update($data);

        $tags = collect($request->tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        });

        $task->tags()->sync($tags);

        $send = [
            'assign_to' => $request->assign_to,
            'subject' => $request->subject,
            'bodyMessage' => $request->message,

        ];

        Mail::send('mail.mail', $send, function($message) use ($send) {
            $message->from('test66267@gmail.com', 'Test abc');
            $message->to($send['assign_to']);
            $message->subject($send['subject']);
        });

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
}
