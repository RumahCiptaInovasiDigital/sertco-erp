<?php

namespace App\Http\Controllers\Page\Marketing\ProjectRegister;

use App\Http\Controllers\Controller;
use App\Traits\GenerateProjectNo;

class ProjectRegisterController extends Controller
{
    use GenerateProjectNo;

    public function getData()
    {
    }

    public function index()
    {
        return view('page.v1.marketing.project-register.index');
    }

    public function edit($id)
    {
    }

    public function update(\Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
