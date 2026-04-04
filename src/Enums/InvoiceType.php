<?php

namespace Clearfacts\Enums;

/**
 * The possible invoice types used for uploads (InvoiceTypeArgument)
 * and journal/document types (InvoiceType).
 */
enum InvoiceType: string
{
    case PURCHASE = 'PURCHASE';
    case SALE = 'SALE';
    case VARIOUS = 'VARIOUS';
}
