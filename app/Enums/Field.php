<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Field extends Enum
{
    const MIN_STRING = 3;

    const MAX_DESC_LOW = 12;
    const MAX_DESC_MIDDLE = 32;
    const MAX_DESC_HIGH = 64;

    // user names
    const MAX_NAME_USER = 32;
    const MIN_PASS_USER = 6;
    const MAX_PASS_USER = 32;
    const MAX_NAME_ROLE = 12;
    
    // document
    const MAX_DOC_NUM = 12;
    const MAX_DOC_VAL = 999999999999;
    const MIN_DOC_VAL = 10000000;
    
    // email
    const MAX_EMAIL = 48;
    const MIN_EMAIL = 6;
     
    // id roles
    const ID_ROLE_SUPERADMIN = 1;
    const ID_ROLE_ADMIN = 2;
    const ID_ROLE_USER = 3;
    const ID_ROLE_GUESS = 4;
}
