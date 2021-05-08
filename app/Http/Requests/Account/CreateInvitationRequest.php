<?php

namespace App\Http\Requests\Account;

use App\Models\Auth\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvitationRequest extends FormRequest
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
                Rule::unique('invitations')->where(function ($query) {
                    return $query->where('team_id', $this->team->id);
                }),
                function($attribute, $value, $fail) {
                    $user = User::where($attribute, $value)->first();

                    if (!is_null($user) && $this->team->hasMember($user)) {
                        $fail('The user is already a team member.');                        
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'An invitation already exists for this email.',
        ];
    }
}
