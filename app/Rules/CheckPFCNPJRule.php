<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPFCNPJRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->init($value);

        if (!$this->valida()) {
            $fail('Documento inválido.');
        }
    }

    function init( $valor = null ) {
        $this->valor = preg_replace( '/[^0-9]/', '', $valor );

        // Garante que o valor é uma string
        $this->valor = (string)$this->valor;
    }

    protected function verifica_cpf_cnpj (): false|string
    {
        if ( strlen( $this->valor ) === 11 ) {
            return 'CPF';
        } elseif ( strlen( $this->valor ) === 14 ) {
            return 'CNPJ';
        } else {
            return false;
        }
    }

    protected function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
        for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
            $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
            $posicoes--;

            if ( $posicoes < 2 ) {
                $posicoes = 9;
            }
        }

        $soma_digitos = $soma_digitos % 11;

        if ( $soma_digitos < 2 ) {
            $soma_digitos = 0;
        } else {
            $soma_digitos = 11 - $soma_digitos;
        }

        $cpf = $digitos . $soma_digitos;

        return $cpf;
    }

    protected function valida_cpf() {
        $digitos = substr($this->valor, 0, 9);

        $novo_cpf = $this->calc_digitos_posicoes( $digitos );

        $novo_cpf = $this->calc_digitos_posicoes( $novo_cpf, 11 );

        if ( $novo_cpf === $this->valor ) {
            return true;
        } else {
            return false;
        }
    }

    protected function valida_cnpj () {
        $cnpj_original = $this->valor;

        $primeiros_numeros_cnpj = substr( $this->valor, 0, 12 );
        $primeiro_calculo = $this->calc_digitos_posicoes( $primeiros_numeros_cnpj, 5 );
        $segundo_calculo = $this->calc_digitos_posicoes( $primeiro_calculo, 6 );
        $cnpj = $segundo_calculo;
        if ( $cnpj === $cnpj_original ) {
            return true;
        }
    }

    public function valida () {
        // Valida CPF
        if ( $this->verifica_cpf_cnpj() === 'CPF' ) {
            // Retorna true para cpf válido
            return $this->valida_cpf() && $this->verifica_sequencia(11);
        }
        // Valida CNPJ
        elseif ( $this->verifica_cpf_cnpj() === 'CNPJ' ) {
            // Retorna true para CNPJ válido
            return $this->valida_cnpj() && $this->verifica_sequencia(14);
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    public function verifica_sequencia($multiplos)
    {
        // cpf
        for($i=0; $i<10; $i++) {
            if (str_repeat($i, $multiplos) == $this->valor) {
                return false;
            }
        }

        return true;
    }
}
