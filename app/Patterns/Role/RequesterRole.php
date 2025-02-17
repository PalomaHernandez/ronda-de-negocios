<?php

namespace App\Patterns\Role;

enum RequesterRole:string {

	case Buyer = 'Demandante';

	case Supplier = 'Oferente';

	case Both = 'Ambos'; 


	/*public function canUpdate():bool{
		return match ($this) {
			self::Payment, self::Pending, self::Documentation => true,
			self::Accepted, self::Rejected => false,
		};
	}*/

}