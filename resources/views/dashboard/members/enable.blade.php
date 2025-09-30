@extends('layouts.dashboard')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        @component('components.card', ['custom' => 'p-6 md:p-10'])
            <div class="prose max-w-full">
                <h3>Termo de Aceite - Área de Membros SuitSales</h3>
                <ul>
                    <li>Ao acessar e utilizar a área de membros da SuitSales, você declara estar ciente e de acordo com os termos abaixo:</li>
                    <li>Uso Responsável: Você se compromete a utilizar a área de membros exclusivamente para fins legítimos, respeitando as regras, diretrizes e políticas estabelecidas pela SuitSales.</li>
                    <li>Dados Pessoais: Ao utilizar esta área, você concorda com o tratamento de seus dados pessoais conforme descrito na nossa Política de Privacidade, com a finalidade de viabilizar o uso da plataforma, melhorar a experiência do usuário e oferecer conteúdos personalizados.</li>
                    <li>Confidencialidade: As informações e conteúdos disponibilizados na área de membros são de uso exclusivo dos usuários autorizados. É proibido o compartilhamento, reprodução ou uso indevido desses materiais sem autorização prévia da SuitSales.</li>
                    <li>Segurança da Conta: Você é responsável por manter a confidencialidade de suas credenciais de acesso e por todas as atividades realizadas em sua conta. Notifique imediatamente a SuitSales em caso de uso não autorizado.</li>
                    <li>Suspensão ou Cancelamento de Acesso: A SuitSales reserva-se o direito de suspender ou cancelar o acesso à área de membros, a seu exclusivo critério, em caso de violação destes termos ou uso indevido da plataforma.</li>
                </ul>
                <p>Ao clicar em "Aceito", você confirma que leu, entendeu e concorda com todos os termos acima.</p>
            </div>
        @endcomponent

        <form
            action="{{ route('dashboard.members.enable') }}"
            method="POST"
        >
            @csrf
            <button
                class="button button-primary ml-auto h-12 rounded-full"
                data-modal-target="modalAddProduct"
                data-modal-toggle="modalAddProduct"
                type="submit"
            >
                Habilitar Suit Members
            </button>
        </form>

    </div>
@endsection
