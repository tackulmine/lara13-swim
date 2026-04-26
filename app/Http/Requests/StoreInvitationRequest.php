<?php

namespace App\Http\Requests;

use App\Rules\CustomUniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                new CustomUniqueRule('users', 'email', __('This email address is already registered as a member. If you forget it please contact the administrator.')),
                new CustomUniqueRule('invitations', 'email', __('An requesting invitation has already been created for this email address. Please contact the administrator.')),
            ],
        ];
    }
}
