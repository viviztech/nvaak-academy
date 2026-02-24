<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstituteSeeder extends Seeder
{
    public function run(): void
    {
        $institute = Institute::firstOrCreate(
            ['slug' => 'nvaak-academy'],
            [
                'name'     => 'NVAAK Academy',
                'address'  => 'Chennai, Tamil Nadu',
                'city'     => 'Chennai',
                'state'    => 'Tamil Nadu',
                'phone'    => '+91-9876543210',
                'email'    => 'info@nvaakacademy.com',
                'website'  => 'https://nvaakacademy.com',
                'type'     => 'both',
                'is_active' => true,
                'settings' => [
                    'neet_enabled'     => true,
                    'ias_enabled'      => true,
                    'sms_enabled'      => false,
                    'razorpay_enabled' => false,
                ],
            ]
        );

        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@nvaakacademy.com'],
            [
                'name'              => 'NVAAK Admin',
                'password'          => Hash::make('Admin@123'),
                'institute_id'      => $institute->id,
                'phone'             => '+91-9876543210',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create demo admin
        $admin = User::firstOrCreate(
            ['email' => 'manager@nvaakacademy.com'],
            [
                'name'              => 'Academy Manager',
                'password'          => Hash::make('Admin@123'),
                'institute_id'      => $institute->id,
                'phone'             => '+91-9876543211',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create demo counsellor
        $counsellor = User::firstOrCreate(
            ['email' => 'counsellor@nvaakacademy.com'],
            [
                'name'              => 'Demo Counsellor',
                'password'          => Hash::make('Admin@123'),
                'institute_id'      => $institute->id,
                'phone'             => '+91-9876543212',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $counsellor->assignRole('counsellor');
    }
}
