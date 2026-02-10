<?php

namespace App\Constants;

class PermissionConstants
{
    public const USER_VIEW = "user.view";
    public const USER_CREATE = "user.create";
    public const USER_UPDATE = "user.update";
    public const USER_DELETE = "user.delete";

    public const SUBJECT_MANAGE = "subject.manage";
    public const COURSE_MANAGE = "course.manage";
    public const PACKAGE_MANAGE = "package.manage";

    public const ROOM_MANAGE = "room.manage";

    public const SCHEDULE_VIEW_ALL = "schedule.view.all";
    public const SCHEDULE_VIEW_OWN = "schedule.view.own";
    public const SCHEDULE_CREATE = "schedule.create";
    public const SCHEDULE_UPDATE = "schedule.update";

    public const STUDENT_PROFILE_MANAGE = "student.profile.manage";
    public const ENROLLMENT_CREATE = "enrollment.create";

    public const ATTENDANCE_MARK = "attendance.mark";
    public const FEEDBACK_LOG= "feedback.log";
    public const LEAVE_REQUEST = "leave.request";
    public const LEAVE_APPROVED = "leave.approved";

    public const INVOICE_CREATE = "invoice.create";
    public const INVOICE_VOID = "invoice.void";
    public const REVENUE_VIEW = "revenue.view";

    /**
     * Matrix separates role and permission
     */
    public static function getPermissionsByRole(): array
    {
        return [
            RoleConstants::ADMIN => [
                self::USER_VIEW, self::USER_CREATE, self::USER_UPDATE, self::USER_DELETE,
                self::SUBJECT_MANAGE, self::COURSE_MANAGE, self::PACKAGE_MANAGE,
                self::ROOM_MANAGE,
                self::SCHEDULE_VIEW_ALL, self::SCHEDULE_CREATE, self::SCHEDULE_UPDATE,
                self::INVOICE_CREATE, self::INVOICE_VOID, self::REVENUE_VIEW,
                self::LEAVE_APPROVED,
            ],
            RoleConstants::RECEPTIONIST => [
                self::USER_VIEW,
                self::STUDENT_PROFILE_MANAGE,
                self::ENROLLMENT_CREATE,
                self::SCHEDULE_VIEW_ALL, self::SCHEDULE_CREATE, self::SCHEDULE_UPDATE,
                self::INVOICE_CREATE,
                self::ATTENDANCE_MARK,
            ],
            RoleConstants::TEACHER => [
                self::SCHEDULE_VIEW_ALL,
                self::ATTENDANCE_MARK,
                self::FEEDBACK_LOG,
            ],
            RoleConstants::STUDENT => [
                self::SCHEDULE_VIEW_OWN,
                self::LEAVE_REQUEST,
            ],
        ];
    }
}
