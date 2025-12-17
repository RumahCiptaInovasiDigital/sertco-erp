<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CalendarEvent::factory()->count(10)->create();
    }
}
