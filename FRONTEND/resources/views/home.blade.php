<?php /* home.blade.php */ ?>

@extends('layouts.app')

@section('title', 'Quiz Platform - Browse Available Quizzes')

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .quiz-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .quiz-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .difficulty-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
    }
    .difficulty-easy {
        background-color: #DEF7EC;
        color: #03543F;
    }
    .difficulty-medium {
        background-color: #FEF3C7;
        color: #92400E;
    }
    .difficulty-hard {
        background-color: #FEE2E2;
        color: #991B1B;
    }
    .category-header {
        border-bottom: 2px solid #E5E7EB;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
    }
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }
    .difficulty-easy { background-color: #DEF7EC; color: #03543F; }
    .difficulty-medium { background-color: #FEF3C7; color: #92400E; }
    .difficulty-hard { background-color: #FEE2E2; color: #991B1B; }
    .search-bar {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.1);
    }
    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-gradient min-h-[50vh] relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 opacity-90"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <div class="container mx-auto px-4 py-16 relative">
        <div class="text-center text-white mb-12">
            <h1 class="text-5xl font-bold mb-6 leading-tight">Challenge Your Knowledge</h1>
            <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">Join thousands of learners testing their expertise across various topics.</p>
            
            @auth
                @if($featuredQuizzes->isNotEmpty())
                    <a href="{{ route('quiz.take', ['id' => $featuredQuizzes->first()->id]) }}" 
                       class="inline-flex items-center px-8 py-4 text-lg font-semibold text-purple-700 bg-white rounded-full hover:bg-purple-50 transform transition-all duration-300 hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Start a Quiz
                    </a>
                @else
                    <div class="inline-block bg-white bg-opacity-20 backdrop-blur-lg rounded-lg px-6 py-4 text-white">
                        No quizzes available at the moment. Check back soon!
                    </div>
                @endif
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white border-2 border-white rounded-full hover:bg-white hover:text-purple-700 transform transition-all duration-300">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-8 py-4 text-lg font-semibold text-purple-700 bg-white rounded-full hover:bg-purple-50 transform transition-all duration-300 hover:scale-105 shadow-lg">
                        Get Started
                    </a>
                </div>
            @endauth
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12">
            <div class="stat-card rounded-xl p-6 text-white text-center transform transition-all duration-300 hover:scale-105">
                <div class="text-3xl font-bold mb-2">{{ number_format($quizStats['total'] ?? 0) }}</div>
                <div class="text-sm uppercase tracking-wide opacity-90">Total Quizzes</div>
            </div>
            <div class="stat-card rounded-xl p-6 text-white text-center transform transition-all duration-300 hover:scale-105">
                <div class="text-3xl font-bold mb-2">{{ number_format($quizStats['categories'] ?? 0) }}</div>
                <div class="text-sm uppercase tracking-wide opacity-90">Categories</div>
            </div>
            <div class="stat-card rounded-xl p-6 text-white text-center transform transition-all duration-300 hover:scale-105">
                <div class="text-3xl font-bold mb-2">{{ number_format($quizStats['participants'] ?? 0) }}</div>
                <div class="text-sm uppercase tracking-wide opacity-90">Active Learners</div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Quizzes Section -->
@if($featuredQuizzes->count() > 0)
<section class="py-16 bg-gray-50 relative overflow-hidden">
    <div class="absolute inset-0 opacity-50">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
    </div>
    <div class="container mx-auto px-4 relative">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-4xl font-bold text-gray-800">Featured <span class="text-purple-600">Quizzes</span></h2>
            @if($featuredQuizzes->count() > 3)
                <a href="{{ route('quizzes.featured') }}" class="text-purple-600 hover:text-purple-700 font-semibold flex items-center">
                    View All
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredQuizzes as $quiz)
            <div class="quiz-card bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="relative">
                    <div class="h-3 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="difficulty-badge difficulty-{{ $quiz->difficulty }}">
                                {{ ucfirst($quiz->difficulty) }}
                            </span>
                            <div class="flex items-center space-x-2 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm">{{ $quiz->questions_count ?? $quiz->questions->count() }} Questions</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $quiz->title }}</h3>
                        <p class="text-gray-600 mb-6 line-clamp-2">{{ Str::limit($quiz->description, 100) }}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">{{ $quiz->category->name }}</span>
                            </div>
                            <a href="{{ route('quiz.take', $quiz->id) }}" 
                               class="inline-flex items-center px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-all duration-300">
                                Take Quiz
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Content Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-row space-x-8">
            <!-- Categories Sidebar -->
            <div class="w-1/4">
                <div class="sticky top-6 hidden lg:block">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Categories
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('home') }}" 
                                   class="block px-4 py-3 rounded-xl transition-all duration-200 {{ !isset($selectedCategory) || $selectedCategory === null ? 'bg-purple-100 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span>All Categories</span>
                                        <span class="text-sm {{ !isset($selectedCategory) || $selectedCategory === null ? 'text-purple-600' : 'text-gray-500' }}">
                                            {{ $quizzes->total() ?? 0 }}
                                        </span>
                                    </div>
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('home', ['category' => $category->id, 'difficulty' => $selectedDifficulty]) }}" 
                                   class="block px-4 py-3 rounded-xl transition-all duration-200 {{ isset($selectedCategory) && $selectedCategory == $category->id ? 'bg-purple-100 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-sm {{ isset($selectedCategory) && $selectedCategory == $category->id ? 'text-purple-600' : 'text-gray-500' }}">
                                            {{ $category->quizzes_count ?? $category->quizzes->count() }}
                                        </span>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-6">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                </svg>
                                Difficulty
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('home', ['category' => $selectedCategory]) }}" 
                                   class="block px-4 py-3 rounded-xl transition-all duration-200 {{ !isset($selectedDifficulty) || $selectedDifficulty === null ? 'bg-purple-100 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full mr-3 bg-gray-300"></span>
                                        All Difficulties
                                    </div>
                                </a>
                                @foreach(['easy', 'medium', 'hard'] as $difficulty)
                                <a href="{{ route('home', ['difficulty' => $difficulty, 'category' => $selectedCategory]) }}" 
                                   class="block px-4 py-3 rounded-xl transition-all duration-200 {{ isset($selectedDifficulty) && $selectedDifficulty === $difficulty ? 'bg-purple-100 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 rounded-full mr-3 {{ $difficulty === 'easy' ? 'bg-green-500' : ($difficulty === 'medium' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                        {{ ucfirst($difficulty) }}
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="w-3/4">
                <!-- Search and Sort Bar -->
                <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
                    <div class="p-4">
                        <form action="{{ route('home') }}" method="GET" class="flex flex-row items-center gap-4">
                            @foreach(request()->except(['search', 'page', 'sort']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            
                            <div class="relative flex-grow">
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search }}" 
                                       class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200"
                                       placeholder="Search quizzes...">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="w-full sm:w-auto">
                                <select name="sort" 
                                        onchange="this.form.submit()" 
                                        class="w-full sm:w-48 pl-4 pr-10 py-3 rounded-xl border-gray-200 focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all duration-200">
                                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="most_attempted" {{ $sort === 'most_attempted' ? 'selected' : '' }}>Most Popular</option>
                                    <option value="highest_rated" {{ $sort === 'highest_rated' ? 'selected' : '' }}>Highest Rated</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quiz Grid -->
                @if($quizzes->isNotEmpty())
                <div class="grid grid-cols-2 gap-6">
                    @foreach($quizzes as $quiz)
                    <div class="quiz-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="difficulty-badge difficulty-{{ $quiz->difficulty }}">
                                        {{ ucfirst($quiz->difficulty) }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $quiz->questions_count ?? $quiz->questions->count() }} Questions
                                    </span>
                                </div>
                                @if($quiz->results_count > 0)
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-sm">{{ $quiz->results_count }} Attempts</span>
                                </div>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold mb-2 text-gray-800">{{ $quiz->title }}</h3>
                            <p class="text-gray-600 mb-6 line-clamp-2">{{ Str::limit($quiz->description, 120) }}</p>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-500">Category:</span>
                                        <span class="text-gray-700 font-medium">{{ $quiz->category->name }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('quiz.take', $quiz->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300">
                                    Take Quiz
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($quizzes->hasPages())
                <div class="mt-8">
                    <div class="bg-white rounded-2xl shadow-lg p-4">
                        {{ $quizzes->onEachSide(1)->links() }}
                    </div>
                </div>
                @endif

                @else
                <div class="text-center py-12">
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Quizzes Found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search filters or check back later</p>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Reset Filters
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
