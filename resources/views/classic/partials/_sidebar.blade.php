<?php 
    use Devrabiul\AdvancedFileManager\Services\S3FileManagerService;
?>

<section class="file-manager-sidebar-container">
    <button class="sidebar-close-mobile">
        <i class="bi bi-x-lg"></i>
    </button>

    <!-- Quick Access -->
    <h5 class="quick-access-section-title">Quick Access</h5>

    <?php
        $storageDrivers = config('advanced-file-manager.disks');
        $enabledDrivers = [];
        foreach (config('advanced-file-manager.disks') as $storageDiskKey => $storageDisk) {
            if (in_array($storageDiskKey, config('advanced-file-manager.enabled_drivers'))) {
                $enabledDrivers[$storageDiskKey] = $storageDisk;
            }
        }
    ?>
    <div class="quick-access-dropdown">
        <?php
            $selectedStorageDriver = request('driver') ?? config('advanced-file-manager.filesystem.default_disk');
        ?>
        <select class="custom-select">
            @foreach($enabledDrivers as $key => $driver)
                <option value="{{ $driver['driver'] }}" {{ $selectedStorageDriver == $driver['driver'] ? 'selected':''}}>
                    {{ Str::upper($key) }}
                </option>
            @endforeach
        </select>
        <i class="bi bi-chevron-down"></i>
    </div>

    <div class="quick-access-section">
        @include('advanced-file-manager::classic.partials._quick-access-items')
    </div>

    <!-- Storage Info -->
    @include('advanced-file-manager::classic.partials._storage-info')
</section>
