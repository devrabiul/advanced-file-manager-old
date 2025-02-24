<?php
 use Devrabiul\AdvancedFileManager\Services\FileManagerHelperService;
?>

@if(isset($AllFilesInCurrentFolderFiles) && count($AllFilesInCurrentFolderFiles) > 0)
    <div class="file-manager-files-section {{ session('file_list_container_view_mode') ?? 'list-view' }}" id="filesContainer">
        @foreach ($AllFilesInCurrentFolderFiles as $key => $File)
            <div class="file-manager-files-item" data-filename="{{ strtolower($File['short_name']) }}">
                <div class="files-icon">
                    @if ($File['type'] == 'image')
                        <a href="{{ $File['full_path'] }}" class="rixet-lightbox" data-gallery="gallery" data-title="{{ $File['name'] }} | {{ $File['size'] }}">
                            <img src="{{ $File['full_path'] }}" alt="" srcset="" class="image-file">
                        </a>
                    @elseif($File['type'] == 'video')
                        <a href="{{ $File['full_path'] }}" class="rixet-lightbox rixet-lightbox-video" data-gallery="gallery" data-title="{{ $File['name'] }} | {{ $File['size'] }}">
                            <video src="{{ $File['full_path'] }}" style="display:none;" preload="metadata" 
                            data-thumbnail="{{ url('vendor/advanced-file-manager/assets/images/image.svg') }}"></video>
                        </a>                                      
                    @else
                        <img src="{{ url('vendor/advanced-file-manager/assets/images/zip.svg') }}" alt="" srcset="">
                    @endif
                </div>
                <div class="files-information">
                    <div class="files-title">
                        {{ $File['short_name'] }}
                    </div>

                    <div class="files-info">
                        {{ ucwords($File['type'] ?? 'Others') }} / {{ $File['size'] }}
                        <br>
                        {{ $File['last_modified'] }}
                    </div>
                </div>

                <div class="files-option-element">
                    <button class="menu-dot" onclick="toggleDropdown(event, this)">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <div class="files-dropdown-menu">
                        <a href="#" onclick="openFile('{{ $File['path'] }}')">
                            <i class="bi bi-eye"></i> Open
                        </a>
                        <a href="#" onclick="renameFile('{{ $File['path'] }}')">
                            <i class="bi bi-pencil"></i> Rename
                        </a>
                        <a href="#" onclick="copyFile('{{ $File['path'] }}')">
                            <i class="bi bi-files"></i> Copy
                        </a>
                        <a href="#" onclick="moveFile('{{ $File['path'] }}')">
                            <i class="bi bi-arrows-move"></i> Move
                        </a>
                        <a href="{{ $File['full_path'] }}" download>
                            <i class="bi bi-download"></i> Download
                        </a>
                        <a href="#" onclick="getFileInfo('{{ $File['path'] }}')" class="info-option">
                            <i class="bi bi-info-circle"></i> Get Info
                        </a>
                        <a href="#" onclick="deleteFile('{{ $File['path'] }}')" class="delete-option">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="pagination-wrapper">
        {{ $AllFilesInCurrentFolderFiles->links() }}
    </div>
@else
    <div class="file-manager-empty-state">
        <div class="empty-state-content">
            <i class="bi bi-folder-x"></i>
            <h3>No Files Found</h3>
            <p>This folder is empty. Upload some files to get started!</p>
            <button class="upload-btn">
                <i class="bi bi-cloud-upload"></i>
                Upload Files
            </button>
        </div>
    </div>
@endif