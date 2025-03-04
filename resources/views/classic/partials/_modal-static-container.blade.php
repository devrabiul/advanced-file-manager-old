<!-- File Info Modal -->
<div class="modal-overlay modal-section-root" id="fileInfoModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>File Information</h5>
            <button class="close-modal"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
            <div id="file-info-content" data-route="{{ route('advanced-file-manager.folders.get-file-info') }}">
                Loading...
            </div>
        </div>
    </div>
</div>