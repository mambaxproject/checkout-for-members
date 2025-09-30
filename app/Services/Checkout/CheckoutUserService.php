<?php

namespace App\Services\Checkout;

use App\Http\Requests\Checkout\PaymentRequest;
use App\Models\Customer;

class CheckoutUserService
{
    public function createCustomer(PaymentRequest $request): Customer
    {
        $data                    = data_get($request->all(), 'user');
        $data['document_number'] = preg_replace('/[^0-9]/', '', $data['document_number']);

        $customer = Customer::whereEmail($data['email'])
            ->first()?->makeVisible('document_number');

        if ($customer) {
            if (! count(array_diff($customer->only(['name', 'email', 'document_number', 'phone_number']), $data))) {
                return $customer;
            }

            $customer->delete();
        }

        return Customer::create($data);
    }
}
