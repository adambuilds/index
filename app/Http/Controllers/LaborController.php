<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LaborController extends Controller
{
    public function logPostData(Request $request)
    {
        // Log all POST data
        Log::info('POST data:', $request->all());

        return response()->json(['message' => 'Data logged successfully']);
    }

    public function store_old(Request $request)
    {
        // Retrieve the JSON data from the request
        $data = $request->json()->all();
    
        // Extract JobID from Job string using regex pattern
        $jobString = $data['Job'] ?? $data['job'] ?? null;
        $jobId = $this->extractJobId($jobString);
    
        // Prepare the data for insertion
        $insertData = [
            'JobID' => $jobId,
            'EmployeeID' => 0, // Default value
            'Date' => date('Y-m-d', strtotime($data['Timestamp'] ?? $data['timestamp'] ?? now())),
            'LaborTypeID' => 1, // Default value
            'description' => ($data['email'] ?? null) . ";" . ($data['job'] ?? null) . ";" . ($data['task'] ?? null) . ";" . ($data['coords'] ?? null) . ";" . ($data['notes'] ?? null),
            'StampIn' => null, // Initialize as null
            'StampOut' => null, // Initialize as null
        ];
    
        // Set StampIn or StampOut based on the state
        if ($data['state'] === 'in') {
            $insertData['StampIn'] = date('Y-m-d H:i:s', strtotime($data['Timestamp'] ?? $data['timestamp'] ?? now()));
        } elseif ($data['state'] === 'out') {
            $insertData['StampOut'] = date('Y-m-d H:i:s', strtotime($data['Timestamp'] ?? $data['timestamp'] ?? now()));
        }
    
        // Insert the data into the LaborNew table
        DB::table('LaborNew')->insert($insertData);
    
        return response()->json(['message' => 'Data successfully inserted'], 201);
    }

    public function store(Request $request)
    {
        // Retrieve the JSON data from the request
        $data = $request->json()->all();

        // Prepare the data for insertion into the clock_events table
        $insertData = [
            'first_name' => $data['First Name'] ?? $data['first_name'] ?? null,
            'last_name' => $data['Last Name'] ?? $data['last_name'] ?? null,
            'email' => $data['Email'] ?? $data['email'] ?? null,
            'clock_time' => isset($data['Clock Time']) ? date('Y-m-d H:i:s', strtotime($data['Clock Time'])) : null,
            'clock_lat' => $data['Clock Lat'] ?? null,
            'clock_long' => $data['Clock Long'] ?? null,
            'job_name' => $data['Job Name'] ?? $data['job'] ?? null,
            'task_name' => $data['Task Name'] ?? $data['task'] ?? null,
            'notes' => $data['Notes'] ?? $data['notes'] ?? null,
        ];

        // Insert the data into the clock_events table
        DB::table('clock_events')->insert($insertData);

        return response()->json(['message' => 'Data successfully inserted'], 201);
    }

    /**
     * Extracts the JobID from the Job string based on the pattern "YY-000" or "YY-0000".
     *
     * @param string|null $jobString
     * @return string|null
     */
    private function extractJobId($jobString)
    {
        if ($jobString) {
            // Use regex to match patterns like "YY-000" or "YY-0000"
            if (preg_match('/\b\d{2}-\d{3,4}\b/', $jobString, $matches)) {
                return $matches[0];
            }
        }

        // Return null if no match is found
        return null;
    }

}

