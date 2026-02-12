<?php

namespace App\Constants;

use MarcinOrlowski\ResponseBuilder\ApiCodesHelpers;

class BusinessCodes extends BaseCodes
{
    use ApiCodesHelpers;
    /*
     * MODULE AUTH & USER
     */
    public const UNAUTHORIZED = 401;
    public const SUCCESS = 1000;
    public const CREATED = 1001;
    public const UPDATED = 1002;
    public const ACCOUNT_LOCKED = 2001;
    public const INVALID_CREDENTIALS = 2002;
    public const AUTH_LOGIN_FAILED = 2003;
    public const INVALID_PASSWORD = 2004;
    public const AUTH_REGISTER_FAILED = 2005;

    /*
     * MODULE COURSE & CLASS
     */
    public const COURSE_NOT_FOUND = 3001;
    public const CLASS_FULL = 3002;
    public const SUBJECT_EXISTS = 3003;

    /*
     * MODULE SCHEDULING
     */
    public const TEACHER_BUSY = 4001;
    public const ROOM_OCCUPIED = 4002;
    public const SHIFT_INVALID = 4003;

    /*
     * MODULE TUITION & FINANCE
     */
    public const TUITION_NOT_PAID = 5001;
    public const INVOICE_CANCELLED = 5002;

    /*
     * MODULE STUDENT
     */
    public const OUT_OF_CREDITS = 6001;
    public const LEAVE_REQUEST_PENDING = 6002;

    public const READABLE_CODE_MAP = [
      'default' => 'UnknownBusinessError',
        self::ACCOUNT_LOCKED => 'Account Locked',
        self::INVALID_CREDENTIALS => 'InvalidCredentials',
        self::AUTH_LOGIN_FAILED => 'AuthenticationFailed',
        self::INVALID_PASSWORD => 'InvalidPassword',
        self::AUTH_REGISTER_FAILED => 'RegistrationFailed',
        self::COURSE_NOT_FOUND => 'CourseNotFound',
        self::CLASS_FULL => 'ClassFull',
        self::SUBJECT_EXISTS => 'SubjectExists',
        self::TEACHER_BUSY => 'TeacherScheduleConflict',
        self::ROOM_OCCUPIED => 'RoomScheduleConflict',
        self::SHIFT_INVALID => 'Shift Invalid',
        self::TUITION_NOT_PAID => 'Tuition not paid',
        self::INVOICE_CANCELLED => 'Invoice Cancelled',
        self::OUT_OF_CREDITS => 'OutOfCredits',
        self::LEAVE_REQUEST_PENDING => 'LeaveRequestIsPending',
    ];
}
