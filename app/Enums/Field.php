<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Field extends Enum
{
    const MAX_NAME_USER = 32;
    const MAX_NAME_ROLE = 12;
 
    const MAX_DOC_NUM = 12;
 
    const MAX_EMAIL = 48;
 
    const MAX_DESC_LOW = 12;
    const MAX_DESC_MIDDLE = 32;
    const MAX_DESC_HIGH = 64;

    // id roles
    const ID_ROLE_SUPERADMIN = 1;
    const ID_ROLE_ADMIN = 2;
    const ID_ROLE_USER = 3;
    const ID_ROLE_GUESS = 4;
}
