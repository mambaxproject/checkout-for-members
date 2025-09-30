<?php

declare(strict_types = 1);

use App\Models\User;

function user(): ?User
{
    if (auth()->check()) {
        return \Cache::rememberForever('user::' . auth()->id(), function () {
            return auth()->user();
        });
    }

    return null;
}

function handleMediaFiles($rowModel, $fileCollectionName, $requestFiles): void
{
    $existingMedia     = $rowModel->getMedia($fileCollectionName);
    $existingFileNames = $existingMedia->pluck('file_name')->toArray();
    $requestFiles      = is_array($requestFiles) ? $requestFiles : [$requestFiles];
    $requestFiles      = array_filter($requestFiles);

    foreach ($existingMedia as $media) {
        if (! in_array($media->file_name, $requestFiles)) {
            $media->delete();
        }
    }

    foreach ($requestFiles as $file) {
        if (! in_array($file, $existingFileNames)) {
            $rowModel->addMedia($file)
                ->usingName(request()->input("media.$fileCollectionName.name", pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)))
                ->withCustomProperties(['description' => request()->input("media.$fileCollectionName.description", '')])
                ->toMediaCollection($fileCollectionName);
        }
    }
}

function generateStars(int $starsCount): string
{
    $totalStars = 5;
    $starsHTML  = '';

    for ($i = 0; $i < $starsCount; $i++) {
        $starsHTML .= '<li class="text-warning-400 star-full">★</li>';
    }

    for ($i = $starsCount; $i < $totalStars; $i++) {
        $starsHTML .= '<li class="text-neutral-400">☆</li>';
    }

    return $starsHTML;
}

function removeAccents($string): array|string
{
    return str_replace(
        ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'à', 'á', 'â', 'ã', 'ä', 'å', 'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'ò', 'ó', 'ô', 'õ', 'ö', 'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü', 'Ç', 'ç', 'Ñ', 'ñ'],
        ['A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a', 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'C', 'c', 'N', 'n'],
        $string
    );
}

function getTranslatedNameAttributeProduct(string $name): string
{
    return match ($name) {
        'name'                           => __('Nome'),
        'description'                    => __('Descrição'),
        'price'                          => __('Preço'),
        'infos'                          => __('Informações'),
        'payment_methods'                => __('Métodos de pagamento'),
        'renewsRecurringPayment'         => __('Renova o pagamento recorrente'),
        'cyclePayment'                   => __('Ciclo de pagamento'),
        'endDateRecurringPayment'        => __('Data de término do pagamento recorrente'),
        'numberPaymentsRecurringPayment' => __('Nº de pagamentos recorrentes'),
        'priceFirstPayment'              => __('Preço do primeiro pagamento'),
        'title_cta'                      => __('Chamada'),
        'promotional_price'              => __('Preço promocional'),
        'product_id'                     => __('Produto'),
        'product_offer_id'               => __('Oferta'),
        'paymentType'                    => __('Tipo de pagamento'),
        default                          => $name,
    };
}

function getTranslatedValueAttributeProduct(string $key, ?string $value): ?string
{
    return match ($key) {
        'cyclePayment'           => \App\Enums\CyclePaymentProductEnum::getFromName($value),
        'renewsRecurringPayment' => collect(config('products.renewsRecurringPayment'))->firstWhere('value', $value)['name'] ?? $value,
        'paymentType'            => \App\Enums\PaymentTypeProductEnum::getFromName($value),
        default                  => $value,
    };
}

function getTranslatedValueAttributeRevisionsOrderBump(string $key, string|array $value): string
{
    return match ($key) {
        'product_id', 'product_offer_id' => \App\Models\Product::where('id', $value)->value('name'),
        'payment_methods' => implode(', ', array_map(fn ($method) => \App\Enums\PaymentMethodEnum::getFromName($method), (array) $value)),
        default           => $value,
    };
}

function normalizePrice(string $value): float
{
    $value = preg_replace('/[^\d.,]/', '', $value);

    return (float) str_replace(',', '.', $value);
}
