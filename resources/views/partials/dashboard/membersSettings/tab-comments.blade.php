<div class="tab-content hidden" id="tab-comments" data-tab="tab-comments">        
        @component('components.card', ['custom' => 'p-6 md:p-8'])
            <div class="space-y-8">
                <div class="space-y-4 md:space-y-6">
                    <h3>Comentários</h3>
                    <form action="{{ route('dashboard.members.addConfigComment', ['courseId' => $course['id']]) }}"
                        method="POST">
                        @csrf
                        <div class="flex flex-wrap gap-6">
                            @component('components.toggle', [
                                'type' => 'radio',
                                'id' => 'commentDeactive',
                                'label' => 'Desativado',
                                'name' => 'visibility',
                                'value' => 'deactivate',
                                'isChecked' => empty($comments),
                            ])
                            @endcomponent
                            @component('components.toggle', [
                                'type' => 'radio',
                                'id' => 'commentPublic',
                                'label' => 'Público',
                                'name' => 'visibility',
                                'value' => 'public',
                                'isChecked' => isset($comments['visibility']) && $comments['visibility'] === 'public',
                            ])
                            @endcomponent
                            @component('components.toggle', [
                                'type' => 'radio',
                                'id' => 'commentPrivate',
                                'label' => 'Privado',
                                'name' => 'visibility',
                                'value' => 'private',
                                'isChecked' => isset($comments['visibility']) && $comments['visibility'] === 'private',
                            ])
                            @endcomponent
                        </div>
                        <div class="flex justify-end mt-6">
                            <button class="button button-primary h-12 rounded-full" type="submit" onclick="submitForm(false)">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endcomponent
</div>