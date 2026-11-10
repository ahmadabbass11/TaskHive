<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request, $taskId)
{
    $request->validate([
        'file' => 'required|mimes:pdf,docx,xlsx,png,jpg|max:2048',
    ]);

    $file = $request->file('file');
    $path = $file->store('task_files', 'public');

    // Check if the task already has a file submission
    $existingFile = File::where('task_id', $taskId)->first();

    if ($existingFile) {
        // Delete old file from storage before updating
        Storage::delete('public/' . $existingFile->file_path);

        // Update the existing file record
        $existingFile->update([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);
    } else {
        // Create a new file record if no submission exists
        File::create([
            'user_id' => auth()->id(),
            'task_id' => $taskId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    // ✅ Update the task status to "Done"
    $task = Task::findOrFail($taskId);
    $task->update(['status' => 'Done']);

    return back()->with('success', 'File uploaded successfully and task updated.');
}


    public function download(File $file)
{
    $filePath = storage_path("app/public/{$file->file_path}");

    // Check if file exists
    if (!file_exists($filePath)) {
        return back()->with('error', 'File not found.');
    }

    // Force download
    return response()->download($filePath, $file->file_name);
}


}
