@extends('layouts.members')

@section('content')
    <div class="mb-4">
        <nav class="no-scrollbar flex items-center overflow-x-auto border-b border-neutral-300"
            data-tabs-toggle="#page-tab-content">
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-domain" aria-selected="false" role="tab" type="button">
                Dominio próprio
            </button>
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-comments" aria-selected="false" role="tab" type="button">
                Comentários
            </button>
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-links" aria-selected="false" role="tab" type="button">
                Link do curso
            </button>
            <button
                class="whitespace-nowrap border-b-2 px-5 py-4 hover:border-primary aria-selected:border-primary aria-selected:font-bold aria-selected:text-neutral-800"
                data-tabs-target="#tab-related-courses" aria-selected="false" role="tab" type="button">
                Cursos recomendados
            </button>
        </nav>
    </div>
    <div id="page-tab-content">
        @include('partials.dashboard.membersSettings.tab-domain')
        @include('partials.dashboard.membersSettings.tab-comments')
        @include('partials.dashboard.membersSettings.tab-links')
        @include('partials.dashboard.membersSettings.tab-related-courses')
    </div>
    <script>
        function loadOnTab() {
            let hash = window.location.hash;

            if (hash.includes('tab=')) {
                let tab = hash.split('tab=')[1];
                let tabButton = document.querySelector(`button[data-tabs-target="#${tab}"]`);

                if (tabButton) {
                    tabButton.click();
                }
            }
        }
        window.addEventListener("load", () => {
            setTimeout(loadOnTab, 50);
        });
    </script>
@endsection
