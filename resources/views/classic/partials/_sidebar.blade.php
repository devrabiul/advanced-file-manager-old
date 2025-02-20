<?php 
    use Devrabiul\AdvancedFileManager\Services\S3FileManagerService;
?>

<section class="file-manager-sidebar-container">
    <button class="sidebar-close-mobile">
        <i class="bi bi-x-lg"></i>
    </button>

    <!-- Quick Access -->
    <h5 class="quick-access-section-title">Quick Access</h5>
    <div class="quick-access-section">

        <?php
            $storageDrivers = config('advanced-file-manager.storage_drivers');
            $enabledDrivers = array_filter($storageDrivers, function($key) {
                return in_array($key, config('advanced-file-manager.enabled_drivers'));
            }, ARRAY_FILTER_USE_KEY);
        ?>
        <div class="quick-access-dropdown">
            <select class="custom-select">
                <option value="" selected disabled>Quick Access</option>
                @foreach($enabledDrivers as $key => $driver)
                    <option value="{{ $driver['path'] }}">{{ $driver['name'] }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down"></i>
        </div>

        @if (request('driver') == 's3')
            @dd(S3FileManagerService::checkS3DriverCredential())
        @endif

        <div class="quick-access-items">
            <div class="quick-access-item item-cursor-pointer" onclick="openFolderByAjax('')">
                <div class="quick-access-content">
                    <i class="bi bi-folder"></i>
                    <span>Main Directory</span>
                </div>
                <span class="type-size">
                    <i class="bi bi-activity"></i>
                </span>
            </div>
            <a href="#" class="quick-access-item" data-type="recent">
                <div class="quick-access-content">
                    <i class="bi bi-clock-history"></i>
                    <span>Recent Files</span>
                </div>
                <span class="type-size">{{ $quickAccess['recent']['totalFiles'] }} files</span>
            </a>
            <!-- <a href="#" class="quick-access-item" data-type="favorites">
                <div class="quick-access-content">
                    <i class="bi bi-star"></i>
                    <span>Favorites</span>
                </div>
                <span class="type-size">{{ $quickAccess['favorites']['totalFiles'] }} files</span>
            </a> -->
            <a href="#" class="quick-access-item" data-type="images">
                <div class="quick-access-content">
                    <i class="bi bi-image"></i>
                    <span>Images</span>
                </div>
                <span class="type-size">{{ $quickAccess['images']['size'] }}</span>
            </a>
            <a href="#" class="quick-access-item" data-type="documents">
                <div class="quick-access-content">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Documents</span>
                </div>
                <span class="type-size">{{ $quickAccess['documents']['size'] }}</span>
            </a>
            <a href="#" class="quick-access-item" data-type="videos">
                <div class="quick-access-content">
                    <i class="bi bi-camera-video"></i>
                    <span>Videos</span>
                </div>
                <span class="type-size">{{ $quickAccess['videos']['size'] }}</span>
            </a>
            <a href="#" class="quick-access-item" data-type="music">
                <div class="quick-access-content">
                    <i class="bi bi-music-note-beamed"></i>
                    <span>Music</span>
                </div>
                <span class="type-size">{{ $quickAccess['music']['size'] }}</span>
            </a>
        </div>
    </div>

    <!-- Storage Info -->
    @include('advanced-file-manager::classic.partials._storage-info')
</section>
