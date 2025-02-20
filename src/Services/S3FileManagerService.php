<?php

namespace Devrabiul\AdvancedFileManager\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
    use Illuminate\Pagination\LengthAwarePaginator;
use Devrabiul\AdvancedFileManager\Services\FileManagerHelperService;

class S3FileManagerService
{
    public static function getStorageDriver()
    {
        if (request()->has('driver') && !empty(request('driver')) && array_key_exists(request('driver'), config('advanced-file-manager.storage_drivers'))) {
            return request('driver');
        }
        return config('advanced-file-manager.default_driver');
    }

    public static function checkS3DriverCredential()
    {
        if (request()->has('driver') && !empty(request('driver')) && array_key_exists(request('driver'), config('advanced-file-manager.storage_drivers'))) {
            if(request('driver') == 's3') {
                try {
                    $content = "This is a test file uploaded to S3.";
                    $fileName = 'test_file.txt';
                    $s3 = Storage::disk('s3');

                    dd($s3->files('/'));
                    // Storage::disk('s3')->put($fileName, $content);
                    // if (Storage::disk('s3')->exists($fileName)) {
                    //     Storage::disk('s3')->delete($fileName);
                    // }
                } catch (\Exception $exception) {
                    dd($exception);
                    return [
                        'status' => false,
                        'message' => 'storage_connection_type_unable_to_changed_due_to_s3_wrong_credential.'
                    ];
                }
            }
        }
        return [
            'status' => true,
            'message' => 'storage_connection_type_check_successfully.'
        ];
    }
}
