<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Libraries\FormFields;
use App\Models\MasterStaffType;
use App\Models\MasterUserType;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserStaff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffController extends BaseController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Staff';
        $this->baseRouteName = 'dashboard.admin.staff.';
        $this->baseViewPath = 'dashboard.admin.staff.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Nama',
            'email' => 'Email',
            'gender' => __('Gender'),
            'password' => 'Password',
            'address' => 'Alamat',
            'location' => 'Kec, Kab/Kota',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
        ];
    }

    protected function generateOptions()
    {
        $this->globalData = [
            'masterStaffTypeOptions' => MasterStaffType::pluck('name', 'id')->prepend('---', ''),
        ] + $this->globalData;
    }

    public function index()
    {
        $staffs = User::has('userStaff')
            ->with('profile', 'userStaff', 'userStaff.type')
            ->where('id', '<>', 1)
            ->orderBy('name')
            ->get();

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'staffs' => $staffs,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(User $staff)
    {
        $this->generateOptions();

        $staff->load('profile', 'userStaff', 'userStaff.type');

        $formFields = new FormFields($staff);
        $formFields = $formFields->generateForm();

        $profile = (new UserProfile)->getFillable();
        foreach ($profile as $key) {
            $formFields->$key = old($key);
        }
        $formFields->birth_date = Carbon::createFromFormat('d/M/Y', old('birth_date', now()->subYears(3)->format('d/M/Y')));
        $formFields->photo_url = $staff->photo_url;
        $formFields->preview_photo = $staff->preview_photo;

        $userStaff = (new UserStaff)->getFillable();
        foreach ($userStaff as $key) {
            $formFields->$key = old($key);
        }
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'staff' => $formFields,
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
        $staffTypeTable = (new MasterStaffType)->getTable();
        $rules = [
            'name' => 'required|unique:'.$userTable.'|min:2|max:100',
            'username' => 'required|unique:'.$userTable.'|max:20',
            'email' => 'required|email|unique:'.$userTable.'|max:100',
            'password' => [
                'required',
                'min:6',
                'max:20',
                'alpha_num',
                // 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed',
            ],
            'master_staff_type_id' => 'required|exists:'.$staffTypeTable.',id',
        ];
        if ($request->filled('nik')) {
            $rules = [
                'nik' => 'required|unique:'.$userProfileTable.',nik|size:16',
            ] + $rules;
        }
        if ($request->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'mimes:jpeg,jpg,png,gif',
                    'max:2048', // 2MB
                ],
            ] + $rules;
        }

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        // $validatedData['created_by'] = auth()->id();
        $validatedData['birth_date'] = $request->birth_date ? Carbon::createFromFormat('d/M/Y', $request->birth_date) : null;

        $data = array_filter($validatedData + $request->all());
        $dataColl = collect($data);
        $dataColl->put('username', Str::slug($request->username));
        $dataColl->put('master_user_type_id', MasterUserType::STAFF_ID);
        // dd($dataColl);

        $staff = User::create($dataColl->only('name', 'username', 'email', 'password', 'master_user_type_id')->all());
        if (! $staff) {
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
            $userProfileAttributes['photo'] = uploadAvatar($request->photo, $staff->id);
        }

        // create new profile
        $staff->profile()->create($userProfileAttributes);

        // create new staff & position
        $staff->userStaff()->create($dataColl->only('master_staff_type_id')->all());

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '{$staff->name}' telah disimpan!");
    }

    public function edit(User $staff)
    {
        $this->generateOptions();

        $staff->load('profile', 'userStaff', 'userStaff.type');

        $formFields = new FormFields($staff);
        $formFields = $formFields->generateForm();

        $userProfile = optional($staff->profile);
        // dd((new UserProfile)->getFillable());
        foreach ((new UserProfile)->getFillable() as $key) {
            $formFields->$key = old($key, $userProfile->$key);
        }
        $formFields->birth_date = Carbon::createFromFormat('d/M/Y', old('birth_date', optional($userProfile->birth_date)->format('d/M/Y') ?? now()->subYears(3)->format('d/M/Y')));

        $formFields->photo_url = $staff->photo_url;
        $formFields->preview_photo = $staff->preview_photo;

        $userStaff = optional($staff->userStaff);
        foreach ((new UserStaff)->getFillable() as $key) {
            $formFields->$key = old($key, $userStaff->$key);
        }
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$staff->name}",
            'staff' => $formFields,
            'id' => $staff->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function update(Request $request, User $staff)
    {
        $userTable = (new User)->getTable();
        $userProfileTable = (new UserProfile)->getTable();
        $staffTypeTable = (new MasterStaffType)->getTable();
        $rules = [
            'name' => 'filled|unique:'.$userTable.',name,'.$staff->id.',id|min:2|max:100',
            'username' => 'filled|unique:'.$userTable.',username,'.$staff->id.',id|max:20',
            'email' => 'filled|email|unique:'.$userTable.',email,'.$staff->id.',id|max:100',
            'master_staff_type_id' => 'required|exists:'.$staffTypeTable.',id',
        ];
        if ($request->filled('nik')) {
            $rules = [
                'nik' => 'filled|unique:'.$userProfileTable.',nik,'.$staff->id.',user_id|size:16',
            ] + $rules;
        }
        if ($request->filled('password')) {
            $rules = [
                'password' => [
                    'required',
                    'min:6',
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
                    'mimes:jpeg,jpg,png,gif',
                    'max:2048', // 2MB
                ],
            ] + $rules;
        }

        $validatedData = $request->validate($rules, $this->customMessages, $this->customAttributes);
        // $validatedData['updated_by'] = auth()->id();
        $validatedData['birth_date'] = $request->birth_date ? Carbon::createFromFormat('d/M/Y', $request->birth_date) : null;

        $data = $validatedData + $request->all();
        $dataColl = collect($data);
        $dataColl->put('master_user_type_id', MasterUserType::STAFF_ID);

        // IF FAILED
        if (! $staff->update($dataColl->only('name', 'username', 'email')->all())) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$staff->name}' GAGAL diupdate!"]);
        }

        // update password if not empty
        if ($request->filled('password')) {
            $staff->update([
                'password' => $request->password,
            ]);
        }

        // init user profile
        $userProfileAttributes = $dataColl->except('name', '_token', '_method', 'action')->all();

        // update photo
        if ($request->hasFile('photo')) {
            // inject data attributes to update photo
            $userProfileAttributes['photo'] = uploadAvatar($request->photo, $staff->id);
        }

        // update profile
        UserProfile::updateOrCreate(
            ['user_id' => $staff->id],
            $userProfileAttributes
        );

        // update staff position
        UserStaff::updateOrCreate(
            ['user_id' => $staff->id],
            ['master_staff_type_id' => $request->master_staff_type_id],
        );

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$staff->name}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '{$staff->name}' telah diupdate.");
    }

    public function destroy(Request $request, User $staff)
    {
        if (! $staff->delete()) {
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
}
