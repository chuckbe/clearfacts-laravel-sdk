<?php

namespace Clearfacts\Enums;

enum PaymentState: string
{
    case PAID = 'PAID';
    case UNPAID = 'UNPAID';
}
