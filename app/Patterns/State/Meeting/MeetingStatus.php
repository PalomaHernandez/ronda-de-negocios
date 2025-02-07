<?php

namespace App\Patterns\State\Meeting;

enum MeetingStatus:string {

	case Pending = 'Pendiente'; //Inscripción

	case Accepted = 'Aceptada'; //Difusión

	case Rejected = 'Rechazada'; //Matcheo

	const DEFAULT_STATUS = self::Pending;


	/*public function canUpdate():bool{
		return match ($this) {
			self::Payment, self::Pending, self::Documentation => true,
			self::Accepted, self::Rejected => false,
		};
	}*/

}