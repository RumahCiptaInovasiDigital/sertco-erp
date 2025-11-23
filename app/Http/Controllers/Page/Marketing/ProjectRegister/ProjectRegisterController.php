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
        return view('page.v1.marketing.register-project.index');
    }

    public function create()
    {
        $project_no = $this->generateProjectNo();

        return view('page.v1.marketing.register-project.create', compact('project_no'));
    }

    public function store(\Request $request)
    {
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
