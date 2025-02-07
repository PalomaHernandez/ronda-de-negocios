<?php

namespace Database\Factories;

use App\Patterns\State\Meeting\MeetingStatus;
use App\Patterns\Role\RequesterRole;
use App\Models\Event;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Meeting::class;
    public function definition(): array
    {
        // Obtener todos los usuarios con el rol "participant"
        $participants = User::whereHas('roles', function ($query) {
            $query->where('name', 'participant');
        })->inRandomOrder()->get(['id']);

        // Si hay menos de 2 participantes, no se puede crear la reunión
        if ($participants->count() < 2) {
            throw new \Exception("No hay suficientes usuarios con el rol 'participant' para crear una reunión.");
        }

        // Seleccionar aleatoriamente dos usuarios diferentes
        $requester = $participants->random();
        $receiver = $participants->where('id', '!=', $requester->id)->random();

        if (!$receiver) {
            throw new \Exception("No se encontró un usuario diferente con rol 'participant' para asignar como receiver.");
        }
        $event = Event::inRandomOrder()->first(['id']);

        if (!$event) {
            throw new \Exception("No hay eventos en la base de datos para asignar a la reunión.");
        }
        return [
            'requester_id' => $requester->id,
            'receiver_id' => $receiver->id,
            'event_id' => $event->id,
            'reason' => $this->faker->sentence,
            'requester_role' => RequesterRole::Buyer,
            'time' =>  $this->faker->dateTimeBetween('now', '+1 year')->format('H:i:s'),
            'status' => MeetingStatus::Pending,
        ];

    }
}
