<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Book;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

trait EventBookTrait
{
    protected function generateEventNumbers(Event $event, bool $resetAll = false)
    {
        if ($resetAll || ! $this->hasEventNumbers($event)) {
            $categories = $this->getEventCategories($event);

            foreach ($categories as $index => $category) {
                DB::table('event_registration_numbers')->updateOrInsert([
                    'event_id' => $event->id,
                    'master_match_type_id' => $category->type_id,
                    'master_match_category_id' => $category->category_id,
                ], [
                    'order_number' => $index + 1,
                ]);
            }
        }
    }

    protected function hasEventNumbers(Event $event): bool
    {
        return DB::table('event_registration_numbers')
            ->where('event_id', $event->id)
            ->exists();
    }

    protected function getEventCategories(Event $event)
    {
        return DB::table('event_registrations')
            ->join('master_match_categories', 'event_registrations.master_match_category_id', '=', 'master_match_categories.id')
            ->join('event_registration_style', 'event_registrations.id', '=', 'event_registration_style.event_registration_id')
            ->join('master_match_types', 'event_registration_style.master_match_type_id', '=', 'master_match_types.id')
            ->join('event_category', [
                ['event_registrations.event_id', '=', 'event_category.event_id'],
                ['event_registrations.master_match_category_id', '=', 'event_category.master_match_category_id'],
            ])
            ->join('event_type', [
                ['event_registrations.event_id', '=', 'event_type.event_id'],
                ['event_registration_style.master_match_type_id', '=', 'event_type.master_match_type_id'],
            ])
            ->where('event_registrations.event_id', $event->id)
            ->whereNull('event_registrations.deleted_at')
            ->groupBy('master_match_types.id', 'master_match_categories.id')
            ->orderBy('event_type.ordering')
            ->orderBy('event_category.ordering')
            ->select([
                'master_match_types.id as type_id',
                'master_match_types.name as type_name',
                'master_match_categories.id as category_id',
                'master_match_categories.name as category_name',
                DB::raw('COUNT(event_registrations.master_participant_id) as total'),
            ])
            ->get();
    }

    protected function getEventNumbers(Event $event)
    {
        return DB::table('event_registrations')
            ->join('event_registration_numbers', [
                ['event_registrations.master_match_category_id', '=', 'event_registration_numbers.master_match_category_id'],
                ['event_registrations.event_id', '=', 'event_registration_numbers.event_id'],
            ])
            ->join('master_match_categories', 'event_registrations.master_match_category_id', '=', 'master_match_categories.id')
            ->join('event_registration_style', [
                ['event_registrations.id', '=', 'event_registration_style.event_registration_id'],
                ['event_registration_numbers.master_match_type_id', '=', 'event_registration_style.master_match_type_id'],
            ])
            ->join('master_match_types', 'event_registration_style.master_match_type_id', '=', 'master_match_types.id')
            ->where('event_registrations.event_id', $event->id)
            ->whereNull('event_registrations.deleted_at')
            ->groupBy('master_match_types.id', 'master_match_categories.id')
            ->orderBy('event_registration_numbers.order_number')
            ->select([
                'event_registration_numbers.id as event_registration_number_id',
                'event_registration_numbers.order_number',
                'master_match_types.name as type_name',
                'master_match_categories.name as category_name',
                DB::raw('COUNT(event_registrations.master_participant_id) as total'),
                DB::raw("CONCAT(master_match_categories.name, ' - ', master_match_types.name) as sheetName"),
                'event_registration_numbers.order_number as number',
            ])
            ->get();
    }
}
