<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FrequenciaComercializacaoEnum extends Enum implements LocalizedEnum
{
    const Diario = 'Diário';
    const Semanal = 'Semanal';
    const Mensal = 'Mensal';
    const Anual = 'Anual';
    const Esporadico = 'Esporádico';
}
?>