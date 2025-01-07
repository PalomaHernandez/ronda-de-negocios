<?php

namespace App\Patterns\State\Event;

enum EventStatus:string {

	case Registration = 'Inscripcion'; //Inscripción

	case Matching = 'Matcheo'; //Matcheo

	case Ended = 'Terminada'; //Terminada


	/*public function canUpdate():bool{
		return match ($this) {
			self::Payment, self::Pending, self::Documentation => true,
			self::Accepted, self::Rejected => false,
		};
	}*/

}