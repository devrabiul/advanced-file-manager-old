<?php
    use Devrabiul\AdvancedFileManager\Services\FileManagerHelperService;
?>
<div class="files-header">
    <div class="files-header-title">
        <h5 class="folders-section-title">
            <span><i class="bi bi-grid-fill"></i></span>
            <span>Files</span>
        </h5>
        <p class="folders-section-subtitle">Browse and organize your files effortlessly</p>
    </div>

    <div class="files-header-end">
        <!-- Add search input -->
        <div class="search-container">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="file-search-input" placeholder="Search files..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="file-list-container-view" data-route="{{ route('advanced-file-manager.view-style-setup') }}">
            <div class="file-list-container-view-style {{ empty(session('file_list_container_view_mode')) || session('file_list_container_view_mode') == 'list-view' ? 'active' : '' }}" 
            data-style="list-view">
                <i class="bi bi-view-list"></i>
            </div>
            <div class="file-list-container-view-style {{ session('file_list_container_view_mode') == 'grid-view' ? 'active' : '' }}" 
            data-style="grid-view">
                <i class="bi bi-grid-fill"></i>
            </div>
        </div>
    </div>
</div>

@include("advanced-file-manager::classic.partials._files-list-content")

<!-- Image Viewer Modal -->
<div id="imageViewerModal" class="advanced-file-manager-modal">
    <div class="advanced-file-manager-modal-content">
        <span class="advanced-file-manager-modal-close">&times;</span>
        <img id="modalImage" src="" alt="">
    </div>
</div>

<!-- File Info Modal -->
<div id="fileInfoModal" class="advanced-file-manager-modal">
    <div class="advanced-file-manager-modal-content">
        <span class="advanced-file-manager-modal-close">&times;</span>
        <div class="advanced-file-manager-file-info-content">
            <h3 id="fileInfoTitle"></h3>
            <div id="fileInfoDetails"></div>
        </div>
    </div>
</div>