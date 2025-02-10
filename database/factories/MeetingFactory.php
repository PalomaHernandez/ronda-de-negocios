<?php

namespace Database\Factories;

use App\Patterns\State\Meeting\MeetingStatus;
use App\Patterns\Role\RequesterRole;
use App\Models\Event;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Registration; 
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

        // Asegurar que el requester tenga una inscripción en el evento
        $this->ensureRegistrationExists($requester->id, $event->id);
        // Asegurar que el receiver tenga una inscripción en el evento
        $this->ensureRegistrationExists($receiver->id, $event->id);

        return [
            'requester_id' => $requester->id,
            'receiver_id' => $receiver->id,
            'event_id' => $event->id,
            'reason' => $this->faker->sentence,
            'requester_role' => $this->faker->randomElement([
                RequesterRole::Supplier,
                RequesterRole::Buyer,
                RequesterRole::Both,
            ]), // Ahora es aleatorio
            'time' =>  $this->faker->dateTimeBetween('now', '+1 year')->format('H:i:s'),
            'status' => $this->faker->randomElement([
                MeetingStatus::Accepted,
                MeetingStatus::Rejected,
                MeetingStatus::Pending,
            ]), // Ahora es aleatorio
        ];

    }
    private function ensureRegistrationExists($userId, $eventId)
    {
        $registrationExists = Registration::where('participant_id', $userId)
                                          ->where('event_id', $eventId)
                                          ->exists();

    }
}
