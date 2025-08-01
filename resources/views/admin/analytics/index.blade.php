@extends('layouts.admin')

@section('title', 'Quiz Analytics')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Quiz Analytics Dashboard</h1>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Quizzes</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalQuizzes }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Attempts</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalAttempts }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium">Average Score</h3>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($averageScore, 1) }}%</p>
        </div>
    </div>

    <!-- Quiz Performance Table -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Quiz Performance</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attempts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pass Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($quizPerformance as $quiz)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $quiz['title'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $quiz['attempts'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($quiz['average_score'], 1) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($quiz['pass_rate'], 1) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ gmdate("i:s", $quiz['average_time']) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Category Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">Category Performance</h2>
            </div>
            <div class="p-6">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">User Engagement</h2>
            </div>
            <div class="p-6">
                <canvas id="engagementChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Recent Activity</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($recentActivity as $result)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $result->user->name }} completed "{{ $result->quiz->title }}"
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
    // Category Performance Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoryPerformance->pluck('name')) !!},
            datasets: [{
                label: 'Average Score',
                data: {!! json_encode($categoryPerformance->pluck('average_score')) !!},
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
                    max: 100
                }
            }
        }
    });

    // User Engagement Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    new Chart(engagementCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($userEngagement->pluck('date')) !!},
            datasets: [{
                label: 'Quiz Attempts',
                data: {!! json_encode($userEngagement->pluck('attempts')) !!},
                borderColor: 'rgb(16, 185, 129)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
