<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class SituacaoInfraFerramentaEnum extends Enum implements LocalizedEnum
{
    const BomEstado = 'Bom estado';
    const Mediano = 'Mediano';
    const Desgastada = 'Desgastada';    
}
?>