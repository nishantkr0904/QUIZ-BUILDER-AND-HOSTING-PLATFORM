<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SampleQuizzesSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if it doesn't exist
        if (!DB::table('users')->where('email', 'admin@qbhp.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Admin User',
                'email' => 'admin@qbhp.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create test user if it doesn't exist
        if (!DB::table('users')->where('email', 'nishant@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'nishant',
                'email' => 'nishant@gmail.com',
                'password' => bcrypt('password'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create categories if they don't exist
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Test your knowledge in web development technologies',
                'slug' => 'web-development',
            ],
            [
                'name' => 'Database',
                'description' => 'Database concepts and SQL queries',
                'slug' => 'database',
            ],
            [
                'name' => 'Data Structures',
                'description' => 'Core computer science concepts',
                'slug' => 'data-structures',
            ]
        ];

        foreach ($categories as $category) {
            if (!DB::table('categories')->where('slug', $category['slug'])->exists()) {
                DB::table('categories')->insert(array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        // Get admin user ID
        $adminId = DB::table('users')->where('email', 'admin@qbhp.com')->first()->id;

        // Get category IDs
        $webDevId = DB::table('categories')->where('slug', 'web-development')->first()->id;
        $databaseId = DB::table('categories')->where('slug', 'database')->first()->id;
        $dsaId = DB::table('categories')->where('slug', 'data-structures')->first()->id;

        // Create quizzes with varying difficulties
        $quizzes = [
            // Web Development Quizzes
            [
                'title' => 'HTML & CSS Basics',
                'description' => 'Test your knowledge of HTML and CSS fundamentals',
                'category_id' => $webDevId,
                'difficulty' => 'easy',
                'duration' => 15,
                'passing_score' => 60,
                'review_enabled' => true,
                'created_by' => $adminId,
                'slug' => 'html-css-basics',
            ],
            [
                'title' => 'JavaScript Advanced',
                'description' => 'Advanced concepts in JavaScript',
                'category_id' => $webDevId,
                'difficulty' => 'hard',
                'duration' => 30,
                'passing_score' => 75,
                'review_enabled' => true,
                'created_by' => $adminId,
                'slug' => 'javascript-advanced',
            ],
            // Database Quizzes
            [
                'title' => 'SQL Fundamentals',
                'description' => 'Basic SQL queries and database concepts',
                'category_id' => $databaseId,
                'difficulty' => 'easy',
                'duration' => 20,
                'passing_score' => 65,
                'review_enabled' => true,
                'created_by' => $adminId,
                'slug' => 'sql-fundamentals',
            ],
            [
                'title' => 'Database Design',
                'description' => 'Advanced database design concepts',
                'category_id' => $databaseId,
                'difficulty' => 'medium',
                'duration' => 25,
                'passing_score' => 70,
                'review_enabled' => true,
                'created_by' => $adminId,
                'slug' => 'database-design',
            ],
            // Data Structures Quizzes
            [
                'title' => 'Basic Data Structures',
                'description' => 'Arrays, Linked Lists, and Stacks',
                'category_id' => $dsaId,
                'difficulty' => 'easy',
                'duration' => 20,
                'passing_score' => 60,
                'review_enabled' => true,
                'created_by' => $adminId,
                'slug' => 'basic-data-structures',
            ],
            [
                'title' => 'Advanced Algorithms',
                'description' => 'Complex algorithmic problems and solutions',
                'category_id' => $dsaId,
                'difficulty' => 'hard',
                'duration' => 40,
                'passing_score' => 80,
                'review_enabled' => true,
                'created_by' => $adminId,
                'slug' => 'advanced-algorithms',
            ]
        ];

        // Insert quizzes if they don't exist and get their IDs
        $quizIds = [];
        foreach ($quizzes as $quiz) {
            if (!DB::table('quizzes')->where('slug', $quiz['slug'])->exists()) {
                DB::table('quizzes')->insert(array_merge($quiz, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
            $quizIds[$quiz['slug']] = DB::table('quizzes')->where('slug', $quiz['slug'])->first()->id;
        }

        // Sample questions for each quiz
        $questions = [
            // HTML & CSS Basics Questions
            [
                'quiz_id' => $quizIds['html-css-basics'],
                'questions' => [
                    [
                        'question_text' => 'Which HTML tag is used to create a hyperlink?',
                        'question_type' => 'multiple_choice',
                        'options' => json_encode(['<link>', '<a>', '<href>', '<url>']),
                        'correct_answer' => '<a>',
                        'explanation' => 'The <a> (anchor) tag is used to create hyperlinks in HTML.'
                    ],
                    [
                        'question_text' => 'What does CSS stand for?',
                        'question_type' => 'single_answer',
                        'options' => null,
                        'correct_answer' => 'Cascading Style Sheets',
                        'explanation' => 'CSS (Cascading Style Sheets) is a style sheet language used for describing the presentation of a document written in HTML.'
                    ],
                    [
                        'question_text' => 'In CSS, margin-collapse only occurs with vertical margins.',
                        'question_type' => 'true_false',
                        'options' => json_encode(['True', 'False']),
                        'correct_answer' => 'True',
                        'explanation' => 'Margin collapse only occurs with vertical margins in CSS, not with horizontal margins.'
                    ]
                ]
            ],
            // JavaScript Advanced Questions
            [
                'quiz_id' => $quizIds['javascript-advanced'],
                'questions' => [
                    [
                        'question_text' => 'Which of the following is not a JavaScript data type?',
                        'question_type' => 'multiple_choice',
                        'options' => json_encode(['String', 'Boolean', 'Float', 'Symbol']),
                        'correct_answer' => 'Float',
                        'explanation' => 'Float is not a distinct data type in JavaScript. Numbers in JavaScript are all of type "number".'
                    ],
                    [
                        'question_text' => 'What is the output of: console.log(typeof typeof 1)?',
                        'question_type' => 'single_answer',
                        'options' => null,
                        'correct_answer' => 'string',
                        'explanation' => 'typeof 1 returns "number", and typeof "number" returns "string".'
                    ],
                    [
                        'question_text' => 'In JavaScript, all objects inherit directly from Object.prototype.',
                        'question_type' => 'true_false',
                        'options' => json_encode(['True', 'False']),
                        'correct_answer' => 'False',
                        'explanation' => 'Objects can inherit from other objects through the prototype chain, not necessarily directly from Object.prototype.'
                    ]
                ]
            ],
            // SQL Fundamentals Questions
            [
                'quiz_id' => $quizIds['sql-fundamentals'],
                'questions' => [
                    [
                        'question_text' => 'Which SQL clause is used to filter rows in a result set?',
                        'question_type' => 'multiple_choice',
                        'options' => json_encode(['FILTER', 'WHERE', 'HAVING', 'GROUP']),
                        'correct_answer' => 'WHERE',
                        'explanation' => 'The WHERE clause is used to filter rows based on specified conditions.'
                    ],
                    [
                        'question_text' => 'What does RDBMS stand for?',
                        'question_type' => 'single_answer',
                        'options' => null,
                        'correct_answer' => 'Relational Database Management System',
                        'explanation' => 'RDBMS is software that manages relational databases based on the relational model.'
                    ],
                    [
                        'question_text' => 'SQL is case-sensitive for table and column names.',
                        'question_type' => 'true_false',
                        'options' => json_encode(['True', 'False']),
                        'correct_answer' => 'False',
                        'explanation' => 'By default, SQL is not case-sensitive for table and column names, though some DBMS can be configured to be case-sensitive.'
                    ]
                ]
            ],
            // Database Design Questions
            [
                'quiz_id' => $quizIds['database-design'],
                'questions' => [
                    [
                        'question_text' => 'Which normal form deals with removing transitive dependencies?',
                        'question_type' => 'multiple_choice',
                        'options' => json_encode(['1NF', '2NF', '3NF', '4NF']),
                        'correct_answer' => '3NF',
                        'explanation' => 'Third Normal Form (3NF) addresses the removal of transitive dependencies in database design.'
                    ],
                    [
                        'question_text' => 'What is the main purpose of an index in a database?',
                        'question_type' => 'single_answer',
                        'options' => null,
                        'correct_answer' => 'To improve query performance by speeding up data retrieval',
                        'explanation' => 'Indexes are used to speed up data retrieval operations on database tables.'
                    ],
                    [
                        'question_text' => 'Every table must have a primary key.',
                        'question_type' => 'true_false',
                        'options' => json_encode(['True', 'False']),
                        'correct_answer' => 'False',
                        'explanation' => 'While it\'s a best practice to have a primary key, it\'s not mandatory for every table to have one.'
                    ]
                ]
            ],
            // Basic Data Structures Questions
            [
                'quiz_id' => $quizIds['basic-data-structures'],
                'questions' => [
                    [
                        'question_text' => 'Which data structure follows the LIFO principle?',
                        'question_type' => 'multiple_choice',
                        'options' => json_encode(['Queue', 'Stack', 'Array', 'Tree']),
                        'correct_answer' => 'Stack',
                        'explanation' => 'A Stack follows the Last In First Out (LIFO) principle.'
                    ],
                    [
                        'question_text' => 'What is the time complexity of searching in a sorted array using binary search?',
                        'question_type' => 'single_answer',
                        'options' => null,
                        'correct_answer' => 'O(log n)',
                        'explanation' => 'Binary search has a time complexity of O(log n) as it divides the search space in half with each step.'
                    ],
                    [
                        'question_text' => 'A linked list always uses less memory than an array.',
                        'question_type' => 'true_false',
                        'options' => json_encode(['True', 'False']),
                        'correct_answer' => 'False',
                        'explanation' => 'Linked lists require extra memory for storing node pointers, which can make them use more memory than arrays in some cases.'
                    ]
                ]
            ],
            // Advanced Algorithms Questions
            [
                'quiz_id' => $quizIds['advanced-algorithms'],
                'questions' => [
                    [
                        'question_text' => 'Which sorting algorithm has the best average-case time complexity?',
                        'question_type' => 'multiple_choice',
                        'options' => json_encode(['Bubble Sort', 'Quick Sort', 'Selection Sort', 'Insertion Sort']),
                        'correct_answer' => 'Quick Sort',
                        'explanation' => 'Quick Sort has an average time complexity of O(n log n) and is generally faster in practice.'
                    ],
                    [
                        'question_text' => 'What is the space complexity of recursive fibonacci implementation?',
                        'question_type' => 'single_answer',
                        'options' => null,
                        'correct_answer' => 'O(n)',
                        'explanation' => 'The space complexity is O(n) due to the recursive call stack depth.'
                    ],
                    [
                        'question_text' => 'Dynamic Programming always provides better time complexity than recursive solutions.',
                        'question_type' => 'true_false',
                        'options' => json_encode(['True', 'False']),
                        'correct_answer' => 'False',
                        'explanation' => 'While Dynamic Programming often improves time complexity, it doesn\'t always provide better time complexity than other approaches.'
                    ]
                ]
            ]
        ];

        // Insert questions for each quiz
        foreach ($questions as $quizQuestions) {
            $quiz_id = $quizQuestions['quiz_id'];
            foreach ($quizQuestions['questions'] as $question) {
                if (!DB::table('questions')->where([
                    'quiz_id' => $quiz_id,
                    'question_text' => $question['question_text']
                ])->exists()) {
                    DB::table('questions')->insert(array_merge($question, [
                        'quiz_id' => $quiz_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
            }
        }
    }
}
