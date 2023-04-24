<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Core\ProdutorModel;
use App\Models\Core\UnidadeProdutivaModel;

class NameExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct( $model )
    {
        $this->model = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        
        if( $this->model == 'Produtor' ){
            $search = ProdutorModel::where($attribute, $value)->get();
        } elseif( $this->model == 'UnidadeProdutiva' ){
            $search = UnidadeProdutivaModel::where($attribute, $value)->get();
        }        
        return count($search) > 0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if( $this->model == 'Produtor' ){
            return __('concepts.produtora.name_exists');
        } elseif( $this->model == 'UnidadeProdutiva' ){
            return __('concepts.unidade_produtiva.name_exists');
        }        
    }
}
