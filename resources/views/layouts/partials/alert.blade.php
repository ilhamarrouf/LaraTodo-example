@if (session('success'))
<div id="alert" class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ session()->get('success') }}</strong>
</div>
@endif

@if (session('danger'))
<div id="alert" class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ session()->get('danger') }}</strong>
</div>
@endif
