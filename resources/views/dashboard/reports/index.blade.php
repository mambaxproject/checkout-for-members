@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Relatórios</h1>

        <div class="space-y-6">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-2">

               <a href="{{ route('dashboard.reports.metricsSubscriptions') }}"
                  title="Métricas de assinaturas"
                  class="bg-white p-5 rounded-lg shadow-sm block hover:shadow-md transition"
               >
                    <div class="col-span-1 flex items-center space-x-3">

                        @include('components.icon', [
                            'type' => 'fill',
                            'icon' => 'bar_chart',
                            'custom' => 'text-5xl',
                        ])

                        <h2 class="text-lg font-semibold">
                            Métricas de assinaturas
                        </h2>
                    </div>
                </a>

            </div>

        </div>

    </div>
@endsection
