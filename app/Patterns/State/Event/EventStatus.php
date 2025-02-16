<?php

namespace App\Patterns\State\Event;

enum EventStatus:string {

	case Registration = 'Inscripcion'; 

	case Matching = 'Matcheo'; 

	case Ended = 'Terminado';


	/*public function canUpdate():bool{
		return match ($this) {
			self::Payment, self::Pending, self::Documentation => true,
			self::Accepted, self::Rejected => false,
		};
	}*/

}