@extends('layouts.admin-app')

@section('content')
<div class="p-10 text-white">
    <h1 class="text-2xl font-bold">Dashboard {{ ucfirst($role ?? 'Role') }}</h1>
</div>
@endsection
