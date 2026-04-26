<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
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

class MemberController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    protected $relegionOptions;

    protected $genderOptions;

    protected $educationOptions;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Atlet');
        $this->baseRouteName = 'dashboard.admin.member.';
        $this->baseViewPath = 'dashboard.admin.member.';
        $this->genderOptions = getGenders();
        $this->relegionOptions = getRelegions();
        $this->educationOptions = getEducations();

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Nama',
            'username' => 'Username/Panggilan',
            'email' => 'Email',
            'password' => 'Password',
            'gender' => __('Gender'),
            'relegion' => 'Agama',
            'last_education' => 'Pendidikan Terakhir',
            'address' => 'Alamat',
            'location' => 'Kec, Kab/Kota',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'height' => 'Tinggi badan',
            'weight' => 'Berat badan',
            'master_school' => __('Sekolah'),
            'master_member_type_id' => 'Status Atlit',
            'birth_certificate' => 'Akte Kelahiran',
            'family_card' => 'KK (Kartu Keluarga)',
            'kta_card' => 'KTA (Kartu Tanda Anggota)',
        ];
    }

    protected function generateOptions()
    {
        $this->globalData = [
            'masterMemberTypeOptions' => MasterMemberType::orderBy('name')->pluck('name', 'id'),
            'masterSchoolOptions' => MasterSchool::orderBy('name')->pluck('name', 'name')->prepend('---', ''),
            'relegionOptions' => $this->relegionOptions,
            'genderOptions' => $this->genderOptions,
            'educationOptions' => $this->educationOptions,
        ] + $this->globalData;
    }

    public function index(Request $request)
    {
        // $members = User::has('userMember')
        $members = User::whereHas('userType', function ($q) {
            $q->where('master_user_type_id', MasterUserType::MEMBER_ID);
        })
            ->with('profile', 'userMember', 'userMember.type', 'userMember.class', 'educations', 'educations.school')
            ->where('id', '<>', 1)
            ->orderBy('username')
            ->when(! auth()->user()->isSuperuser() && auth()->user()->isMember(), function ($q) {
                $q->where('id', auth()->id());
            })
            ->when($request->filled('trashed'), function ($q) {
                $q->onlyTrashed();
            })
            ->get();

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName} ".($request->filled('trashed') ? 'Non Aktif' : 'Aktif'),
            'members' => $members,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(User $member)
    {
        $this->generateOptions();

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $formFields = new FormFields($member);
        $formFields = $formFields->generateForm();

        foreach ((new UserProfile)->getFillable() as $key) {
            $formFields->$key = old($key);
        }
        // $formFields->birth_date = Carbon::createFromFormat('d/M/Y', old('birth_date', now()->subYears(3)->format('d/M/Y')));
        $formFields->photo_url = $member->photo_url;
        $formFields->preview_photo = $member->preview_photo;
        $formFields->signature_url = $member->signature_url;

        foreach ((new UserMember)->getFillable() as $key) {
            $formFields->$key = old($key);
        }

        foreach ((new UserEducation)->getFillable() as $key) {
            $formFields->$key = old($key);
        }
        $formFields->master_school = old('master_school');

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'member' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function store(Request $request)
    {
        $userTable = (new User)->getTable();
        $userProfileTable = (new UserProfile)->getTable();
        $userMemberTable = (new UserMember)->getTable();
        $memberTypeTable = (new MasterMemberType)->getTable();
        // $masterSchoolTable = (new MasterSchool)->getTable();
        $rules = [
            'name' => 'required|unique:'.$userTable.'|min:2|max:100',
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

    public function show(Request $request, User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $member->id != auth()->id()) {
            return redirect()->route('dashboard.admin.member.show', auth()->id());
        }

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $this->globalData = [
            'pageTitle' => "Detail {$this->moduleName}",
            'member' => $member,
            'id' => $member->id,
        ] + $this->globalData;

        if ($request->filled('print')) {
            $this->globalData['coach'] = User::staff()
                ->whereHas('userStaff')
                ->orderBy('id')
                ->firstOrFail();

            $this->globalData['pageTitle'] = "Detail {$this->moduleName} - {$member->name}".($member->userMember ? ' - '.optional($member->userMember)->nis : '');

            return view($this->baseViewPath.'print', $this->globalData);
        }

        return view($this->baseViewPath.'show', $this->globalData);
    }

    public function print(Request $request, User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $member->id != auth()->id()) {
            return redirect()->route('dashboard.admin.member.print', auth()->id());
        }

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $this->globalData = [
            'pageTitle' => "Detail {$this->moduleName} - {$member->name}".($member->userMember ? ' - '.optional($member->userMember)->nis : ''),
            'member' => $member,
            'id' => $member->id,
        ] + $this->globalData;

        $this->globalData['coach'] = User::staff()
            ->whereHas('userStaff')
            ->orderBy('id')
            ->firstOrFail();

        return view($this->baseViewPath.'print', $this->globalData);
    }

    public function edit(User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember() && $member->id != auth()->id()) {
            return redirect()->route('dashboard.admin.member.edit', auth()->id());
        }

        $this->generateOptions();

        $member->load('profile', 'userMember', 'userMember.type', 'educations', 'educations.school');

        $formFields = new FormFields($member);
        $formFields = $formFields->generateForm();

        $userProfile = optional($member->profile);
        foreach ((new UserProfile)->getFillable() as $key) {
            $formFields->$key = old($key, $userProfile->$key);
        }
        // $formFields->birth_date = Carbon::createFromFormat('d/M/Y', old('birth_date', optional($userProfile->birth_date)->format('d/M/Y') ?? now()->subYears(3)->format('d/M/Y')));
        $formFields->birth_date = old('birth_date', (
            $userProfile->birth_date
            ? Carbon::createFromFormat('d/M/Y', optional($userProfile->birth_date)->format('d/M/Y'))
            : null));
        $formFields->photo_url = $member->photo_url;
        $formFields->preview_photo = $member->preview_photo;
        $formFields->preview_birth_certificate = $member->preview_birth_certificate;
        $formFields->preview_family_card = $member->preview_family_card;
        $formFields->preview_kta_card = $member->preview_kta_card;
        $formFields->signature_url = $member->signature_url;
        $formFields->preview_signature = $member->preview_signature;

        $userMember = optional($member->userMember);
        foreach ((new UserMember)->getFillable() as $key) {
            $formFields->$key = old($key, $userMember->$key);
        }

        $userEducation = optional(optional($member->educations)->first());
        foreach ((new UserEducation)->getFillable() as $key) {
            $formFields->$key = old($key, $userEducation->$key);
        }
        $formFields->master_school = old('master_school', optional($userEducation->school)->name);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$member->name}",
            'member' => $formFields,
            'id' => $member->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function update(Request $request, User $member)
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
            'name' => 'filled|unique:'.$userTable.',name,'.$member->id.',id|min:2|max:100',
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

    public function destroy(Request $request, User $member)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember()) {
            abort(403);
        }

        if (! $member->delete()) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dihapus!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dihapus!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dihapus."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dihapus.");
    }

    public function destroyBatch(Request $request)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember()) {
            abort(403);
        }

        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $batchDestroy = false;
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                optional(User::find($id))->delete();
            }

            DB::commit();
            $batchDestroy = true;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }

        if (! $batchDestroy) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL dinon-aktifkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL dinon-aktifkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah dinon-aktifkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah dinon-aktifkan.");
    }

    public function restoreBatch(Request $request)
    {
        if (! auth()->user()->isSuperuser() && auth()->user()->isMember()) {
            abort(403);
        }

        if (! $request->filled('ids')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Silakan pilih item terlebih dahulu!'], 404);
            }

            return back()
                ->withErrors(['Silakan pilih item terlebih dahulu!']);
        }

        $batchRestore = false;
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id) {
                optional(User::onlyTrashed()->find($id))->restore();
            }

            DB::commit();
            $batchRestore = true;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }

        if (! $batchRestore) {
            if ($request->ajax()) {
                return response()->json(['error' => "{$this->moduleName} GAGAL diaktifkan!"], 404);
            }

            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} GAGAL diaktifkan!"]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => "{$this->moduleName} telah diaktifkan."], 200);
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} telah diaktifkan.");
    }
}
