@extends('layouts.new-admin', ['title' => 'Aprovar produto'])

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        <div class="col-span-12">

            @component('components.admin.ui.card', [
                'cardTitle' => 'Preview página de vendas',
                'customCardBody' => '!p-0',
            ])
                <iframe
                    class="h-full min-h-[calc(100vh-185px-130px)] w-full"
                    src="{{ $product->getValueSchemalessAttributes('externalSalesLink') }}"
                    frameborder="0"
                    seamless
                ></iframe>

                <div class="flex items-center justify-end gap-2 p-4">

                    @includeUnless($product->isReproved, 'partials.admin.products.reject-product')

                    @if ($product->isInAnalysis)
                        <form action="{{route('admin.products.updateSituation', $product)}}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="situation" value="{{ \App\Enums\SituationProductEnum::PUBLISHED->name }}" />
                            <button
                                    class="ti-btn ti-btn-success-full mb-0"
                                    type="submit"
                            >
                                <i class="bx bx-check"></i>
                                Aprovar produto
                            </button>
                        </form>
                    @endif
                </div>
            @endcomponent

        </div>

    </div>
@endsection
