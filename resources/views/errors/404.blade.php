@extends('errors::minimal')

@section('title', $exception->getMessage() ?? __('Not Found'))
@section('code', 'Ops!')
@section('message', $exception->getMessage() ?? __('Not Found'))
