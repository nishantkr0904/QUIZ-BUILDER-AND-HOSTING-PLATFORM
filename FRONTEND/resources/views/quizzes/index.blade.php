@extends('layouts.app')

@section('content')
<div class="quiz-listing" id="quizListing">
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-3">Explore Quizzes</h1>
                    <p class="lead mb-4">Discover and attempt quizzes across various categories and difficulty levels</p>
                    
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Search quizzes..."
                                   v-model="searchQuery"
                                   @input="filterQuizzes">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="/assets/images/quiz-hero.svg" alt="Quiz illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section py-4 bg-light border-bottom">
        <div class="container">
            <div class="row g-3 align-items-center">
                <!-- Category Filter -->
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="form-label mb-2">Category</label>
                        <select class="form-select" v-model="selectedCategory" @change="filterQuizzes">
                            <option value="">All Categories</option>
                            <option v-for="category in categories" :value="category.id">
                                @{{ category.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Difficulty Filter -->
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="form-label mb-2">Difficulty</label>
                        <div class="btn-group w-100">
                            <button v-for="level in difficulties"
                                    :key="level"
                                    class="btn"
                                    :class="[
                                        'btn-outline-primary',
                                        {'active': selectedDifficulty === level}
                                    ]"
                                    @click="selectDifficulty(level)">
                                @{{ level }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="form-label mb-2">Sort By</label>
                        <select class="form-select" v-model="sortBy" @change="filterQuizzes">
                            <option value="newest">Newest First</option>
                            <option value="popular">Most Popular</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quiz Grid -->
    <section class="quiz-grid py-5">
        <div class="container">
            <!-- Active Filters -->
            <div class="active-filters mb-4" v-if="hasActiveFilters">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="text-muted me-2">Active Filters:</span>
                    <div class="filter-tag" v-if="selectedCategory">
                        Category: @{{ getCategoryName(selectedCategory) }}
                        <button class="btn-close ms-2" @click="clearCategory"></button>
                    </div>
                    <div class="filter-tag" v-if="selectedDifficulty">
                        Difficulty: @{{ selectedDifficulty }}
                        <button class="btn-close ms-2" @click="clearDifficulty"></button>
                    </div>
                    <button class="btn btn-link text-decoration-none" @click="clearAllFilters" v-if="hasActiveFilters">
                        Clear All Filters
                    </button>
                </div>
            </div>

            <!-- Quiz Cards Grid -->
            <transition-group name="quiz-grid" tag="div" class="row g-4">
                <div class="col-md-6 col-lg-4" v-for="quiz in filteredQuizzes" :key="quiz.id">
                    <div class="quiz-card card h-100 border-0 shadow-sm">
                        <!-- Quiz Image -->
                        <div class="quiz-image position-relative">
                            <img :src="quiz.image" :alt="quiz.title" class="card-img-top">
                            <div class="quiz-overlay">
                                <span class="difficulty-badge" :class="'difficulty-' + quiz.difficulty.toLowerCase()">
                                    @{{ quiz.difficulty }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Category -->
                            <div class="quiz-category mb-2">
                                <span class="badge bg-primary-soft text-primary">
                                    @{{ getCategoryName(quiz.category_id) }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="card-title h5 mb-3">@{{ quiz.title }}</h3>

                            <!-- Quiz Meta -->
                            <div class="quiz-meta d-flex align-items-center mb-3">
                                <div class="meta-item me-3">
                                    <i class="fas fa-question-circle text-muted me-1"></i>
                                    @{{ quiz.questions_count }} Questions
                                </div>
                                <div class="meta-item me-3">
                                    <i class="fas fa-clock text-muted me-1"></i>
                                    @{{ formatDuration(quiz.duration) }}
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-users text-muted me-1"></i>
                                    @{{ quiz.attempts_count }} Attempts
                                </div>
                            </div>

                            <!-- Quiz Stats -->
                            <div class="quiz-stats d-flex align-items-center justify-content-between mb-3">
                                <div class="rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <span class="ms-1">@{{ quiz.rating }}/5</span>
                                </div>
                                <div class="completion-rate">
                                    <div class="progress" style="width: 100px; height: 6px;">
                                        <div class="progress-bar bg-success" 
                                             :style="{ width: quiz.completion_rate + '%' }" 
                                             role="progressbar"
                                             :aria-valuenow="quiz.completion_rate"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">@{{ quiz.completion_rate }}% Completion</small>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a :href="'/quiz/' + quiz.id" class="btn btn-primary w-100">
                                Start Quiz
                            </a>
                        </div>
                    </div>
                </div>
            </transition-group>

            <!-- Empty State -->
            <div class="empty-state text-center py-5" v-if="filteredQuizzes.length === 0">
                <img src="/assets/images/empty-state.svg" alt="No quizzes found" class="empty-state-image mb-4">
                <h3>No Quizzes Found</h3>
                <p class="text-muted">Try adjusting your filters or search query</p>
                <button class="btn btn-primary" @click="clearAllFilters">
                    Clear All Filters
                </button>
            </div>

            <!-- Load More -->
            <div class="text-center mt-5" v-if="hasMoreQuizzes">
                <button class="btn btn-outline-primary" 
                        @click="loadMoreQuizzes"
                        :disabled="isLoading">
                    <span v-if="!isLoading">Load More Quizzes</span>
                    <span v-else>
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
.quiz-listing {
    background-color: #f8f9fa;
}

.hero-section {
    background: linear-gradient(45deg, #007bff, #0056b3);
    position: relative;
    overflow: hidden;
}

.search-bar .form-control:focus {
    box-shadow: none;
}

.filter-group {
    background: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 50px;
    font-size: 0.875rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.quiz-card {
    border-radius: 1rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.quiz-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important;
}

.quiz-image {
    height: 200px;
    overflow: hidden;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}

.quiz-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.quiz-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.difficulty-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: capitalize;
}

.difficulty-easy {
    background: #d4edda;
    color: #155724;
}

.difficulty-medium {
    background: #fff3cd;
    color: #856404;
}

.difficulty-hard {
    background: #f8d7da;
    color: #721c24;
}

.bg-primary-soft {
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.quiz-meta {
    font-size: 0.875rem;
}

.empty-state-image {
    max-width: 300px;
    opacity: 0.7;
}

/* Animations */
.quiz-grid-enter-active, .quiz-grid-leave-active {
    transition: all 0.3s ease;
}

.quiz-grid-enter, .quiz-grid-leave-to {
    opacity: 0;
    transform: translateY(30px);
}

@media (max-width: 768px) {
    .hero-section {
        text-align: center;
    }

    .search-bar {
        max-width: 100%;
    }

    .quiz-image {
        height: 160px;
    }
}
</style>
@endpush

@push('scripts')
<script>
new Vue({
    el: '#quizListing',
    data: {
        quizzes: [],
        categories: [],
        difficulties: ['Easy', 'Medium', 'Hard'],
        selectedCategory: '',
        selectedDifficulty: '',
        searchQuery: '',
        sortBy: 'newest',
        page: 1,
        isLoading: false,
        hasMoreQuizzes: true
    },
    computed: {
        filteredQuizzes() {
            return this.quizzes.filter(quiz => {
                const matchesCategory = !this.selectedCategory || quiz.category_id === this.selectedCategory;
                const matchesDifficulty = !this.selectedDifficulty || 
                                        quiz.difficulty.toLowerCase() === this.selectedDifficulty.toLowerCase();
                const matchesSearch = !this.searchQuery || 
                                    quiz.title.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                return matchesCategory && matchesDifficulty && matchesSearch;
            });
        },
        hasActiveFilters() {
            return this.selectedCategory || this.selectedDifficulty || this.searchQuery;
        }
    },
    methods: {
        async fetchQuizzes() {
            this.isLoading = true;
            try {
                const response = await fetch(`/api/quizzes?page=${this.page}&category=${this.selectedCategory}&difficulty=${this.selectedDifficulty}&sort=${this.sortBy}&search=${this.searchQuery}`);
                const data = await response.json();
                
                if (this.page === 1) {
                    this.quizzes = data.quizzes;
                } else {
                    this.quizzes = [...this.quizzes, ...data.quizzes];
                }
                
                this.hasMoreQuizzes = data.has_more;
            } catch (error) {
                console.error('Error fetching quizzes:', error);
            } finally {
                this.isLoading = false;
            }
        },
        async fetchCategories() {
            try {
                const response = await fetch('/api/categories');
                this.categories = await response.json();
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },
        getCategoryName(categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            return category ? category.name : '';
        },
        selectDifficulty(level) {
            this.selectedDifficulty = this.selectedDifficulty === level ? '' : level;
            this.filterQuizzes();
        },
        filterQuizzes() {
            this.page = 1;
            this.fetchQuizzes();
        },
        loadMoreQuizzes() {
            this.page++;
            this.fetchQuizzes();
        },
        clearCategory() {
            this.selectedCategory = '';
            this.filterQuizzes();
        },
        clearDifficulty() {
            this.selectedDifficulty = '';
            this.filterQuizzes();
        },
        clearAllFilters() {
            this.selectedCategory = '';
            this.selectedDifficulty = '';
            this.searchQuery = '';
            this.filterQuizzes();
        },
        formatDuration(minutes) {
            if (minutes < 60) {
                return `${minutes} min`;
            }
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            return remainingMinutes > 0 ? 
                   `${hours}h ${remainingMinutes}m` : 
                   `${hours}h`;
        }
    },
    mounted() {
        this.fetchCategories();
        this.fetchQuizzes();
    }
});
</script>
@endpush
