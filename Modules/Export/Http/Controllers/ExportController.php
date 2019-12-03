<?php

namespace Modules\Export\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Export\Repositories\Export\User\UserExportInterface as userExport;
use Illuminate\Routing\Controller;

class ExportController extends Controller
{
    protected $model = 'export';

    public function __construct(User $User,userExport $userExport)
    {
        $this->User = $User;
        $this->userExport = $userExport;
    }
    
    /**
     * Display a listing of the resource.
     * export users records in pdf,excel,print,csv
     * @return Response
     */
    public function exportUserReport(Request $request)
    {
       return $this->userExport->getExportUserReport($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('export::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('export::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('export::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
