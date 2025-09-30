<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 46,
                'title' => 'admin_access',
            ],
            [
                'id'    => 47,
                'title' => 'gender_create',
            ],
            [
                'id'    => 48,
                'title' => 'gender_edit',
            ],
            [
                'id'    => 49,
                'title' => 'gender_show',
            ],
            [
                'id'    => 50,
                'title' => 'gender_delete',
            ],
            [
                'id'    => 51,
                'title' => 'gender_access',
            ],
            [
                'id'    => 52,
                'title' => 'marital_status_create',
            ],
            [
                'id'    => 53,
                'title' => 'marital_status_edit',
            ],
            [
                'id'    => 54,
                'title' => 'marital_status_show',
            ],
            [
                'id'    => 55,
                'title' => 'marital_status_delete',
            ],
            [
                'id'    => 56,
                'title' => 'marital_status_access',
            ],
            [
                'id'    => 57,
                'title' => 'state_create',
            ],
            [
                'id'    => 58,
                'title' => 'state_edit',
            ],
            [
                'id'    => 59,
                'title' => 'state_show',
            ],
            [
                'id'    => 60,
                'title' => 'state_delete',
            ],
            [
                'id'    => 61,
                'title' => 'state_access',
            ],
            [
                'id'    => 62,
                'title' => 'city_create',
            ],
            [
                'id'    => 63,
                'title' => 'city_edit',
            ],
            [
                'id'    => 64,
                'title' => 'city_show',
            ],
            [
                'id'    => 65,
                'title' => 'city_delete',
            ],
            [
                'id'    => 66,
                'title' => 'city_access',
            ],
            [
                'id'    => 67,
                'title' => 'produto_access',
            ],
            [
                'id'    => 68,
                'title' => 'category_product_create',
            ],
            [
                'id'    => 69,
                'title' => 'category_product_edit',
            ],
            [
                'id'    => 70,
                'title' => 'category_product_show',
            ],
            [
                'id'    => 71,
                'title' => 'category_product_delete',
            ],
            [
                'id'    => 72,
                'title' => 'category_product_access',
            ],
            [
                'id'    => 73,
                'title' => 'product_create',
            ],
            [
                'id'    => 74,
                'title' => 'product_edit',
            ],
            [
                'id'    => 75,
                'title' => 'product_show',
            ],
            [
                'id'    => 76,
                'title' => 'product_delete',
            ],
            [
                'id'    => 77,
                'title' => 'product_access',
            ],
            [
                'id'    => 78,
                'title' => 'pedido_access',
            ],
            [
                'id'    => 79,
                'title' => 'order_create',
            ],
            [
                'id'    => 80,
                'title' => 'order_edit',
            ],
            [
                'id'    => 81,
                'title' => 'order_show',
            ],
            [
                'id'    => 82,
                'title' => 'order_delete',
            ],
            [
                'id'    => 83,
                'title' => 'order_access',
            ],
            [
                'id'    => 84,
                'title' => 'item_order_create',
            ],
            [
                'id'    => 85,
                'title' => 'item_order_edit',
            ],
            [
                'id'    => 86,
                'title' => 'item_order_show',
            ],
            [
                'id'    => 87,
                'title' => 'item_order_delete',
            ],
            [
                'id'    => 88,
                'title' => 'item_order_access',
            ],
            [
                'id'    => 89,
                'title' => 'order_payment_create',
            ],
            [
                'id'    => 90,
                'title' => 'order_payment_edit',
            ],
            [
                'id'    => 91,
                'title' => 'order_payment_show',
            ],
            [
                'id'    => 92,
                'title' => 'order_payment_delete',
            ],
            [
                'id'    => 93,
                'title' => 'order_payment_access',
            ],
            [
                'id'    => 94,
                'title' => 'discount_order_create',
            ],
            [
                'id'    => 95,
                'title' => 'discount_order_edit',
            ],
            [
                'id'    => 96,
                'title' => 'discount_order_show',
            ],
            [
                'id'    => 97,
                'title' => 'discount_order_delete',
            ],
            [
                'id'    => 98,
                'title' => 'discount_order_access',
            ],
            [
                'id'    => 99,
                'title' => 'discount_coupon_create',
            ],
            [
                'id'    => 100,
                'title' => 'discount_coupon_edit',
            ],
            [
                'id'    => 101,
                'title' => 'discount_coupon_show',
            ],
            [
                'id'    => 102,
                'title' => 'discount_coupon_delete',
            ],
            [
                'id'    => 103,
                'title' => 'discount_coupon_access',
            ],
            [
                'id'    => 108,
                'title' => 'type_product_access',
            ],
            [
                'id'    => 109,
                'title' => 'affiliate_create',
            ],
            [
                'id'    => 110,
                'title' => 'affiliate_edit',
            ],
            [
                'id'    => 111,
                'title' => 'affiliate_show',
            ],
            [
                'id'    => 112,
                'title' => 'affiliate_delete',
            ],
            [
                'id'    => 113,
                'title' => 'affiliate_access',
            ],
            [
                'id'    => 119,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 119,
                'title' => 'shop_create',
            ],
            [
                'id'    => 120,
                'title' => 'shop_edit',
            ],
            [
                'id'    => 121,
                'title' => 'shop_show',
            ],
            [
                'id'    => 122,
                'title' => 'shop_delete',
            ],
            [
                'id'    => 123,
                'title' => 'shop_access',
            ],
            [
                'id'    => 124,
                'title' => 'dashboard_admin_access',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['id' => $permission['id']], $permission);
        }

    }
}
