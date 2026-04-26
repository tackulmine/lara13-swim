<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\MasterMemberType;
use App\Models\MasterSchool;
use App\Models\MasterUserType;
use App\Models\User;
use App\Models\UserMember;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends BaseController
{
    public function __invoke(Request $request)
    {
        $userTable = (new User)->getTable();
        $userProfileTable = (new UserProfile)->getTable();
        $userMemberTable = (new UserMember)->getTable();
        $memberTypeTable = (new MasterMemberType)->getTable();
        // $masterSchoolTable = (new MasterSchool)->getTable();
        $rules = [
            'name' => 'required|string|min:2|max:100',
            'username' => 'required|unique:'.$userTable.'|alpha_dash|min:2|max:50',
            'email' => 'required|email|unique:'.$userTable.'|max:100',
            'password' => [
                'required',
                'min:8',
                'max:20',
                'alpha_num',
                // 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed',
            ],
            'master_member_type_id' => 'required|exists:'.$memberTypeTable.',id',
            'gender' => 'required|in:male,female',
            'relegion' => 'required|in:'.implode(',', array_keys($this->relegionOptions)),
            'last_education' => 'required|in:'.implode(',', array_keys($this->educationOptions)),
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date_format:d/M/Y',
            // 'master_school' => 'required|exists:' . $masterSchoolTable . ',name',
        ];
        if ($request->filled('nik')) {
            $rules = [
                'nik' => 'required|unique:'.$userProfileTable.',nik|size:16',
            ] + $rules;
        }
        if ($request->filled('nis')) {
            $rules = [
                'nis' => 'required|unique:'.$userMemberTable.',nis|max:50',
            ] + $rules;
        }
        if ($request->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=300,min_height=400',
                ],
            ] + $rules;
        }
        if ($request->hasFile('birth_certificate')) {
            $rules = [
                'birth_certificate' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=600,min_height=800',
                ],
            ] + $rules;
        }
        if ($request->hasFile('family_card')) {
            $rules = [
                'family_card' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=800,min_height=600',
                ],
            ] + $rules;
        }
        if ($request->hasFile('kta_card')) {
            $rules = [
                'kta_card' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=400,min_height=300',
                ],
            ] + $rules;
        }

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        $validatedData['birth_date'] = $request->birth_date ? Carbon::createFromFormat('d/M/Y', $request->birth_date) : null;

        $data = array_filter($validatedData + $request->all());
        $dataColl = collect($data);
        // $dataColl->put('username', Str::slug($request->email));
        // $dataColl->put('username', Str::slug($request->username));
        // $dataColl->put('email', Str::slug($request->username) . '@centrumsc.com');
        // $dataColl->put('password', Str::slug($request->username) . '123');
        $dataColl->put('master_user_type_id', MasterUserType::MEMBER_ID);
        $dataColl->put('point', parsePointToInt($request->input('point_text')));

        DB::beginTransaction();
        try {
            $member = User::create($dataColl->only('name', 'username', 'email', 'password', 'master_user_type_id')->all());
            if (! $member) {
                DB::rollback();

                return back()
                    ->withInput()
                    ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
            }

            // init user profile
            $userProfileAttributes = $dataColl->except('name', 'username', 'email', 'password', 'master_user_type_id', '_token', '_method', 'action')
                ->all();

            // update photo
            if ($request->hasFile('photo')) {
                // inject data attributes to update photo
                $userProfileAttributes['photo'] = uploadAvatar($request->photo, $member->id);
            }
            // update other files
            if ($request->hasFile('birth_certificate')) {
                $userProfileAttributes['birth_certificate'] = uploadMemberFile($request->birth_certificate, 'birth_certificate-'.$member->id);
            }
            if ($request->hasFile('family_card')) {
                $userProfileAttributes['family_card'] = uploadMemberFile($request->family_card, 'family_card-'.$member->id);
            }
            if ($request->hasFile('kta_card')) {
                $userProfileAttributes['kta_card'] = uploadMemberFile($request->kta_card, 'kta_card-'.$member->id);
            }

            // create new profile
            $member->profile()->create($userProfileAttributes);

            // create new member & position
            $member->userMember()->create($dataColl->only('master_member_type_id', 'nis')->all());

            // create or using default school/tim
            $masterSchool = MasterSchool::firstOrCreate([
                'name' => $request->master_school ?? 'CENTRUM SC',
            ]);

            $member->educations()->create([
                'master_school_id' => $masterSchool->id,
            ]);

            DB::commit();

            return redirect()
                ->route($this->baseRouteName.'index')
                ->withSuccess("{$this->moduleName} '{$member->name}' telah disimpan!");
        } catch (\Throwable $th) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([$th->getMessage()]);
        }
    }
}
