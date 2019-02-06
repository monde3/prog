@if(session('message'))
<div class="row">
    <div class="col-sm-4">
        <div class="alert alert-success fade in out">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session('message') }}
        </div>
    </div>
</div>
@endif
@if(session('error'))
<div class="row">
    <div class="col-sm-4">
        <div class="alert alert-danger fade in out">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session('error') }}
        </div>
    </div>
</div>
@endif