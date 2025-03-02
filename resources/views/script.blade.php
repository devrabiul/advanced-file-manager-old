@if (Str::lower(config('advanced-file-manager.theme')) == 'modern')
    
@elseif (Str::lower(config('advanced-file-manager.theme')) == 'material')
    
@else

@endif


<!-- First load jQuery -->
<script src="{{ url('vendor/advanced-file-manager/assets/js/jquery-3.7.1.min.js') }}"></script>

<!-- Finally load your custom scripts -->
<script src="{{ url('vendor/advanced-file-manager/assets/js/files.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/js/functions.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/js/ajax-function.js') }}"></script>

<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond.min.js') }}"></script>
<!-- FilePond Plugins -->
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond-plugin-file-poster.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond-plugin-file-rename.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond-plugin-image-preview.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond-plugin-image-edit.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/filepond/filepond.init.js') }}"></script>

<script src="{{ url('vendor/advanced-file-manager/assets/libs/rixetlightbox/rixetlightbox.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/libs/rixetlightbox/rixetlightbox-init.js') }}"></script>
