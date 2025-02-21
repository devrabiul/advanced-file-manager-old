<?php

use Illuminate\Support\Facades\Route;
use Devrabiul\AdvancedFileManager\Controllers\FolderController;
use Devrabiul\AdvancedFileManager\Controllers\FileManagerController;

Route::group(['prefix' => 'advanced-file-manager'], function () {
    Route::controller(FileManagerController::class)->group(function () {
        Route::post('folder-content', 'getFolderContent')->name('advanced-file-manager.folder-content');
        Route::post('smart-file-sync', 'syncFileList')->name('advanced-file-manager.smart-file-sync');
        Route::post('view-style-setup', 'viewStyleSetup')->name('advanced-file-manager.view-style-setup');
    });

    Route::group(['prefix' => 'folders'], function () {
        Route::controller(FolderController::class)->group(function () {
            Route::post('/rename', 'rename')->name('advanced-file-manager.folders.rename');
            Route::post('/copy',  'copy')->name('advanced-file-manager.folders.copy');
            Route::post('/move', 'move')->name('advanced-file-manager.folders.move');
            Route::get('/info',  'info')->name('advanced-file-manager.folders.info');
            Route::delete('/delete', 'delete')->name('advanced-file-manager.folders.delete');
        });
    });
});