<?php

namespace Devrabiul\AdvancedFileManager\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Devrabiul\AdvancedFileManager\Services\AdvancedFileManagerService;
use Devrabiul\AdvancedFileManager\Services\S3FileManagerService;

class FileManagerHelperService
{
    public static function getAllFiles($targetFolder = null, object|array $request = null): array
    {
        $GenData = [];
        $request = !empty($request) ? $request : request()->all();
        $targetFolder = !empty($targetFolder) ? $targetFolder : request('targetFolder') ?? '/';
        $AllFilesInCurrentFolder = Storage::disk(S3FileManagerService::getStorageDriver())->files($targetFolder);
        $GenData['path'] = $AllFilesInCurrentFolder;

        $FilesWithInfo = [];
        foreach ($AllFilesInCurrentFolder as $file) {
            $type = explode('/', Storage::disk(S3FileManagerService::getStorageDriver())->mimeType($file))[0];
            $name = explode('/', $file);
            if (!empty($targetFolder) && count($name) > 1) {
                if ((!empty($request['filter']) && $type == $request['filter']) || (empty($request['filter']) || ($request['filter'] == 'all'))) {
                    $FilesWithInfo[] = [
                        'name' => end($name),
                        'short_name' => FileManagerHelperService::getFileMinifyString(end($name)),
                        'path' => $file,
                        'encodePath' => Crypt::encryptString($file),
                        'type' => $type,
                        'icon' => self::getIconByExtension(extension: pathinfo($file, PATHINFO_EXTENSION)),
                        'size' => FileManagerHelperService::getMasterFileFormatSize(Storage::disk(S3FileManagerService::getStorageDriver())->size($file)),
                        'sizeInInteger' => Storage::disk(S3FileManagerService::getStorageDriver())->size($file),
                        'extension' => pathinfo($file, PATHINFO_EXTENSION),
                        'last_modified' => Carbon::parse(date('Y-m-d H:i:s', Storage::disk(S3FileManagerService::getStorageDriver())->lastModified($file)))->diffForHumans()
                    ];
                }
            }
        }

        $totalFileSize = 0;
        foreach ($AllFilesInCurrentFolder as $file) {
            $totalFileSize += Storage::disk(S3FileManagerService::getStorageDriver())->size($file);
        }

        $GenData['size'] = FileManagerHelperService::getMasterFileFormatSize($totalFileSize);
        $GenData['files'] = $FilesWithInfo;
        $GenData['totalFiles'] = count($AllFilesInCurrentFolder);

        $GenData['last_modified'] = Carbon::parse(date('Y-m-d H:i:s', Storage::disk(S3FileManagerService::getStorageDriver())->lastModified('')))->diffForHumans();
        if ($targetFolder && Storage::exists($targetFolder)) {
            $GenData['last_modified'] = Carbon::parse(date('Y-m-d H:i:s', Storage::disk(S3FileManagerService::getStorageDriver())->lastModified($targetFolder)))->diffForHumans();
        }

        return $GenData;
    }

    public static function renderAdvancedFileManagerView(array|object $request = [], string $type = null)
    {
        $requestData = !empty($request) ? $request : request()->all();
        $targetFolder = urldecode($requestData['targetFolder'] ?? '');

        $cacheKeyFiles = "files_in_{$targetFolder}";
        $cacheKeyFolders = "folders_in_{$targetFolder}";
        $cacheKeyOverview = "overview_in_{$targetFolder}";

        FileManagerHelperService::cacheKeys($cacheKeyFiles);
        FileManagerHelperService::cacheKeys($cacheKeyFolders);
        FileManagerHelperService::cacheKeys($cacheKeyOverview);

        $AllFilesInCurrentFolder = Cache::remember($cacheKeyFiles, 3600, function () use ($targetFolder, $requestData) {
            return AdvancedFileManagerService::getAllFiles(targetFolder: $targetFolder, request: $requestData);
        });

        $AllFilesInCurrentFolder['files'] = AdvancedFileManagerService::getAllFilesInCurrentFolder($cacheKeyFiles, $targetFolder, $requestData);

        $folderArray = Cache::remember($cacheKeyFolders, 3600, function () use ($targetFolder) {
            return AdvancedFileManagerService::getAllFolders($targetFolder);
        });

        $AllFilesOverview = Cache::remember($cacheKeyOverview, 3600, function () use ($AllFilesInCurrentFolder) {
            return AdvancedFileManagerService::getAllFilesOverview(AllFilesWithInfo: $AllFilesInCurrentFolder);
        });

        $lastFolderArray = explode('/', $targetFolder);
        $lastFolder = count($lastFolderArray) > 1 ? str_replace('/' . end($lastFolderArray), '', $targetFolder) : '';

        $dataArray = [
            'folderArray' => $folderArray,
            'AllFilesInCurrentFolder' => $AllFilesInCurrentFolder,
            'lastFolder' => $lastFolder,
            'AllFilesOverview' => $AllFilesOverview
        ];

        // Check if the theme exists or fallback to a default if needed
        $theme = self::getFileManagerTheme() ?: 'default';
        return [
            'html' => view("advanced-file-manager::$theme.partials._content", $dataArray)->render(),
            'html_files' => view("advanced-file-manager::$theme.partials._files-list-content", $dataArray)->render(),
        ];
    }
    
    
    public static function getFileMinifyString($inputString, $prefixLength = 15, $suffixLength = 8, $ellipsis = '.....')
    {
        if (strlen($inputString) <= $prefixLength + $suffixLength) {
            return $inputString;
        }
        $prefix = substr($inputString, 0, $prefixLength);
        $suffix = substr($inputString, -$suffixLength);
        return $prefix . $ellipsis . $suffix;
    }
    
    public static function getMasterFileFormatSize($size = 0): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $unitIndex = 0;
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public static function masterFileManagerStorage(string $path): string
    {
        if (config('advanced-file-manager.system_processing_directory') == 'public') {
            $result = str_replace('storage/app/public', 'storage', $path);
        } else {
            $result = $path;
        }
        return asset($result);
    }
    
    public static function masterFileManagerAsset(string $path): string
    {
        if (config('advanced-file-manager.system_processing_directory') == 'public') {
            $result = asset('vendor/devrabiul/advanced-file-manager/' . $path);
        } else {
            $result = asset('public/vendor/devrabiul/advanced-file-manager/' . $path);
        }
        return $result;
    }
    
    public static function cacheKeys($cacheKey): void
    {
        $cacheKeys = Cache::get('masterFileManagerCacheKeys', []);
        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put('masterFileManagerCacheKeys', $cacheKeys, 60 * 60 * 3);
        }
    }
    
    public static function getFileManagerTheme(): string
    {
        if (Str::lower(config('advanced-file-manager.theme')) == 'modern') {
            return 'modern';
        } elseif (Str::lower(config('advanced-file-manager.theme')) == 'material') {
            return 'material';
        } else {
            return 'classic';
        }
    }
    
    
}
