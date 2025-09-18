<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HealthController extends Controller
{
    /**
     * Check the health of the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Check database connection
            DB::connection()->getPdo();
            $dbMigrated = Schema::hasTable('users') && Schema::hasTable('tasks');
        } catch (\Exception $e) {
            $dbMigrated = false;
        }

        return response()->json([
            'app' => 'Task Management API',
            'version' => '1.0.0',
            'db_migrated' => $dbMigrated,
        ]);
    }
}
