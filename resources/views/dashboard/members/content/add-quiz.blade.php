@extends('layouts.members')

@section('content')
    <form id="formModulo" class="mt-4 mb-4" action="{{ route('dashboard.members.createQuiz') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        {{ csrf_field() }}
        <div class="space-y-10">
            <h3>Adicionar conteúdo: Quiz</h3>
            <input type="hidden" name="moduleId" value="{{ $moduleId }}">
            <input type="hidden" name="courseId" value="{{ $course['id'] }}">
            <input type="hidden" name="draft" id="inputDraft" value="false">
            <div class="space-y-8">

                <div class="rounded-2xl bg-white">

                    <div class="p-6">
                        <h5 class="font-medium">Detalhes do conteúdo</h5>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12">
                                <label class="mb-1">Nome do quiz <span class="text-danger-400">*</span></label>
                                <input value="{{ old('name') }}" class="mt-2" type="text" id="name"
                                    name="name" placeholder="Digite o nome da sua aula" required maxlength="245" />
                            </div>

                            <div class="col-span-12">
                                <label id="name" class="mb-1">Descrição do quiz (Opcional)</label>
                                <textarea rows="6" id="description" name="description" maxlength="5000"
                                    placeholder="Explique o seu quiz em no máximo 5000 characters" oninput="setCharacterLimit(this)"></textarea>
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

@push('style')
    <style>
        .question.dragging {
            opacity: 0.5;
            padding: 16px;
            border: 2px dashed #ccc;
            background-color: #f9f9f9;
        }

        .alternative.draggingAlternative {
            opacity: 0.5;
            padding: 16px;
            border: 2px dashed #ccc;
            background-color: #f9f9f9;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-question', function() {
                const questionIndex = $('.question').length + 1;
                const questionContainer = $('.questions-container');
                const questionTemplate = `
                    <div class="question space-y-6" draggable="true">
                        
                        <div class="flex items-center gap-3">
                            
                            @include('components.icon', [
                                'icon' => 'drag_indicator',
                                'custom' => 'text-lg cursor-move',
                            ])
                            
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
                                        required
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
                            class="add-alternative animate flex w-full flex-col items-center justify-center rounded-2xl bg-neutral-50 py-6 hover:bg-neutral-100 active:bg-neutral-200"
                            type="button"
                            data-question-id="${questionIndex}"
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
                const questionIndex = $(this).data('question-id');
                const alternativesContainer = $(this).siblings('.alternatives-container');
                const alternativeIndex = alternativesContainer.find('.alternative').length + 1;
                const alternativeTemplate = `
                    <div class="space-y-6 alternative" draggable="true">

                        <div class="space-y-3">

                            <div class="flex items-center gap-3">

                                @include('components.icon', [
                                    'icon' => 'drag_indicator',
                                    'custom' => 'text-lg cursor-move',
                                ])

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
                                            required
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
                if (confirm("Tem certeza que deseja remover esta questão e todas as suas alternativas?")) {
                    $(this).closest('.question').remove();
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
        });

        function submitForm(isDraft) {
            event.preventDefault();

            let errors = [];
            const name = document.getElementById('name');
            const questions = document.querySelectorAll('.question');

            if (!name.value.trim()) {
                errors.push("O nome do quiz é obrigatório.");
            }

            for (const question of questions) {
                const questionInput = question.querySelector('input[name*="[question]"]');
                if (!questionInput || !questionInput.value.trim()) {
                    errors.push("Todas as questões devem ter o texto preenchido.");
                    questionInput?.focus();
                }

                const alternatives = question.querySelectorAll('.alternative input[name*="[text]"]');
                let hasCorrect = false;
                let allFilled = true;

                for (const alt of alternatives) {
                    if (!alt.value || !alt.value.trim()) {
                        allFilled = false;
                    }

                    const correct = alt.closest('.alternative').querySelector('input[type="radio"]');
                    if (correct && correct.checked) {
                        hasCorrect = true;
                    }
                }

                if (!allFilled) {
                    errors.push("Cada questão deve ter todas as alternativas preenchidas.");
                }

                if (!hasCorrect) {
                    errors.push("Cada questão deve ter uma alternativa marcada como correta.");
                }
            }


            if (errors.length > 0) {
                errors.forEach(error => {
                    notyf.error(error);
                });
                return
            }

            document.getElementById('inputDraft').value = isDraft ? 'true' : 'false';
            document.getElementById('formModulo').submit();
        }
    </script>
@endpush
