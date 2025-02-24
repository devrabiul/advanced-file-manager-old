@if(isset($folderArray))
    <div class="folders-section-header">
        <div>
            <h5 class="folders-section-title">
                <span><i class="bi bi-folder-fill"></i></span>
                <span>Folders</span>
            </h5>
            <p class="folders-section-subtitle">Manage your folders easily</p>
        </div>

        @if(request('targetFolder'))
            <div class="folder-breadcrumb mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none" onclick="openFolderByAjax('')">Root</a>
                        </li>
                        @php
                            $path = '';
                            $folders = explode('/', request('targetFolder'));
                        @endphp
                        @foreach($folders as $folder)
                            @php $path .= $folder . '/'; @endphp
                            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                @if(!$loop->last)
                                    <a href="#" class="text-decoration-none"
                                       onclick="openFolderByAjax('{{ rtrim($path, '/') }}')">
                                        {{ ucwords(str_replace(['-', '_'], ' ', $folder)) }}
                                    </a>
                                @else
                                    {{ ucwords(str_replace(['-', '_'], ' ', $folder)) }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        @endif
    </div>


    <div class="file-manager-folders-section">
        @if(request('targetFolder'))
            <div class="file-manager-folder-item" onclick="openFolderByAjax('')">
                <div class="folder-icon folder-icon-back">
                    {{--                <img src="{{ url('vendor/advanced-file-manager/assets/images/return-back.svg') }}" alt="" srcset=""--}}
                    {{--                     class="svg">--}}
                    <img src="{{ url('vendor/advanced-file-manager/assets/classic/images/classic-return-back.svg') }}" alt="" srcset=""
                         class="svg">
                </div>
                <div class="folder-title">
                    Back to Root
                </div>

                <div class="folder-info">
                    --
                </div>
            </div>
        @endif
        @foreach($folderArray as $folder)
            <div class="file-manager-folder-item"
                 onclick="openFolderByAjax('{{ $folder['path'] }}')"
                 role="button"
                 @if(isset($folder['isImage']) && $folder['isImage'])
                     data-preview-url="{{ $folder['url'] ?? '' }}"
                 data-is-image="true"
                 @endif
                 style="cursor: pointer;">
                <div class="folder-icon">
                    @if(isset($folder['isImage']) && $folder['isImage'])
                        <img src="{{ $folder['thumbnail'] ?? $folder['url'] }}" alt="{{ $folder['name'] }}"
                             class="folder-thumbnail">
                    @else
                        {{-- <img src="{{ url('vendor/advanced-file-manager/assets/images/folder.svg') }}" alt="" srcset=""
                             class="svg"> --}}
                        <img src="{{ url('vendor/advanced-file-manager/assets/classic/images/classic-folder.svg') }}" alt="" srcset=""
                             class="svg">
                    @endif
                </div>
                <div class="folder-title">
                    {{ ucwords(str_replace('-', ' ', str_replace('_', ' ', $folder['name']))) }}
                </div>

                <div class="folder-info">
                    {{ $folder['totalFiles'] }} {{ ('Files') }}
                    /
                    {{ $folder['size'] }}
                </div>

                <div class="files-option-element" onclick="event.stopPropagation()">
                    <button class="menu-dot" onclick="toggleDropdown(event, this)">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <div class="files-dropdown-menu">
                        <a href="#" onclick="openFolderByAjax('{{ $folder['path'] }}')">
                            <i class="bi bi-folder2-open"></i> Open
                        </a>
                        <a href="#" onclick="renameFolder('{{ $folder['path'] }}')">
                            <i class="bi bi-pencil"></i> Rename
                        </a>
                        <a href="#" onclick="copyFolder('{{ $folder['path'] }}')">
                            <i class="bi bi-files"></i> Copy
                        </a>
                        <a href="#" onclick="moveFolder('{{ $folder['path'] }}')">
                            <i class="bi bi-arrows-move"></i> Move
                        </a>
                        <a href="#" onclick="getFolderInfo('{{ $folder['path'] }}')" class="info-option">
                            <i class="bi bi-info-circle"></i> Get Info
                        </a>
                        <a href="#" onclick="deleteFolder('{{ $folder['path'] }}')" class="delete-option">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(!request('targetFolder') && count($folderArray) <= 0)
        <div class="file-manager-empty-state">
            <div class="empty-state-content">
                <i class="bi bi-folder-x"></i>
                <h3>No Folder Found</h3>
                <p>This folder is empty. Upload some Folder to get started!</p>
            </div>
        </div>
    @endif
@endif
