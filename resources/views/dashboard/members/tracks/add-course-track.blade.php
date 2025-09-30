@extends('layouts.members')

@section('content')
    <div class="space-y-6">
        <h3>Adicionar curso</h3>
        <form class="space-y-6" id="formTrack"
            action="{{ route('dashboard.members.createCourseTrack', ['trackId' => $track['id']]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            {{ csrf_field() }}
            <input type="hidden" name="inputDraft" value="">
            <input type="hidden" name="isTrack" value=true>
            <input type="hidden" name="parentId" value={{ $course['id'] }}>
            <input type="hidden" name="trackId" value={{ $track['id'] }}>
            <input name="redirect" id="redirectMembers" value="1" type="hidden">
            <input name="categoryId" value="{{ $course['categoryId'] }}" type="hidden">
            <input name="productUuid" value="{{ $course['productRef'] }}" type="hidden">
            @component('components.card')
                <div class="space-y-6 px-6 py-6">

                    <div class="">

                        <label for="">
                            Nome do curso
                        </label>

                        <input name="name" required placeholder="Digite o nome do curso" type="text">

                    </div>
                    <div class="">

                        <label for="">
                            Descrição do curso (Opcional)
                        </label>

                        <textarea name="description" minlength="30" maxlength="245"
                            placeholder="Explique o seu curso em no máximo 245 characters" rows="5"></textarea>

                    </div>
                    <div class="">

                        <label for="thumbnail">
                            Thumbnail do curso
                        </label>

                        @component('components.dropzone', [
                            'id' => 'thumbnail',
                            'name' => 'thumbnail',
                            'accept' => 'image/*',
                            'required' => true,
                        ])
                        @endcomponent

                        <p class="mt-1 text-sm text-neutral-400">Adicione uma image da Thumbnail com no máximo de 5mb</p>

                    </div>
                </div>
            @endcomponent
            <div class="flex items-center justify-end">

                <button
                    class="button h-12 rounded-full border border-neutral-200 hover:bg-neutral-200 active:bg-neutral-300"
                    type="submit" onclick="submitForm(true)">
                    Salvar como rascunho
                </button>

                <button class="button button-primary h-12 rounded-full" type="submit" onclick="submitForm(false)">
                    Salvar Alterações
                </button>

            </div>

        </form>

    </div>
    <script>
        function submitForm(isDraft) {
            document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
            document.getElementById('formTrack').submit();
        }
    </script>
@endsection
