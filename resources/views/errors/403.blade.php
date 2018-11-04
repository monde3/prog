@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.accesodenegado') }}
@endsection

@section('contentheader_title')
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')

<div class="error-page">
    <h2 class="headline text-red"> 403</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-red"></i> Oops! {{ trans('adminlte_lang::message.accesodenegado') }}.</h3>
        <p>
            {{ trans('adminlte_lang::message.nopermisos') }}
        </p>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection