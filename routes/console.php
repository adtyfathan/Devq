<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('schedule:fetch-quiz-questions', function () {
    Artisan::call('fetch:quiz-questions');
    Log::info('Scheduled fetch:quiz-questions command ran.');
})->describe('Run fetch quiz questions command');

// Schedule it to run every minute
app(Schedule::class)->command('schedule:fetch-quiz-questions')->everyMinute();