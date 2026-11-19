<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * LAB 3 (intentionally vulnerable): unsafe SQL string construction and raw error disclosure.
 */
class ProjectSearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $orgId = $request->user()->organization_id;

        try {
            $projects = DB::select(
                "SELECT * FROM projects WHERE organization_id = {$orgId} AND name LIKE '%{$keyword}%' ORDER BY id"
            );
        } catch (\Throwable $e) {
            return response(
                "Search failed.\n\nException: ".get_class($e)."\nMessage: ".$e->getMessage()
                ."\nFile: ".$e->getFile().':'.$e->getLine(),
                500,
                ['Content-Type' => 'text/plain']
            );
        }

        return view('projects.search', [
            'projects' => $projects,
            'keyword' => $keyword,
        ]);
    }
}
