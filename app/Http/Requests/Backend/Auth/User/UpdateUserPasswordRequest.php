<?php

namespace App\Http\Requests\Backend\Auth\User;

use App\Models\Core\Rules\Auth\UnusedPassword;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Auth\PasswordRulesHelper;

/**
 * Class UpdateUserPasswordRequest.
 */
class UpdateUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => array_merge(
                [
                    new UnusedPassword((int) $this->segment(4)),
                ],
                PasswordRulesHelper::changePassword($this->email)
            ),
        ];
    }
}
