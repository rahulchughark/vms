<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Room::create([
            'name' => 'Conference Room A',
            'description' => 'Large conference room with projector',
            'status' => 1,
        ]);

        \App\Models\Room::create([
            'name' => 'Meeting Room 1',
            'description' => 'Small meeting room for 4 people',
            'status' => 1,
        ]);

        \App\Models\Room::create([
            'name' => 'Huddle Room',
            'description' => 'Quiet space for quick catch-ups',
            'status' => 0,
        ]);
    }
}
