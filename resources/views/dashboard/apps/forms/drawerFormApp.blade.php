@component('components.drawer', [
        'id' => 'drawerFormApp',
        'title' => 'App',
        'custom' => 'max-w-xl',
    ])

    <div id="contentFormApp"></div>
@endcomponent

@push('custom-script')
    <script>
        $('#contentFormApp').on('click', 'input[name="status"]', function() {
            $(this).val($(this).val() === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE');
        });
    </script>
@endpush