@extends('layouts.email')
@section('content')
<h2
    style="
    margin: 0;
    line-height: 29px;
    mso-line-height-rule: exactly;
    font-family: Poppins,
    sans-serif;
    font-size: 18px;
    font-style: normal;
    font-weight: normal;
    color: #3d405b;
    ">
    Você foi convidado para ser um
    colaborador do evento <br />
    <strong>{{ $product->name }}</strong>. <br /><br />
    Clique no botão abaixo para
    fazer seu login ou cadastro e
    começar a divulgar o evento.
</h2>
@endsection
@section('action')
<a href="{{ route('register', ['collaborator' => encrypt($collaborator->id)]) }}" class="es-button" target="_blank"
    style="
    mso-style-priority: 100 !important;
    text-decoration: none;
    -webkit-text-size-adjust: none;
    -ms-text-size-adjust: none;
    mso-line-height-rule: exactly;
    color: #ffffff;
    font-size: 18px;
    padding: 15px 35px 15px 35px;
    display: inline-block;
    background: #6e17b0;
    border-radius: 10px;
    font-family: Poppins,
    sans-serif;
    font-weight: bold;
    font-style: normal;
    line-height: 22px;
    width: auto;
    text-align: center;
    ">Aceitar
convite</a>
@endsection
