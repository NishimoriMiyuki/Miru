<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    DB::table('pages')
    ->where('deleted_at', '<', now()->subDays(7))
    ->delete();
})->daily();

Schedule::call(function () {
    DB::table('boards')
    ->where('deleted_at', '<', now()->subDays(7))
    ->delete();
})->daily();