<?php

class ValidaCargaHorariaService
{
    public function validaCargaHoraria($cargaHoraria)
    {
        if (empty($cargaHoraria)) {
            return ['valido' => true, 'mensagem' => ''];
        }

        // Remove espaços em branco
        $cargaHoraria = trim($cargaHoraria);

       
        if (!preg_match('/^(\d{1,2}):(\d{2})$/', $cargaHoraria, $matches)) {
            return [
                'valido' => false,
                'mensagem' => 'Carga horária inválida. Informe um valor no formato HH:MM.'
            ];
        }

        $horas   = (int) $matches[1];
        $minutos = (int) $matches[2];

        // Verifica se as horas são válidas (0-24)
        if ($horas > 24) {
            return [
                'valido' => false,
                'mensagem' => 'Carga horária inválida. As horas não podem ser maiores que 24.'
            ];
        }

        // Verifica se os minutos são válidos (0-59)
        if ($minutos > 59) {
            return [
                'valido' => false,
                'mensagem' => 'Carga horária inválida. Os minutos não podem ser maiores que 59.'
            ];
        }

        // Verifica caso especial: 24:XX só pode ser 00
        if ($horas === 24 && $minutos > 0) {
            return [
                'valido' => false,
                'mensagem' => 'Carga horária inválida. Se as horas forem 24, os minutos devem ser 00.'
            ];
        }

        return ['valido' => true, 'mensagem' => ''];
    }
}
