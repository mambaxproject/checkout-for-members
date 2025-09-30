UPDATE checkout.abandoned_carts
SET client_abandoned_cart_uuid = UUID()
WHERE client_abandoned_cart_uuid IS NULL;