<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\MasterMemberType;
use App\Models\MasterSchool;
use App\Models\MasterUserType;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/dashboard/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|string|min:2|max:255',
            'username' => 'required|string|max:255|unique:users|alpha_dash|min:2|max:50',
            'email' => 'required|string|email|max:255|unique:users|exists:invitations,email',
            'password' => 'required|string|min:8|alpha_num|confirmed',
            'password_confirmation' => 'required|string|min:8|alpha_num',
            'gender' => 'required|in:male,female',
            'relegion' => 'required|in:'.implode(',', array_keys(getRelegions())),
            'last_education' => 'required|in:'.implode(',', array_keys(getEducations())),
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:500',
            'phone_number' => 'required',
            'photo' => 'required|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_width=300,min_height=400',
            'birth_certificate' => 'required|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_width=600,min_height=800',
            'family_card' => 'required|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_width=800,min_height=600',
            'signature_data' => 'required|string',
            'agreement' => 'required|accepted',
        ];

        if (request()->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=300,min_height=400',
                ],
            ] + $rules;
        }
        if (request()->hasFile('birth_certificate')) {
            $rules = [
                'birth_certificate' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=600,min_height=800',
                ],
            ] + $rules;
        }
        if (request()->hasFile('family_card')) {
            $rules = [
                'family_card' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=800,min_height=600',
                ],
            ] + $rules;
        }
        if (request()->hasFile('kta_card')) {
            $rules = [
                'kta_card' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=400,min_height=300',
                ],
            ] + $rules;
        }

        $messages = [
            'photo.max' => ':attribute maksimal berukuran 2 MB.',
            'birth_certificate.max' => ':attribute maksimal berukuran 2 MB.',
            'family_card.max' => ':attribute maksimal berukuran 2 MB.',
        ];

        $customAttributes = [
            'name' => __('Nama Lengkap Atlet'),
            'username' => __('Username/Panggilan'),
            'email' => __('Email Address'),
            'password' => __('Password'),
            'password_confirmation' => __('Konfirmasi Password'),
            'gender' => __('Gender'),
            'relegion' => __('Agama'),
            'last_education' => __('Pendidikan Terakhir'),
            'address' => __('Alamat'),
            // 'location'              => __('Kec, Kab/Kota'),
            'birth_place' => __('Tempat Lahir'),
            'birth_date' => __('Tanggal Lahir'),
            'phone_number' => __('No. Telp/ WhatsApp (WA)'),
            'height' => __('Tinggi badan'),
            'weight' => __('Berat badan'),
            // 'master_school'         => __('Sekolah'),
            // 'master_member_type_id' => __('Status Atlit'),
            'photo' => __('Foto Atlet'),
            'birth_certificate' => __('Akte Kelahiran'),
            'family_card' => __('KK (Kartu Keluarga)'),
            'kta_card' => __('KTA (Kartu Tanda Anggota)'),
            'signature_data' => __('Tanda Tangan'),
            'agreement' => __('Persetujuan'),
        ];

        return Validator::make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'master_user_type_id' => MasterUserType::MEMBER_ID,
        ]);
    }

    /**
     * Request invitation form.
     *
     * @return Factory|View
     */
    public function requestInvitation()
    {
        return view('auth.request');
    }

    /**
     * Override the application registration form. Get the email that has been associated with the invitation and
     * pass it to the view.
     *
     * @return Response
     */
    public function showRegistrationForm(Request $request)
    {
        $invitation_token = $request->get('invitation_token');
        $invitation = Invitation::where('invitation_token', $invitation_token)->firstOrFail();
        $data['genderOptions'] = getGenders();
        $data['relegionOptions'] = getRelegions();
        $data['educationOptions'] = getEducations();
        $data['email'] = $invitation->email;

        return view('auth.register', $data);
    }

    /**
     * After user registered, update the invitation registered_at and all the relationship.
     */
    public function registered(Request $request, User $user)
    {
        // update invitation registered_at
        $invitation = Invitation::where('email', $user->email)->firstOrFail();
        $invitation->registered_at = $user->created_at;
        $invitation->save();

        // init user profile attr
        $userProfileAttributes = $request->except('name', 'username', 'email', 'password', '_token', '_method', 'action');

        // update photo
        if ($request->hasFile('photo')) {
            // inject data attributes to update photo
            $userProfileAttributes['photo'] = uploadAvatar($request->photo, $user->id);
        }
        // update other files
        if ($request->hasFile('birth_certificate')) {
            $userProfileAttributes['birth_certificate'] = uploadMemberFile($request->birth_certificate, 'birth_certificate-'.$user->id);
        }
        if ($request->hasFile('family_card')) {
            $userProfileAttributes['family_card'] = uploadMemberFile($request->family_card, 'family_card-'.$user->id);
        }
        if ($request->hasFile('kta_card')) {
            $userProfileAttributes['kta_card'] = uploadMemberFile($request->kta_card, 'kta_card-'.$user->id);
        }

        // cleanup the custom display
        $userProfileAttributes['birth_date'] = Carbon::parse($request->birth_date)->toDateString();
        $userProfileAttributes['phone_number'] = cleanPhoneNumber($request->phone_number);

        // Create a new Image instance from the Base64 data
        $img = Image::make($request->signature_data);
        // Now you can perform manipulations, e.g., resize
        // $img->resize(300, 200);
        $signaturePath = 'members/signature_'.$user->id.'.jpg';
        // You can save the image to a file
        $img->save(config('filesystems.disks.shared.root').'/'.$signaturePath);

        // create new profile
        $user->profile()->create([
            'signature' => $signaturePath,
        ] + $userProfileAttributes);

        // create new member & position
        $user->userMember()->create([
            'master_member_type_id' => MasterMemberType::ATHLETE_ID,
        ]);

        // create or using default school/tim
        $masterSchool = MasterSchool::firstOrCreate([
            'name' => $request->master_school ?? 'CENTRUM SC',
        ]);
        $user->educations()->create([
            'master_school_id' => $masterSchool->id,
        ]);

        // assign member role
        $user->assignRole('member');
    }
}
