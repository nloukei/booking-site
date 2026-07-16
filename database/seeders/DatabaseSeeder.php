<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => UserRole::Admin,
        ]);

        // Create Regular User
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role' => UserRole::User,
        ]);

        // Seed Venues
        \App\Models\Venue::create([
            'name' => 'Downtown Creative Studio',
            'description' => 'A bright, open-concept studio perfect for photoshoots, workshops, and intimate creative gatherings. Features high ceilings, exposed brick, and premium lighting equipment.',
            'capacity' => 45,
            'price_per_hour' => 65.00,
            'location' => 'SoHo, New York',
            'image_path' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=800&q=80',
        ]);

        \App\Models\Venue::create([
            'name' => 'The Skyline Rooftop Lounge',
            'description' => 'Stunning 360-degree views of the city skyline. Complete with comfortable outdoor seating, a fully-equipped bar area, and ambient string lighting. Perfect for corporate socials and evening parties.',
            'capacity' => 120,
            'price_per_hour' => 150.00,
            'location' => 'Midtown, New York',
            'image_path' => 'https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=800&q=80',
        ]);

        \App\Models\Venue::create([
            'name' => 'Grand Crystal Ballroom',
            'description' => 'An elegant ballroom featuring majestic crystal chandeliers, a spacious hardwood dance floor, and a built-in stage. Excellent for weddings, galas, and large conferences.',
            'capacity' => 350,
            'price_per_hour' => 250.00,
            'location' => 'Upper East Side, New York',
            'image_path' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80',
        ]);

        \App\Models\Venue::create([
            'name' => 'Urban Co-Working Boardroom',
            'description' => 'A sleek, modern meeting space equipped with a 4K presentation screen, high-speed fiber internet, video conferencing facilities, and whiteboards. Fits your team meetings and client pitches perfectly.',
            'capacity' => 14,
            'price_per_hour' => 45.00,
            'location' => 'Chelsea, New York',
            'image_path' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80',
        ]);

        \App\Models\Venue::create([
            'name' => 'The Garden Pavillion',
            'description' => 'A picturesque outdoor garden pavilion surrounded by lush greenery, flowering rosebushes, and a stone pathway. Ideal for tea parties, family gatherings, and outdoor ceremonies.',
            'capacity' => 80,
            'price_per_hour' => 95.00,
            'location' => 'Brooklyn Heights, New York',
            'image_path' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80',
        ]);

        \App\Models\Venue::create([
            'name' => 'Industrial Loft Event Space',
            'description' => 'A spacious, industrial-chic loft with massive factory windows, polished concrete floors, and a modular layout. Versatile space for pop-up shops, galleries, and private events.',
            'capacity' => 200,
            'price_per_hour' => 180.00,
            'location' => 'DUMBO, Brooklyn',
            'image_path' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
        ]);

        // Seed Bookings
        \App\Models\Booking::create([
            'venue_id' => 1,
            'user_id' => 2,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '17:00:00',
            'status' => 'confirmed',
        ]);

        \App\Models\Booking::create([
            'venue_id' => 1,
            'user_id' => 2,
            'date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'status' => 'confirmed',
        ]);

        \App\Models\Booking::create([
            'venue_id' => 2,
            'user_id' => 2,
            'date' => now()->addDay()->toDateString(),
            'start_time' => '18:00:00',
            'end_time' => '22:00:00',
            'status' => 'confirmed',
        ]);
    }
}

