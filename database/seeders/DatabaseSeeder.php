<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\EventStage;
use App\Models\MasterCity;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use App\Models\MasterMemberType;
use App\Models\MasterParticipant;
use App\Models\MasterProvince;
use App\Models\MasterSchool;
use App\Models\MasterStaffType;
use App\Models\MasterUserType;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();
        Role::truncate();
        RoleUser::truncate();
        $this->call(StaffSeeder::class);
        $this->call(MemberSeeder::class);
        // $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleUserSeeder::class);

        MasterSchool::truncate();
        MasterMatchType::truncate();
        MasterMatchCategory::truncate();
        MasterParticipant::truncate();
        $this->call(MasterSchoolSeeder::class);
        $this->call(MasterMatchTypeSeeder::class);
        $this->call(MasterMatchCategorySeeder::class);
        $this->call(MasterParticipantSeeder::class);

        Event::truncate();
        EventStage::truncate();
        EventSession::truncate();
        EventSessionParticipant::truncate();
        $this->call(EventSeeder::class);
        $this->call(EventStageSeeder::class);
        $this->call(EventSessionSeeder::class);
        $this->call(EventSessionParticipantSeeder::class);

        MasterUserType::truncate();
        $this->call(MasterUserTypeSeeder::class);
        $this->call(StaffTypeSeeder::class);
        $this->call(MemberTypeSeeder::class);

        MasterStaffType::truncate();
        $this->call(MasterStaffTypeSeeder::class);

        MasterMemberType::truncate();
        $this->call(MasterMemberTypeSeeder::class);

        MasterProvince::truncate();
        $this->call(MasterProvinceSeeder::class);

        MasterCity::truncate();
        $this->call(MasterCitySeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
