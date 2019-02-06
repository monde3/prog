@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.shop') }}
@endsection

@section('contentheader_title')
	{{ trans('adminlte_lang::message.shop') }}
@endsection

@section('main-content')
	@if (Auth::user()->rol=='alumno')
		@include('layouts.alumno.tienda')
	@elseif (Auth::user()->rol=='administrador')
		@include('layouts.administrador.tienda')
	@endif
@endsection