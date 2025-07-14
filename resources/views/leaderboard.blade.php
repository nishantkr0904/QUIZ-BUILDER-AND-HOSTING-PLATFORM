@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Leaderboard</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Alice</td>
                <td>95</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Bob</td>
                <td>90</td>
            </tr>
            <!-- More rows -->
        </tbody>
    </table>
</div>
@endsection
