@extends('layouts.members')

@section('content')
    <form id="formModulo" class="mt-4 mb-4" action="{{ route('dashboard.members.updateQuiz', ['lessonId' => $lesson['id']]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        {{ csrf_field() }}
        <div class="space-y-10">
            <h3>Editar conteúdo: Quiz</h3>
            <input type="hidden" name="courseId" value="{{ $course['id'] }}">
            <input type="hidden" name="old_name" value="{{ $lesson['name'] }}">
            <input type="hidden" name="old_description" value="{{ $lesson['description'] }}">
            <input type="hidden" name="draft" id="inputDraft" value="false">
            <div class="space-y-8">

                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Detalhes do conteúdo</h5>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12">
                                <label id="name" class="mb-1">Nome do quiz <span
                                        class="text-danger-400">*</span></label>
                                <input value="{{ $lesson['name'] }}" class="mt-2" type="text" id="name"
                                    name="name" placeholder="Digite o nome da sua aula" required maxlength="245" />
                            </div>

                            <div class="col-span-12">
                                <label id="name" class="mb-1">Descrição do quiz (Opcional)</label>
                                <textarea rows="6" id="description" name="description" maxlength="5000"
                                    placeholder="Explique o seu quiz em no máximo 5000 characters" oninput="setCharacterLimit(this)">{{ $lesson['description'] }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Perguntas</h5>
                    </div>
                    <div class="px-6 pb-6">

                        <div class="space-y-6">

                            <div class="questions-container space-y-6"></div>

                            <button
                                class="animate add-question flex w-full flex-col items-center justify-center rounded-2xl bg-neutral-50 py-6 hover:bg-neutral-100 active:bg-neutral-200"
                                type="button">
                                @include('components.icon', [
                                    'icon' => 'add_circle',
                                    'custom' => 'text-xl text-primary',
                                ])
                                <span class="text-sm font-medium">Adicionar questão</span>
                            </button>

                        </div>

                    </div>
                </div>
                @if (!empty($lesson['Attachments']))
                    <div class="col-span-12">
                        <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                            <div class="overflow-x-scroll md:overflow-visible">
                                <table class="table-lg table w-full">
                                    <thead>
                                        <tr>
                                            <th>Anexo</th>
                                            <th>Nome</th>
                                            <th>Extensão</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lesson['Attachments'] as $key => $attachments)
                                            <tr class="attachmentMedia">
                                                <td>
                                                    <a href="{{ $attachments['url'] }}" title="Ver anexo"
                                                        data-tooltip-text="Ver anexo" target="_blank">
                                                        @include('components.icon', [
                                                            'icon' => 'description',
                                                            'custom' => 'text-2xl',
                                                        ])
                                                    </a>
                                                </td>
                                                <td>{{ basename($attachments['url']) }}</td>
                                                <td>
                                                    <span
                                                        class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]">
                                                        {{ pathinfo($attachments['url'], PATHINFO_EXTENSION) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    @component('components.dropdown-button', [
                                                        'id' => 'dropdownMoreTableAnexofeaturedImage' . $key,
                                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                                        'customContainer' => 'ml-auto w-fit',
                                                        'custom' => 'text-xl',
                                                    ])
                                                        <ul>
                                                            <li>
                                                                <button
                                                                    class="deleteRow flex w-full items-center rounded-lg px-3 py-2 text-sm text-danger-500 hover:bg-danger-50"
                                                                    data-url="{{ route('dashboard.members.deleteLessonComplement', ['complementId' => $attachments['id']]) }}">
                                                                    Remover
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    @endcomponent
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="rounded-2xl bg-white">
                    <div class="p-6">
                        <h5 class="font-medium">Anexos</h5>
                    </div>
                    <div class="px-6 pb-6">
                        @include('components.dropzone', [
                            'id' => 'attachments[]',
                            'name' => 'attachments[]',
                            'accept' => 'image/*,application/pdf',
                            'isMultiple' => true,
                        ])
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4 mt-5">
                    <button
                        class="button h-12 rounded-full border border-neutral-200 hover:bg-neutral-200 active:bg-neutral-300"
                        onclick="submitForm(true)">
                        Salvar como rascunho
                    </button>

                    <button class="button button-primary h-12 rounded-full" onclick="submitForm(false)">
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-question', function() {
                const questionIndex = $('.question').length + 1;
                const questionContainer = $('.questions-container');
                const questionTemplate = `
                    <div class="question space-y-6" data-index="${questionIndex}">
                        <div class="flex items-center gap-3 mb-5">
                            
                            <div class="flex-1">
                                <label 
                                    class="title" 
                                    for="question${questionIndex}"
                                >
                                    Questão ${questionIndex}
                                </label>
                                <div class="flex items-center gap-3">
                                    
                                    <input
                                        id="question${questionIndex}"
                                        placeholder="Digite aqui o título do conteúdo"
                                        type="text"
                                        name="questions[${questionIndex}][question]"
                                    >
                                    
                                    <button
                                        class="remove-question animate flex h-12 w-12 items-center justify-center rounded-lg hover:bg-danger-50 hover:text-danger-800 active:bg-danger-500 active:text-white"
                                        type="button"
                                    >
                                        @include('components.icon', [
                                            'icon' => 'delete',
                                            'custom' => 'text-lg',
                                        ])
                                    </button>

                                </div>
                            </div>

                        </div>

                        <hr class="border-neutral-200">

                        <div class="space-y-6 alternatives-container"></div>

                        <button
                            class="add-alternative mt-5 animate flex w-full flex-col items-center justify-center rounded-2xl bg-neutral-50 py-6 hover:bg-neutral-100 active:bg-neutral-200"
                            type="button"
                            id = ${questionIndex}
                        >
                            @include('components.icon', [
                                'icon' => 'add_circle',
                                'custom' => 'text-xl text-primary',
                            ])
                            <span class="text-sm font-medium">Adicionar alternativa</span>
                        </button>
                        
                        <hr class="border-t-[3px] border-neutral-500">

                    </div>
                `;
                questionContainer.append(questionTemplate);
            });

            $(document).on('click', '.add-alternative', function() {
                const alternativesContainer = $(this).siblings('.alternatives-container');
                const question = $(this).closest('.question');
                const questionIndex = question.data('index')
                const alternativeIndex = alternativesContainer.find('.alternative').length + 1;

                const alternativeTemplate = `
                    <div class="space-y-6 alternative">

                        <div class="space-y-3">

                            <div class="flex items-center gap-3">

                                <div class="flex-1">
                                    <label 
                                        class="title question${questionIndex}" 
                                        for="alternative${alternativeIndex}"
                                    >
                                        Alternativa ${alternativeIndex}
                                    </label>
                                    <div class="flex items-center gap-3">

                                        <input
                                            placeholder="Digite aqui o título do conteúdo"
                                            type="text"
                                            name="questions[${questionIndex}][Options][${alternativeIndex}][text]"
                                        >

                                        <button
                                            class="remove-alternative animate flex h-12 w-12 items-center justify-center rounded-lg hover:bg-danger-50 hover:text-danger-800 active:bg-danger-500 active:text-white"
                                            type="button"
                                        >
                                            @include('components.icon', [
                                                'icon' => 'delete',
                                                'custom' => 'text-lg',
                                            ])
                                        </button>

                                    </div>
                                </div>

                            </div>

                            @component('components.toggle', [
                                'type' => 'radio',
                                'id' => 'setIsCorrectQuestionId${questionIndex}AlternativeId${alternativeIndex}',
                                'label' => 'Resposta correta?',
                                'name' => 'questions[${questionIndex}][Options][${alternativeIndex}][isCorrect]',
                                'customInput' => 'set-is-correct',
                                'contentEmpty' => true,
                            ])
                            @endcomponent

                        </div>
                        
                        <hr class="border-neutral-200">
                        
                    </div>
                `;
                alternativesContainer.append(alternativeTemplate);
            });

            $(document).on('click', '.set-is-correct', function() {
                const id = $(this).attr('id');
                const questionIndex = id.match(/QuestionId(\d+)/)[1];
                $(`input[id*="QuestionId${questionIndex}"]`).not(this).prop('checked', false);
            });

            // Função para remover a questão e suas alternativas
            $(document).on('click', '.remove-question', function() {
                if (confirm(
                        "Tem certeza que deseja remover esta questão Permanentemente e todas as suas alternativas?"
                    )) {
                    const question = $(this).closest('.question')
                    const questionIndex = question.data('index');
                    const input = $(`input[name="questions[${questionIndex}][quizId]"]`);

                    if (input.length > 0) {
                        let url =
                            "{{ route('dashboard.members.deleteQuiz', ['quizId' => 'PLACEHOLDER']) }}"
                            .replace('PLACEHOLDER', input.val());
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        });
                    }

                    $(`.question[data-index="${questionIndex}"]`).remove();
                    updateQuestionLabels();
                }
            });

            // Função para remover uma alternativa específica
            $(document).on('click', '.remove-alternative', function() {
                if (confirm("Tem certeza que deseja remover esta alternativa?")) {
                    var $container = $(this).closest('.alternatives-container');
                    $(this).closest('.alternative').remove();
                    updateAlternativeLabels($container);
                }
            });

            // Adicionar funcionalidade de drag and drop
            const questionContainer = $('.questions-container');

            // Adicionar funcionalidade de drag and drop para questões
            questionContainer.on({
                dragstart: function(e) {
                    $(this).addClass('dragging');
                    e.originalEvent.dataTransfer.setData('text/plain', $(this).index());
                },
                dragover: function(e) {
                    e.preventDefault();
                    const dragging = $('.dragging');
                    const afterElement = getDragAfterElement(questionContainer[0], e.clientY);
                    afterElement ? $(afterElement).before(dragging) : questionContainer.append(
                        dragging);
                },
                dragend: function() {
                    $(this).removeClass('dragging');
                    // Atualizar rótulos das questões após rearranjo
                    updateQuestionLabels();
                }
            }, '.question');

            // Adicionar funcionalidade de drag and drop para alternativas
            questionContainer.on({
                dragstart: function(e) {
                    $(this).addClass('draggingAlternative');
                    e.originalEvent.dataTransfer.setData('text/plain', $(this).index());
                },
                dragover: function(e) {
                    e.preventDefault();
                    const dragging = $('.draggingAlternative');
                    const afterElement = getAlternativeDragAfterElement($(this).closest(
                        '.alternatives-container')[0], e.clientY);
                    afterElement ? $(afterElement).before(dragging) : $(this).closest(
                        '.alternatives-container').append(dragging);
                },
                dragend: function() {
                    $(this).removeClass('draggingAlternative');
                    // Atualizar rótulos das questões após rearranjo
                    updateAlternativeLabels($(this).closest('.alternatives-container'))
                }
            }, '.alternative');

            // Função para encontrar o elemento da questão após o qual o elemento será inserido
            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.question:not(.dragging)')];
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    return offset < 0 && offset > closest.offset ? {
                        offset,
                        element: child
                    } : closest;
                }, {
                    offset: Number.NEGATIVE_INFINITY
                }).element;
            }

            function getAlternativeDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.alternative:not(.draggingAlternative)')];
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    return offset < 0 && offset > closest.offset ? {
                        offset,
                        element: child
                    } : closest;
                }, {
                    offset: Number.NEGATIVE_INFINITY
                }).element;
            }

            // Atualizar rótulos das questões
            function updateQuestionLabels() {
                $('.question').each((index, element) => {
                    $(element).find('.title').first().text(`Questão ${index + 1}`);
                });
            }

            function updateAlternativeLabels($container) {
                $container.find('.alternative').each((index, element) => {
                    $(element).find('.title').first().text(`Alternativa ${index + 1}`);
                });
            }

            const lesson = @json($lesson);

            function loadQuizData() {
                const quizzes = lesson.Quizzes || [];
                const questionContainer = $('.questions-container');

                quizzes.forEach((quiz, qIndex) => {
                    const questionIndex = qIndex + 1;


                    const questionTemplate = $(`
            <div class="question space-y-6" data-index="${questionIndex}">
                <div class="flex items-center gap-3 mb-5">
                    <input type="hidden" name="questions[${questionIndex}][quizId]" value="${quiz.id}">
                    <div class="flex-1">
                        <label class="title" for="question${questionIndex}">
                            Questão ${questionIndex}
                        </label>
                        <div class="flex items-center gap-3">
                            <input id="question${questionIndex}" type="text" name="questions[${questionIndex}][question]" value="${quiz.question}" />
                                                                    <button
                                            class="remove-question animate flex h-12 w-12 items-center justify-center rounded-lg hover:bg-danger-50 hover:text-danger-800 active:bg-danger-500 active:text-white"
                                            type="button"
                                        >
                                            @include('components.icon', [
                                                'icon' => 'delete',
                                                'custom' => 'text-lg',
                                            ])
                                        </button>
                        </div>
                    </div>
                </div>

                <hr class="border-neutral-200">
                <div class="space-y-6 alternatives-container"></div>

                <button
                            class="add-alternative mt-3 mb-3 animate flex w-full flex-col items-center justify-center rounded-2xl bg-neutral-50 py-6 hover:bg-neutral-100 active:bg-neutral-200"
                            type="button"
                        >
                            @include('components.icon', [
                                'icon' => 'add_circle',
                                'custom' => 'text-xl text-primary',
                            ])
                            <span class="text-sm font-medium">Adicionar alternativa</span>
                        </button>

                <hr class="border-t-[3px] border-neutral-500">
            </div>
        `);

                    // Adiciona alternativas
                    const alternativesContainer = questionTemplate.find('.alternatives-container');
                    quiz.Options.forEach((option, oIndex) => {
                        const alternativeIndex = oIndex + 1;
                        const isChecked = option.isCorrect ? 'checkedOption' : 'uncheckedOption';

                        const alternativeTemplate = $(`
                <div class="space-y-6 alternative">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="questions[${questionIndex}][Options][${alternativeIndex}][id]" value="${option.id}">
                            <div class="flex-1">
                                <label class="title question${questionIndex}" for="alternative${alternativeIndex}">
                                    Alternativa ${alternativeIndex}
                                </label>
                                <div class="flex items-center gap-3">
                                    <input placeholder="Digite aqui a alternativa" type="text" value="${option.text}" name="questions[${questionIndex}][Options][${alternativeIndex}][text]" />
                                                     <button
                                            class="remove-alternative animate flex h-12 w-12 items-center justify-center rounded-lg hover:bg-danger-50 hover:text-danger-800 active:bg-danger-500 active:text-white"
                                            type="button"
                                        >
                                            @include('components.icon', [
                                                'icon' => 'delete',
                                                'custom' => 'text-lg',
                                            ])
                                        </button>
                                </div>
                            </div>
                        </div>

                            @component('components.toggle', [
                                'type' => 'radio',
                                'id' => 'setIsCorrectQuestionId${questionIndex}AlternativeId${alternativeIndex}',
                                'label' => 'Resposta correta?',
                                'name' => 'questions[${questionIndex}][Options][${alternativeIndex}][isCorrect]',
                                'customInput' => 'set-is-correct ${isChecked}',
                                'contentEmpty' => true,
                            ])
                            @endcomponent
                    </div>
                    <hr class="border-neutral-200">
                </div>
            `);

                        alternativesContainer.append(alternativeTemplate);
                    });

                    questionContainer.append(questionTemplate);
                });
            }

            loadQuizData();

            document.querySelectorAll('input.checkedOption').forEach(input => {
                input.checked = true;
            });


            document.querySelectorAll('input.uncheckedOption').forEach(input => {
                input.checked = false;
            });
        });

        function submitForm(isDraft) {
            document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
            document.getElementById('formModulo').submit();
        }

        $(document).on('click', '.deleteRow', function(event) {
            event.preventDefault();

            let url = $(this).data('url');

            if (!confirm('Tem certeza que deseja remover esse anexo permanentemente?')) {
                return;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    window.location.reload();
                },
            });
        });
    </script>
@endpush
