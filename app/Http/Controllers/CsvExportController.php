<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\client;

class CsvExportController extends Controller
{
    public function export()
    {
        $data = client::all(); 

        $csvFileName = 'exported_data.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://output', 'w');
        fputcsv($handle, array_keys($data->first()->toArray()));

        foreach ($data as $row) {
            fputcsv($handle, $row->toArray());
        }

        fclose($handle);

        return Response::stream(
            function () use ($handle) {
                fclose($handle);
            },
            200,
            $headers
        );
    }
}

