@extends('layouts.new-admin', ['title' => 'Revisões do produto'])

@section('content')
    <div class="grid grid-cols-12 md:gap-x-6">

        <div class="col-span-12">

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">

                    <div class="box-title before:hidden">Informações gerais</div>

                    <div class="{{ \App\Enums\SituationProductEnum::getClassAdmin($product->situation) }} flex items-center gap-2 rounded-full px-3 py-2">
                        <i class="bx bx-check-circle text-xl"></i>
                        {{ $product->situationTranslated }}
                    </div>

                </div>

                <div class="box-body space-y-4 p-2">

                    @component('components.admin.ui.card', ['cardTitle' => 'Informações gerais'])
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">Nome do Produto</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->name }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Categoria do Produto</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->category?->name }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Página de vendas</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('externalSalesLink') ?? '-' }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Descrição</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->description ?? '-' }}</p>
                            </div>

                        </div>
                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Revisões pendentes'])
                        <div class="space-y-4 table-responsive">

                            <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                <thead>
                                    <tr class="border-defaultborder border-b [&>th]:!px-6 [&>th]:text-start">
                                        <th>Data</th>
                                        <th>Tipo</th>
                                        <th>Antes</th>
                                        <th>Depois</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->revisions as $revision)
                                        <tr class="[&>td]:!px-6 [&>td]:text-start">
                                            <td>{{ $revision->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                {{ str($revision->key)->headline() }}
                                            </td>
                                            <td>
                                                @if($revision->key == 'anexo' && filled($revision->old_value))
                                                    <a href="{{ $revision->old_value['original_url'] }}" target="_blank">
                                                        Ver anexo
                                                        <i class="bx bx-link-external"></i>
                                                    </a>
                                                @elseif($revision->key == 'orderBump')
                                                    @foreach($revision->old_value as $key => $value)
                                                        <p>
                                                            <b>{{ getTranslatedNameAttributeProduct($key) }}:</b>
                                                            {{ getTranslatedValueAttributeRevisionsOrderBump($key, $value) }}
                                                        </p>
                                                    @endforeach
                                                @elseif(filled($revision->old_value))
                                                    @foreach($revision->old_value as $key => $value)
                                                        <p>
                                                            <b>{{ getTranslatedNameAttributeProduct($key) }}:</b>
                                                            {{ getTranslatedValueAttributeProduct($key, $value) }}
                                                        </p>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($revision->key == 'anexo')
                                                    <a href="{{ $revision->new_value['original_url'] }}" target="_blank">
                                                        Ver anexo
                                                        <i class="bx bx-link-external"></i>
                                                    </a>
                                                @elseif(in_array($revision->key, ['orderBump', 'novoOrderbump']))
                                                    @foreach($revision->new_value as $key => $value)
                                                        <p>
                                                            <b>{{ getTranslatedNameAttributeProduct($key) }}:</b>
                                                            {{ getTranslatedValueAttributeRevisionsOrderBump($key, $value) }}
                                                        </p>
                                                    @endforeach
                                                @else
                                                    @foreach($revision->new_value as $key => $value)
                                                        <p>
                                                            <b>{{ getTranslatedNameAttributeProduct($key) }}:</b>
                                                            {{ getTranslatedValueAttributeProduct($key, $value) }}
                                                        </p>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $revision->statusTranslated }}</td>
                                            <td>
                                                @if ($revision->isPending)
                                                    @component('components.admin.ui.dropdown', ['icon' => 'chevron-down'])
                                                        <li>
                                                            <form method="post"
                                                                  action="{{ route('admin.products.updateRevision', [$product->id, $revision->id]) }}"
                                                                  onsubmit="return confirm('Tem certeza?')"
                                                            >
                                                                @csrf
                                                                @method('PUT')

                                                                <input type="hidden" name="status" value="approved" />

                                                                <button
                                                                    class="ti-dropdown-item block !text-[0.8125rem] !font-medium"
                                                                    type="submit"
                                                                    title="Aprovar produto"
                                                                >
                                                                    <i class="bx bx-check"></i>
                                                                    Aprovar
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="post"
                                                                action="{{ route('admin.products.updateRevision', [$product->id, $revision->id]) }}"
                                                                onsubmit="return confirm('Tem certeza?')"
                                                            >
                                                                @csrf
                                                                @method('PUT')

                                                                <input type="hidden" name="status" value="reproved" />

                                                                <button
                                                                    class="ti-dropdown-item block !text-[0.8125rem] !font-medium"
                                                                    type="submit"
                                                                    title="Reprovar produto"
                                                                >
                                                                    <i class="bx bx-x"></i>
                                                                    Reprovar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endcomponent
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    @endcomponent

                </div>

            </div>
        </div>

    </div>
@endsection
