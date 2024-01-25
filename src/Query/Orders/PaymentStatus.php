<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Query\Orders;

class PaymentStatus
{
    /**
     * Paid and not refunded.
     */
    public const PAID = 'paid';

    /**
     * Not paid and not refunded and external payment not requested.
     */
    public const UNPAID = 'unpaid';

    /**
     * Cancelled.
     */
    public const CANCELLED = 'cancelled';

    /**
     * Refunded.
     */
    public const REFUNDED = 'refunded';

    /**
     * External payment requested but still not paid and .
     */
    public const INCOMPLETE = 'incomplete';
}
