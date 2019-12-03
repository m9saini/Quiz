<?php
namespace Modules\Export\Repositories\Export\User;

use App\User;
use Input,DB,PDF,Excel;
use App\Exports\UsersExport;

class UserExportRepository implements UserExportInterface
{

    protected $model = 'export';
    
    function __construct(User $User) {
        $this->User = $User;
    }

    public function getExportUserReport($request)
    {
        $inputs = $request->input();
        $model = strtolower($this->model);
        $data = $this->User->all();
        if($inputs['export_type'] == 'pdf') {
            $pdf = PDF::loadView(strtolower($this->model) . '::export.users.pdf.users-pdf', compact('data','model'))->setPaper('a4', 'landscape');
            $fileName = 'all_instructor_report.pdf';
            return $pdf->download($fileName);
        }
        else if($inputs['export_type'] == 'print') {
            $pdf = PDF::loadView(strtolower($this->model) . '::export.users.pdf.users-pdf', compact('data','model'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
        else if($inputs['export_type'] == 'excel') {
            $export = new UsersExport($request,$data);
            return Excel::download($export, 'all_users_report.xlsx');
        }
        else if($inputs['export_type'] == 'csv') {
            $export = new UsersExport($request,$data);
            return Excel::download($export, 'all_users_report.csv');
        }
    }
}