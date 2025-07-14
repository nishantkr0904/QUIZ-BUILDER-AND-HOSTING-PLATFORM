@extends('layouts.app')

@section('title', 'Analytics & Statistics')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Analytics & Statistics</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Quizzes</h5>
                    <p class="display-6">12</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="display-6">150</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Attempts</h5>
                    <p class="display-6">320</p>
                </div>
            </div>
        </div>
    </div>
    <!-- More analytics as needed -->
</div>
@endsection
