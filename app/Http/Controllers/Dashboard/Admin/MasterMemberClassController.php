<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Requests\StoreMasterMemberClassRequest;
use App\Http\Requests\UpdateMasterMemberClassRequest;
use App\Libraries\FormFields;
use App\Models\MasterMemberClass;
use App\Models\User;
use App\Models\UserMember;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MasterMemberClassController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Kelas';
        $this->baseRouteName = 'dashboard.admin.class.';
        $this->baseViewPath = 'dashboard.admin.class.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }

    protected function generateOptions()
    {
        $members = User::member()->orderBy('username')->get(['id', 'username', 'name']);
        $memberOptions = [];
        foreach ($members as $member) {
            // $memberOptions[$member->id] = "$member->name ($member->username)";
            $memberOptions[$member->id] = "$member->username";
        }

        $this->globalData = [
            'memberOptions' => collect($memberOptions)->prepend('-- Pilih --', ''),
            // 'masterGayaOptions' => MasterGaya::orderByRaw('CAST(`name` AS UNSIGNED) ASC')->pluck('name', 'name')->prepend('-- Pilih --', ''),
        ] + $this->globalData;
    }

    public function index()
    {
        $classes = MasterMemberClass::orderBy('name')
            ->with('userMembers', 'userMembers.user:id,username,name')
            ->withCount(['userMembers' => function ($query) {
                $query->whereHas('user');
            }])
            ->get();

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
            'classes' => $classes,
        ] + $this->globalData;

        return view($this->baseViewPath.'index', $this->globalData);
    }

    public function create(MasterMemberClass $class)
    {
        $this->generateOptions();

        $class->load('userMembers');

        $formFields = new FormFields($class);
        $formFields = $formFields->generateForm();

        $formFields->users = old('users')
        ? Arr::pluck(old('users'), 'id')
        : [];
        // dd($formFields);

        $this->globalData = [
            'pageTitle' => "Buat {$this->moduleName} Baru",
            'class' => $formFields,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create', $this->globalData);
    }

    public function store(StoreMasterMemberClassRequest $request)
    {
        $class = MasterMemberClass::create($request->validated());
        if (! $class) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} Baru GAGAL disimpan!"]);
        }

        // update member class
        if ($request->input('users') && $userIds = Arr::pluck($request->input('users'), 'id')) {
            foreach ($userIds as $user_id) {
                $userMember = UserMember::find($user_id);

                if ($userMember->exists()) {
                    $userMember->update(['master_member_class_id' => $class->id]);
                }
            }
        }

        return redirect()
            ->route($this->baseRouteName.'index')
            ->withSuccess("{$this->moduleName} '{$class->name}' telah disimpan!");
    }

    public function edit(MasterMemberClass $class)
    {
        $this->generateOptions();

        $class->load('userMembers');

        $formFields = new FormFields($class);
        $formFields = $formFields->generateForm();

        $formFields->users = old('users')
        ? Arr::pluck(old('users'), 'id')
        : optional($class->userMembers)->pluck('user_id');

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} {$class->name}",
            'class' => $formFields,
            'id' => $class->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }

    public function update(UpdateMasterMemberClassRequest $request, MasterMemberClass $class)
    {
        // IF FAILED
        if (! $class->update($request->validated())) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '{$class->name}' GAGAL diupdate!"]);
        }

        // update member class
        if ($request->input('users') && $userIds = Arr::pluck($request->input('users'), 'id')) {
            $class->userMembers()
                ->where('master_member_class_id', $class->id)
                ->update(['master_member_class_id' => null]);
            foreach ($userIds as $user_id) {
                $userMember = UserMember::find($user_id);

                if ($userMember->exists()) {
                    $userMember->update(['master_member_class_id' => $class->id]);
                }
            }
        } else {
            $class->userMembers()->update(['master_member_class_id' => null]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '{$class->name}' telah diupdate.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', getQueryParams())
            ->withSuccess("{$this->moduleName} '{$class->name}' telah diupdate.");
    }

    public function destroy(Request $request, MasterMemberClass $class)
    {
        if (! $class->delete()) {
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
