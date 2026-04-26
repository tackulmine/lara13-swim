<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Book;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetOrderingController extends BaseController
{
    public function __invoke(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'sometimes|nullable|integer|exists:event_registration_numbers,id',
            'order' => 'sometimes|nullable|integer|min:1',
            'ids' => 'sometimes|nullable|array',
            'items_id' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'code' => 422,
                    'status' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors($validator);
        }

        // commas separated values
        if ($request->input('items_id') && is_string($request->input('items_id'))) {
            $itemsId = array_filter(
                array_map(
                    'trim',
                    explode(',', $request->input('items_id'))
                )
            );
            foreach ($itemsId as $index => $itemId) {
                DB::table('event_registration_numbers')
                    ->where('event_id', $event->id)
                    ->where('id', $itemId)
                    ->update(['order_number' => ($index + 1), 'updated_at' => now()]);
            }
            $data = true;
        }
        // must be array
        if ($request->input('ids') && is_array($request->input('ids'))) {
            $itemsId = $request->input('ids');
            foreach ($itemsId as $index => $itemId) {
                $data = DB::table('event_registration_numbers')
                    ->where('event_id', $event->id)
                    ->where('id', $itemId)
                    ->update(['order_number' => ($index + 1), 'updated_at' => now()]);
            }
        }

        if ($request->input('item_id')) {
            $itemId = $request->input('item_id');
            $order = $request->input('order');

            logger()->info('itemID: '.$itemId.', order: '.$order);

            $data = DB::table('event_registration_numbers')
                ->where('event_id', $event->id)
                ->where('id', $itemId)
                ->update(['order_number' => $order, 'updated_at' => now()]);
        }

        if ($request->ajax()) {
            return response()->json([
                'code' => ($data == true) ? '200' : '500',
                'status' => ($data == true) ? 'Success Update Data' : 'Failed Update Data',
            ], ($data == true) ? 200 : 500);
        }

        if (! $data) {
            return back()
                ->withInput()
                ->withErrors(["{$this->moduleName} '$event->name' GAGAL diurutkan!"]);
        }

        if ($request->action === 'continue') {
            return back()
                ->withSuccess("{$this->moduleName} '$event->name' telah diurutkan.");
        }

        return redirect()
            ->route($this->baseRouteName.'index', $event->id)
            ->withSuccess("{$this->moduleName} '$event->name' telah diurutkan.");
    }
}
