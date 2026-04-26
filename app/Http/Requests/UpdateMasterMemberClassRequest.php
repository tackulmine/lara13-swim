<?php

namespace App\Http\Requests;

use App\Models\MasterMemberClass;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UpdateMasterMemberClassRequest extends FormRequest
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
        $classTable = (new MasterMemberClass)->getTable();

        return [
            'name' => 'filled|unique:'.$classTable.',name,'.$this->id.',id|min:2|max:100',
            'users.*.id' => [
                'nullable',
                Rule::exists('user_member', 'user_id'),
            ],
        ];
    }

    public function attributes()
    {
        $attributes = [
            'name' => 'Nama Kelas',
            'users.*.id' => '',
        ];

        $usersData = $this->get('users', []);
        $userIds = Arr::pluck($usersData, 'id');

        $users = User::findMany($userIds);

        if ($users->isEmpty()) {
            return $attributes;
        }

        $attributes = collect($usersData)
            ->mapWithKeys(function ($userData, $index) {
                $memberIndex = $index + 1;
                // $userId = Arr::get($userData, 'id');
                // $user = $users->where('id', $userId)->first();

                // return [ "users.{$index}.id" => optional( $user )->name ];
                return ["users.{$index}.id" => "Atlit #{$memberIndex}"];
            })
            ->merge($attributes)
            ->toArray();

        return $attributes;
    }
}
