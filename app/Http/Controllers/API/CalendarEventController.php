<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Traits\FormatResponse;
use Carbon\Carbon;

class CalendarEventController extends Controller
{
    use FormatResponse;

    public function index()
    {
        $model = CalendarEvent::query();

        if( request()->has('start') && request()->has('end')) {
            $start = Carbon::parse(request()->input('start'))->hour(0)->minute(0)->second(0);
            $end = Carbon::parse(request()->input('end'))->hour(23)->minute(59)->second(59);
            $model->whereBetween ('start', [$start, $end]);
        }else{
            $model->whereBetween('start', [Carbon::now()->subMonths(7), now()->addMonths(6)] );
        }

        if(request()->has('title')) {
            $title = request()->input('title');
            $model->whereLikeColumns(['title'], $title);
        }

        return $this->success(
            data: $model->orderBy('start')->get()
        );
    }
}
