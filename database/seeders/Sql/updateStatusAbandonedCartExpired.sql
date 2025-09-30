UPDATE checkout.abandoned_carts
SET status = 'expired', deleted_at = NULL
WHERE status = 'not_paid';