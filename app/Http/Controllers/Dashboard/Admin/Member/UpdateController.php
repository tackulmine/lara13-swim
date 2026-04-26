<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Models\MasterMemberType;
use App\Models\MasterSchool;
use App\Models\MasterUserType;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserMember;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateController extends BaseController
{
    public function __invoke(Request $request, User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $member->id != auth()->id()) {
            abort(403);
        }

        $userTable = (new User)->getTable();
        $userProfileTable = (new UserProfile)->getTable();
        $userMemberTable = (new UserMember)->getTable();
        $memberTypeTable = (new MasterMemberType)->getTable();
        $masterSchoolTable = (new MasterSchool)->getTable();
        $rules = [
            'name' => 'filled|string|min:2|max:100',
            'username' => 'filled|unique:'.$userTable.',username,'.$member->id.',id|alpha_dash|min:2|max:50',
            'email' => 'filled|email|unique:'.$userTable.',email,'.$member->id.',id|max:100',
            'master_member_type_id' => 'filled|exists:'.$memberTypeTable.',id',
            'gender' => 'filled|in:male,female',
            'relegion' => 'filled|in:'.implode(',', array_keys($this->relegionOptions)),
            'last_education' => 'filled|in:'.implode(',', array_keys($this->educationOptions)),
            'birth_place' => 'filled|string|max:100',
            'birth_date' => 'filled|date_format:d/M/Y',
            // 'master_school' => 'required|exists:' . $masterSchoolTable . ',name',
        ];
        if ($request->filled('nik')) {
            $rules = [
                'nik' => 'filled|unique:'.$userProfileTable.',nik,'.$member->id.',user_id|size:16',
            ] + $rules;
        }
        if ($request->filled('nis')) {
            $rules = [
                'nis' => 'required|unique:'.$userMemberTable.',nis,'.$member->id.',user_id|max:50',
            ] + $rules;
        }
        if ($request->filled('password')) {
            $rules = [
                'password' => [
                    'required',
                    'min:8',
                    'max:20',
                    'alpha_num',
                    // 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                    'confirmed',
                ],
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
        // $validatedData['updated_by'] = auth()->id();
        $validatedData['birth_date'] = $request->birth_date ? Carbon::createFromFormat('d/M/Y', $request->birth_date) : null;

        $data = $validatedData + $request->all();
        $dataColl = collect($data);
        $dataColl->put('username', Str::slug($request->username));
        $dataColl->put('master_user_type_id', MasterUserType::MEMBER_ID);
        $dataColl->put('point', parsePointToInt($request->input('point_text')));

        DB::beginTransaction();
        try {
            // code...
            // IF FAILED
            if (! $member->update($dataColl->only('name', 'username', 'email')->all())) {
                DB::rollback();

                return back()
                    ->withInput()
                    ->withErrors(["{$this->moduleName} '{$member->name}' GAGAL diupdate!"]);
            }

            // update password if not empty
            if ($request->filled('password')) {
                $member->update([
                    'password' => $request->password,
                ]);
            }

            // init user profile
            $userProfileAttributes = $dataColl->except('name', '_token', '_method', 'action')->all();

            // update photo
            if ($request->hasFile('photo')) {
                // inject data attributes to update photo
                $userProfileAttributes['photo'] = uploadAvatar($request->photo, $member->id);
            }
            // update other files
            // if ($request->hasFile('school_certificate')) {
            //     $userProfileAttributes['school_certificate'] = uploadMemberFile($request->school_certificate, 'school_certificate-' . $member->id);
            // }
            if ($request->hasFile('birth_certificate')) {
                $userProfileAttributes['birth_certificate'] = uploadMemberFile($request->birth_certificate, 'birth_certificate-'.$member->id);
            }
            if ($request->hasFile('family_card')) {
                $userProfileAttributes['family_card'] = uploadMemberFile($request->family_card, 'family_card-'.$member->id);
            }
            if ($request->hasFile('kta_card')) {
                $userProfileAttributes['kta_card'] = uploadMemberFile($request->kta_card, 'kta_card-'.$member->id);
            }

            // update profile
            UserProfile::updateOrCreate(
                ['user_id' => $member->id],
                $userProfileAttributes
            );

            // update member position
            UserMember::updateOrCreate(
                ['user_id' => $member->id],
                $dataColl->only('master_member_type_id', 'nis')->all()
            );

            // update member education
            if ($request->filled('master_school')) {
                $masterSchool = MasterSchool::firstOrCreate([
                    'name' => $request->master_school,
                ]);

                UserEducation::updateOrCreate(
                    ['user_id' => $member->id],
                    ['master_school_id' => $masterSchool->id],
                );
            } elseif ($member->educations->isNotEmpty()) {
                $member->educations()->delete();
            }

            DB::commit();

            if ($request->action === 'continue') {
                return back()
                    ->withSuccess("{$this->moduleName} '{$member->name}' telah diupdate.");
            }

            return redirect()
                ->route($this->baseRouteName.'index', getQueryParams())
                ->withSuccess("{$this->moduleName} '{$member->name}' telah diupdate.");
        } catch (\Throwable $th) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([$th->getMessage()]);
        }
    }
}
