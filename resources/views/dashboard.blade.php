@extends('layouts.custom-adminlte')

@section('title', 'Dashboard')

@section('content_header')
<h1>ダッシュボード</h1>
@stop
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            {{ __("You're logged in!") }}
        </div>
    </div>
</div>
@stop

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
<!-- <script>
    console.log('Hi!');
</script> -->
@stop
