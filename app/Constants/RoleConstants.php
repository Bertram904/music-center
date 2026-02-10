<?php

namespace App\Constants;

class RoleConstants
{
    public const ADMIN = 'admin';
    public const STUDENT = 'student';
    public const RECEPTIONIST = 'receptionist';
    public const TEACHER = 'teacher';
    public const ALL_ROLES = [
        self::ADMIN,
        self::STUDENT,
        self::RECEPTIONIST,
        self::TEACHER,
    ];
}
