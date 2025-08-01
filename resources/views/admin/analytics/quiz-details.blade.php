@extends('layouts.admin')

@section('title', 'Quiz Analytics - ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.analytics.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Analytics Dashboard
        </a>
        <h1 class="text-3xl font-bold mt-2">{{ $quiz->title }} - Analytics</h1>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Attempts</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $quiz->results->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Average Score</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($quiz->results->avg('score'), 1) }}%</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Pass Rate</h3>
            <p class="text-3xl font-bold text-gray-900">
                {{ number_format($quiz->results->where('score', '>=', $quiz->passing_score)->count() / $quiz->results->count() * 100, 1) }}%
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Average Time</h3>
            <p class="text-3xl font-bold text-gray-900">{{ gmdate("i:s", $quiz->results->avg('time_taken')) }}</p>
        </div>
    </div>

    <!-- Score and Time Distribution Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">Score Distribution</h2>
            </div>
            <div class="p-6">
                <canvas id="scoreDistributionChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">Time Distribution</h2>
            </div>
            <div class="p-6">
                <canvas id="timeDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Question Analysis -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Question Analysis</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Success Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correct/Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($questionAnalysis as $analysis)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $analysis['question'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <div class="w-48 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $analysis['success_rate'] }}%"></div>
                                </div>
                                <span class="ml-3">{{ number_format($analysis['success_rate'], 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $analysis['correct_count'] }}/{{ $analysis['total_attempts'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Attempts -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Recent Attempts</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($quiz->results->take(10) as $result)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $result->user->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Score: {{ $result->score }}% | Time: {{ gmdate("i:s", $result->time_taken) }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-500">
                        {{ $result->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    const scoreCtx = document.getElementById('scoreDistributionChart').getContext('2d');
    new Chart(scoreCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($scoreDistribution->toArray())) !!}.map(score => `${score}-${parseInt(score) + 10}`),
            datasets: [{
                label: 'Number of Students',
                data: {!! json_encode($scoreDistribution->values()) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Time Distribution Chart
    const timeCtx = document.getElementById('timeDistributionChart').getContext('2d');
    new Chart(timeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($timeDistribution->toArray())) !!}.map(time => `${time}-${parseInt(time) + 5} min`),
            datasets: [{
                label: 'Number of Attempts',
                data: {!! json_encode($timeDistribution->values()) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
