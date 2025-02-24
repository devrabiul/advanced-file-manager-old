<style>
    .storage-error-container {
        text-align: center;
        padding: 20px;
        background-color: #fff3f3;
        border: 1px solid #ffcdd2;
        border-radius: 4px;
    }
    .storage-error-icon {
        color: #d32f2f;
        font-size: 48px;
        margin-bottom: 15px;
    }
    .storage-error-heading {
        color: #d32f2f;
        margin-bottom: 10px;
    }
    .storage-error-text {
        color: #666;
        margin-bottom: 15px;
    }
    .storage-error-list {
        list-style: none;
        padding: 0;
        color: #666;
    }
    .storage-error-list-item {
        margin-bottom: 5px;
    }
</style>

<div class="storage-error-container">
    <div class="storage-error-icon">
        <i class="bi bi-exclamation-octagon"></i>
    </div>
    {{-- <div>
        <img src="{{ url('vendor/advanced-file-manager/assets/images/storage-connection-error.png') }}" alt="" srcset="">
    </div> --}}
    <h3 class="storage-error-heading">Storage Connection Error</h3>
    <p class="storage-error-text">
        Unable to connect to S3 storage. This could be due to:
    </p>
    <ul class="storage-error-list">
        <li class="storage-error-list-item">• Invalid credentials</li>
        <li class="storage-error-list-item">• Connection timeout</li>
        <li class="storage-error-list-item">• Service unavailable</li>
    </ul>
    <p class="storage-error-text">
        Please check your S3 configuration and try again.
    </p>
</div>