@if (isset($driverCredentialStatus) && !$driverCredentialStatus)
    @include('advanced-file-manager::classic.partials._driver-error')
@else
    @include('advanced-file-manager::classic.partials._folder-list')

    @include('advanced-file-manager::classic.partials._files-list')
@endif