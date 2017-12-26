@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="pull-right">
                <a href="{{ route('history.restore') }}" class="btn btn-default" style="float: right; margin-right: 180px; margin-bottom: 10px;">Restore All</a>
            </div>
            <div class="col-md-8 col-md-offset-2">
                @include('layouts.partials.alert')

                @forelse($tasksTrashed as $task)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>{{ $task->title }}</strong>
                            <div class="pul-right">
                                @foreach ($task->tags as $task)
                                    <span class="label label-warning">{{ $task->tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel-body">
                            {{ $task->description }}
                        </div>
                        <div class="panel-footer">
                            <p>

                                Created At : {{ date('d-m-Y'), strtotime($task->created_at) }} <br>
                                Deleted At : {{ date('d-m-Y'), strtotime($task->deleted_at) }}
                            </p>
                        </div>
                    </div>

                    @empty
                    <div class="alert alert-warning text-center">
                        <strong>Tidak ada History</strong>
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