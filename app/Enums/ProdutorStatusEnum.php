<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ProdutorStatusEnum extends Enum implements LocalizedEnum
{    
    const Agendar = 'agendar';
    const Tentativa = 'tentativa';
    const Agendado = 'agendado';
    const Cadastro = 'cadastro';
    const Acompanhamento = 'acompanhamento';
    const Inativo = 'inativo';
}
