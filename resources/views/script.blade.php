@if (Str::lower(config('advanced-file-manager.theme')) == 'modern')
    
@elseif (Str::lower(config('advanced-file-manager.theme')) == 'material')
    
@else

@endif


<!-- First load jQuery -->
<script src="{{ url('vendor/advanced-file-manager/assets/js/jquery-3.7.1.min.js') }}"></script>

<!-- Then load FilePond and its plugins -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

<!-- Finally load your custom scripts -->
<script src="{{ url('vendor/advanced-file-manager/assets/js/files.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/js/functions.js') }}"></script>
<script src="{{ url('vendor/advanced-file-manager/assets/js/ajax-function.js') }}"></script>
