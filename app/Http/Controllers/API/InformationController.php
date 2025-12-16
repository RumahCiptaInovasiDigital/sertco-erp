<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Enum\StatusInformation;
use App\Models\Information;
use App\Traits\FormatResponse;

class InformationController extends Controller
{
    use FormatResponse;
    public function index()
    {
        $model = Information::query()
                    ->where("status", StatusInformation::ACTIVE);
        if(request()->has("title")) {
            $title = request()->input("title");
            $model->whereLikeColumns(['title'], $title);
        }

        return $this->success(data: $model->orderBy('created_at', 'desc')->paginate());

    }
}
