<?php
 use Devrabiul\AdvancedFileManager\Services\FileManagerHelperService;
?>


@if(count($AllFilesInCurrentFolderFiles) > 0)
    <div class="file-manager-files-section {{ session('file_list_container_view_mode') ?? 'list-view' }}" id="filesContainer">
        @foreach ($AllFilesInCurrentFolderFiles as $key => $File)
            <div class="file-manager-files-item" data-filename="{{ strtolower($File['short_name']) }}">
                <div class="files-icon"
                     onclick="previewFile('{{ $File['type'] }}', '{{ $File['path'] }}', '{{ $File['short_name'] }}')">
                    @if ($File['type'] == 'image')
                        <img
                                src="{{ FileManagerHelperService::masterFileManagerStorage('storage/app/public/'.$File['path']) }}"
                                alt="" srcset="" class="image-file">
                    @elseif($File['type'] == 'video')
                        <img src="{{ url('vendor/advanced-file-manager/assets/images/video.svg') }}" alt="" srcset="">
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
                        <a href="{{ FileManagerHelperService::masterFileManagerStorage('storage/app/public/'.$File['path']) }}"
                           download>
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

<!-- Pagination Links -->
<div class="pagination-wrapper">
    {{ $AllFilesInCurrentFolderFiles->links() }}
</div>