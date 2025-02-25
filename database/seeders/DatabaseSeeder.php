<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\User;
use Silber\Bouncer\BouncerFacade;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Image;
use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles con sus permisos
        foreach (config('roles') as $roleName => $abilities) {
            $role = BouncerFacade::role()->create([
                'name' => Str::slug($roleName),
                'title' => $roleName
            ]);
            BouncerFacade::allow($role)->to($abilities);
        }

        // Crear un administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);
        $admin->assign('administrator');

        // Crear responsables y participantes
        $users = collect([
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Desarrollo de software',
            ],
            [
                'name' => 'María Gómez',
                'email' => 'maria.gomez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Empresa textil',
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos.lopez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria petrolera',
            ],
            [
                'name' => 'Juan Fernández',
                'email' => 'juan.fernandez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria alimenticia',
            ],
            [
                'name' => 'Carlos Fernández',
                'email' => 'carlos.fernandez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria alimenticia',
            ],
            [
                'name' => 'Martin Fernández',
                'email' => 'martin.fernandez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria alimenticia',
            ],
            [
                'name' => 'Ana Lopez',
                'email' => 'ana.lopez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria petrolera',
            ],
            [
                'name' => 'Israel Fernández',
                'email' => 'israel.fernandez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria alimenticia',
            ],
            [
                'name' => 'Luis Fernández',
                'email' => 'luis.fernandez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria alimenticia',
            ],
            [
                'name' => 'Paola Fernández',
                'email' => 'paola.fernandez@gmail.com',
                'password' => Hash::make('password'),
                'activity' => 'Industria alimenticia',
            ]
        ])->map(function ($userData) {
            $user = User::factory()->create($userData);
            $user->assign('participant');
            return $user;
        });

        $responsible = User::create([
            'name' => 'Responsable',
            'email' => 'responsable@gmail.com',
            'password' => Hash::make('responsable'),
        ]);
        $responsible->assign('responsible');

        // Crear eventos con datos reales
        $events = collect([
            [
                'title' => 'Congreso de Tecnología 2025',
                'description' => 'Un evento para conocer las últimas tendencias en tecnología.',
                'location' => 'Centro de Convenciones, Buenos Aires',
                'date' => now()->addMonths(2),
                'starts_at' => '09:00:00',
                'ends_at' => '17:00:00',
                'inscription_end_date' => '2025-05-10T23:59',
                'matching_end_date' => '2025-05-10T23:59',
                'slug' => Str::slug('Congreso de Tecnología 2025'),
                'responsible_id' => $responsible->id,
            ],
            [
                'title' => 'Feria de Innovación',
                'description' => 'Espacio para startups y emprendedores tecnológicos.',
                'location' => 'Hotel Hilton, Ciudad de México',
                'date' => now()->addMonths(3),
                'inscription_end_date' => '2025-05-10T23:59',
                'matching_end_date' => '2025-05-10T23:59',
                'starts_at' => '09:00:00',
                'ends_at' => '17:00:00',
                'slug' => Str::slug('Feria de Innovación'),
                'responsible_id' => $responsible->id,
            ],
            [
                'title' => 'Cumbre Empresarial 2025',
                'description' => 'Reunión de líderes del sector empresarial para discutir el futuro.',
                'location' => 'WTC, Madrid',
                'inscription_end_date' => '2025-05-10T23:59',
                'matching_end_date' => '2025-05-10T23:59',
                'date' => now()->addMonths(1),
                'starts_at' => '09:00:00',
                'ends_at' => '17:00:00',
                'slug' => Str::slug('Cumbre Empresarial 2025'),
                'responsible_id' => $responsible->id,
            ]
        ])->map(fn ($eventData) => Event::create($eventData));

        // Inscribir participantes a eventos
        $participants = User::whereHas('roles', fn ($q) => $q->where('name', 'participant'))->get();
        foreach ($participants as $participant) {
            foreach ($events->random(1) as $event) {
                Registration::create([
                    'participant_id' => $participant->id,
                    'event_id' => $event->id,
                    'interests' => 'Tecnología, IA, Blockchain',
                    'products_services' => 'Consultoría en la industria',
                    'remaining_meetings' => rand(3, 10),
                ]);
            }
        }

        $meetings = [
            ['title' => 'Reunión de Networking', 'reason' => 'Intercambio de ideas y colaboración'],
            ['title' => 'Mesa Redonda de Innovación', 'reason' => 'Discusión sobre tendencias del sector'],
            ['title' => 'Workshop de IA', 'reason' => 'Taller práctico sobre inteligencia artificial'],
        ];

        foreach ($meetings as $meetingData) {
            Meeting::factory()->create([
                'reason' => $meetingData['title'],
            ]);
        }
    }
}
