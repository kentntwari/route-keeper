<?php

namespace App\Enums;

enum StopException: string
{
    case BusinessOff = 'business_off';
    case UnpaidOff = 'unpaid_off';
    case SickOff = 'sick_off';
    case VacationOff = 'vacation_off';
    case SuspendedOff = 'suspended_off';
    case TerminatedOff = 'terminated_off';
}
