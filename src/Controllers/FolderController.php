<?php

namespace Devrabiul\AdvancedFileManager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class FolderController extends Controller
{
    public function rename(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'newName' => 'required|string|max:255'
        ]);

        try {
            $oldPath = $request->path;
            $newPath = dirname($oldPath) . '/' . $request->newName;

            if (Storage::exists($newPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'A folder with this name already exists'
                ], 422);
            }

            Storage::move($oldPath, $newPath);

            return response()->json([
                'success' => true,
                'html' => view('partials.file-manager-content')->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error renaming folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function copy(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            $newPath = $this->generateUniqueCopyPath($request->path);
            
            // Recursively copy the directory
            $this->recursiveCopy($request->path, $newPath);

            return response()->json([
                'success' => true,
                'html' => view('partials.file-manager-content')->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error copying folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function move(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'destination' => 'required|string'
        ]);

        try {
            if (!Storage::exists($request->destination)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destination folder does not exist'
                ], 422);
            }

            Storage::move($request->path, $request->destination . '/' . basename($request->path));

            return response()->json([
                'success' => true,
                'html' => view('partials.file-manager-content')->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error moving folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function info(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            $info = [
                'name' => basename($request->path),
                'size' => $this->getFolderSize($request->path),
                'created' => Storage::lastModified($request->path),
                'files' => $this->countFiles($request->path),
                'folders' => $this->countFolders($request->path)
            ];

            return response()->json([
                'success' => true,
                'html' => view('partials.folder-info-modal', compact('info'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting folder info: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            Storage::deleteDirectory($request->path);

            return response()->json([
                'success' => true,
                'html' => view('partials.file-manager-content')->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting folder: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueCopyPath($path)
    {
        $original = $path;
        $counter = 1;
        
        while (Storage::exists($path)) {
            $path = dirname($original) . '/' . basename($original) . " ($counter)";
            $counter++;
        }
        
        return $path;
    }

    private function recursiveCopy($from, $to)
    {
        $files = Storage::files($from);
        $directories = Storage::directories($from);

        // Create the destination directory
        Storage::makeDirectory($to);

        // Copy all files
        foreach ($files as $file) {
            Storage::copy($file, $to . '/' . basename($file));
        }

        // Recursively copy subdirectories
        foreach ($directories as $directory) {
            $this->recursiveCopy(
                $directory,
                $to . '/' . basename($directory)
            );
        }
    }

    private function getFolderSize($path)
    {
        $size = 0;
        foreach (Storage::allFiles($path) as $file) {
            $size += Storage::size($file);
        }
        return $size;
    }

    private function countFiles($path)
    {
        return count(Storage::files($path));
    }

    private function countFolders($path)
    {
        return count(Storage::directories($path));
    }

    public function viewStyleSetup(Request $request)
    {
        Session::put('file_list_container_view_mode', request('view_mode') ?? 'grid-view');
        return response()->json([
            'status' => 'success',
            'view_mode' => session('file_list_container_view_mode'),
        ]);
    }
} 