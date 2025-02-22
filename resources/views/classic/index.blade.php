<?php

    use Devrabiul\AdvancedFileManager\Services\AdvancedFileManagerService;
    use Devrabiul\AdvancedFileManager\Services\FileManagerHelperService;
    use Devrabiul\AdvancedFileManager\Services\S3FileManagerService;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Facades\Storage;

    $storageDriver = S3FileManagerService::getStorageDriver();
    $requestData = !empty($request) ? $request : request()->all();
    $targetFolder = urldecode($requestData['targetFolder'] ?? '');

    $cacheKeyAllFiles = "files_in_{$storageDriver}";
    $cacheKeyFiles = "files_in_{$targetFolder}_{$storageDriver}";
    $cacheKeyFolders = "folders_in_{$targetFolder}_{$storageDriver}";
    $cacheKeyOverview = "overview_in_{$targetFolder}_{$storageDriver}";

    FileManagerHelperService::cacheKeys($cacheKeyAllFiles);
    FileManagerHelperService::cacheKeys($cacheKeyFiles);
    FileManagerHelperService::cacheKeys($cacheKeyFolders);
    FileManagerHelperService::cacheKeys($cacheKeyOverview);

    $AllFilesInStorage = Cache::remember($cacheKeyAllFiles, 3600, function () {
        return Storage::disk(S3FileManagerService::getStorageDriver())->allFiles();
    });

    $AllFilesInCurrentFolder = Cache::remember($cacheKeyFiles, 3600, function () use ($targetFolder, $requestData) {
        return AdvancedFileManagerService::getAllFiles(targetFolder: $targetFolder, request: $requestData);
    });

    $AllFilesInCurrentFolderFiles = AdvancedFileManagerService::getAllFilesInCurrentFolder($cacheKeyFiles, $targetFolder, $requestData);

    $folderArray = Cache::remember($cacheKeyFolders, 3600, function () use ($targetFolder) {
        return AdvancedFileManagerService::getAllFolders($targetFolder);
    });

    $AllFilesOverview = Cache::remember($cacheKeyOverview, 3600, function () use ($AllFilesInCurrentFolder) {
        return AdvancedFileManagerService::getAllFilesOverview(AllFilesWithInfo: $AllFilesInCurrentFolder);
    });

    $recentFiles = AdvancedFileManagerService::getRecentFiles();
    $favoriteFiles = AdvancedFileManagerService::getFavoriteFiles();
    $quickAccess = AdvancedFileManagerService::getQuickAccessStats();

    $lastFolderArray = explode('/', $targetFolder);
    $lastFolder = count($lastFolderArray) > 1 ? str_replace('/' . end($lastFolderArray), '', $targetFolder) : '';
?>
<div class="file-manager-root-container">

    <!-- Header Container | Start -->
    <section class="file-manager-header">
        <div class="header-left">
            <button id="sidebarToggle" class="sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>
            <h4>
                {{ config('advanced-file-manager.name') }}
                <span class="version-badge">v1.0</span>
            </h4>
        </div>

        <div class="header-right">
            <div class="search-container">
                <i class="bi bi-search"></i>
                <input type="search" placeholder="Search files, folders">
            </div>

            <button id="createBtn" class="header-responsive-btn">
                <i class="bi bi-plus"></i>
                <span>Upload</span>
            </button>

            <button id="actionSmartFileSync" class="header-responsive-btn" data-route="{{ route('advanced-file-manager.smart-file-sync') }}">
                <i class="bi bi-arrow-repeat"></i>
                <span>Smart File Sync</span>
            </button>
        </div>
    </section>
    <!-- Header Container | End -->

    <!-- Main Container | Start -->
    <section class="file-manager-main-container temp-border">

        <!-- Sidebar Container | Start -->
        @include('advanced-file-manager::classic.partials._sidebar')
        <!-- Sidebar Container | End -->

        <!-- Files Container | Start -->
        <section class="file-manager-files-container temp-border" data-route="{{ route('advanced-file-manager.folder-content') }}">
            <div class="advanced-file-manager-loader-container loader-container-hide">
                <div class="loader">
                    <div class="loader-circle"></div>
                    <div class="loader-text">Loading...</div>
                </div>
            </div>

            <div class="advanced-file-manager-content">
                @include('advanced-file-manager::classic.partials._content')
            </div>

        </section>
        <!-- Files Container | End -->

    </section>
    <!-- Main Container | End -->

    <!-- Add this before the closing body tag -->
    <div class="modal-overlay" id="createModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Upload Files</h5>
                <button class="close-modal"><i class="bi bi-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="upload-area">
                    <div class="filepond-wrapper">
                        <input type="file" class="filepond" name="files" multiple>
                    </div>
                </div>
            </div>
            <div class="upload-actions">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="button" class="btn-upload">
                    <i class="bi bi-cloud-arrow-up"></i>
                    Upload
                </button>
            </div>
        </div>
    </div>

</div>
