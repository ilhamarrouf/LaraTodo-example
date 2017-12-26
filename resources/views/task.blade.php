@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            @include('layouts.partials.alert')

            @if (isset($edit))
            <form action="{{ route('tasks.update', ['task' => $edit->id]) }}" method="POST">
            @else
            <form action="{{ route('tasks.store') }}" method="POST">
            @endif
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">Project Title:</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{ isset($edit) ? $edit->title : old('title') }}">
                    @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3">{{ isset($edit) ? $edit->description : old('description') }}</textarea>
                    @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="tags">Tags:</label>
                    <select class="form-control" name="tags[]" id="tags" multiple="multiple" style="width: 100%">
                        @foreach (\App\Tag::all() as $tag)
                        <option {{ (isset($edit) ? $edit->tags()->get()->contains('name', $tag->name) : null) ? 'selected' : '' }} value="{{ $tag->name }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-default">{{ isset($edit) ? 'Update' : 'Save' }}</button>
                </div>
                @if (isset($edit))
                <div class="form-group text-center">
                    <a href="{{ route('tasks') }}">cancel</a>
                </div>
                @endif

            </form>

            @if (count($tasksTrashed))
                <div class="pull-right" style="float: right; ">
                    <a href="{{ url('history') }}" class="btn btn-default">History</a>
                </div>
            @endif
            <br><br>


            @forelse ($tasks as $task)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{ $task->title }}</strong>
                    <div class="pull-right">
                        @foreach ($task->tags as $tag)
                        <span class="label label-warning">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="panel-body">
                    {{ $task->description }}
                </div>
                <div class="panel-footer">
                    <a href="{{ route('tasks', ['task' => $task->id]) }}" class="btn btn-success btn-xs">Edit</a>
                    <a href="{{ route('tasks.destroy', ['task' => $task->id]) }}" class="btn btn-danger btn-xs" onclick="return confirm('Yakin menghapus tugas?')">Delete</a>
                </div>
            </div>
            @empty
            <div class="alert alert-warning text-center">
                <strong>Yeaayy tidak ada tugas :D</strong>
            </div>
            @endforelse

            <div class="text-center">
                {{ $tasks->links() }}
            </div>


        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#tags').select2({
        tags: true
    });
});
</script>
@endsection