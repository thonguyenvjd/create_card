<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:check-scheduled-mail')->everyMinute();
