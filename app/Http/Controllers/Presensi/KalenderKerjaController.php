<?php

namespace App\Http\Controllers\Presensi;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class KalenderKerjaController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Kalender Kerja',
        ];
        return view('page.master.kalender-kerja', $data);
    }

    public function events(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $events = CalendarEvent::whereBetween('start', [$start, $end])->get();

        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start->toIso8601String(),
                'end' => $event->end ? $event->end->toIso8601String() : null,
                'allDay' => $event->all_day,
                'backgroundColor' => $event->color,
                'borderColor' => $event->color,
                'extendedProps' => [
                    'description' => $event->description
                ]
            ];
        });

        return response()->json($formattedEvents);
    }

    public function store(Request $request)
    {
        $request->merge([
            'all_day' => $request->input('all_day') == 'on' || $request->input('all_day') === true || $request->input('all_day') == 1,
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'all_day' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if (empty($data['end'])) {
            $data['end'] = null;
        }

        $event = CalendarEvent::create($data);

        return response()->json(['success' => 'Acara berhasil ditambahkan.', 'event' => $event]);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'all_day' => $request->input('all_day') == 'on' || $request->input('all_day') === true || $request->input('all_day') == 1,
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'all_day' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = CalendarEvent::find($id);
        if (!$event) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $data = $request->all();
        if (empty($data['end'])) {
            $data['end'] = null;
        }

        $event->update($data);

        return response()->json(['success' => 'Acara berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $event = CalendarEvent::find($id);
        if (!$event) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $event->delete();

        return response()->json(['success' => 'Acara berhasil dihapus.']);
    }

    public function importHolidays(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $apiUrl = "https://api-harilibur.vercel.app/api?year=" . $year;

        try {
            $client = new Client();
            $response = $client->get($apiUrl);
            $holidays = json_decode($response->getBody()->getContents(), true);

            $importedCount = 0;
            foreach ($holidays as $holiday) {
                if (isset($holiday['is_national_holiday']) && $holiday['is_national_holiday']) {
                    CalendarEvent::updateOrCreate(
                        ['start' => $holiday['holiday_date']],
                        [
                            'title' => $holiday['holiday_name'],
                            'description' => $holiday['holiday_name'],
                            'color' => '#dc3545', // Merah untuk libur
                            'all_day' => true,
                        ]
                    );
                    $importedCount++;
                }
            }

            return response()->json(['success' => $importedCount . ' hari libur nasional berhasil diimport.']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengimport hari libur: ' . $e->getMessage()], 500);
        }
    }
}
