document.getElementById('actionSmartFileSync').addEventListener('click', function(event) {
    const route = this.getAttribute('data-route');
    
    fetch(route)
        .then(response => response.json()) // Assuming the response is JSON
        .then(response => {
            const url = new URL(window.location.href);
            const targetFolder = url.searchParams.get('targetFolder') ?? '';
            const driver = url.searchParams.get('driver') ?? 'public';
            openFolderByAjax(targetFolder, driver);
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

function openFolderByAjax(targetFolder, driver) {
    const url = new URL(window.location.href);
    const route = document.querySelector('.file-manager-files-container').getAttribute('data-route');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('targetFolder', targetFolder);
    formData.append('driver', driver);

    url.searchParams.delete('search');
    url.searchParams.delete('page');

    const contentContainer = document.querySelector('.advanced-file-manager-content');

    // Show loader before sending the request
    loaderContainerRender('show');

    fetch(route, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Assuming the response is JSON
    .then(response => {
        // Hide and clear the content, then replace it with new HTML
        contentContainer.style.display = 'none';
        contentContainer.innerHTML = response.html;
        contentContainer.style.display = 'block';

        // Update the URL query parameters
        if (targetFolder) {
            url.searchParams.set('targetFolder', targetFolder);
        } else {
            url.searchParams.delete('targetFolder');
        }
        if (driver) {
            url.searchParams.set('driver', driver);
        } else {
            url.searchParams.delete('driver');
        }
        window.history.pushState({}, '', url);
    })
    .catch(error => {
        console.error('Error:', error);
    })
    .finally(() => {
        loaderContainerRender('hide');
    });
}

// Handle pagination clicks dynamically
document.querySelector('.file-manager-root-container').addEventListener('click', function(event) {
    if (event.target && event.target.matches('.pagination a')) {
        event.preventDefault();
        const page = new URL(event.target.href).searchParams.get('page');
        const url = new URL(window.location.href);
        const targetFolder = url.searchParams.get('targetFolder') || '';

        const formData = new FormData();
        formData.append('page', page);
        formData.append('targetFolder', targetFolder);

        const contentContainer = document.querySelector('.advanced-file-manager-content');

        // Show loader before sending the request
        loaderContainerRender('show');

        fetch(document.querySelector('.file-manager-files-container').getAttribute('data-route'), {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Assuming the response is JSON
        .then(response => {
            // Hide and update the content
            contentContainer.style.display = 'none';
            contentContainer.innerHTML = response.html;
            contentContainer.style.display = 'block';

            // Update the URL query parameters
            url.searchParams.set('page', page);
            window.history.pushState({}, '', url);
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            loaderContainerRender('hide');
        });
    }
});

// document.querySelector('.file-manager-root-container').addEventListener('click', function(event) {
//     if (event.target && event.target.matches('.pagination a')) {

//     }
// });

document.querySelector('.file-manager-root-container').addEventListener('click', function(event) {
    // Use closest to find the nearest parent element that matches the selector
    const targetElement = event.target.closest('.file-list-container-view-style');

    // Check if targetElement is found before proceeding
    if (!targetElement) {
        return; // Stop execution if no valid element was clicked
    }

    const viewStyle = targetElement.getAttribute('data-style');

    // Remove 'active' class from all elements with the class 'file-list-container-view-style'
    document.querySelectorAll('.file-list-container-view-style').forEach(function(item) {
        item.classList.remove('active');
    });

    // Add 'active' class to the clicked element
    targetElement.classList.add('active');

    // Update the content container class
    const contentContainer = document.querySelector('.file-manager-files-section');
    if (contentContainer) {
        contentContainer.classList.remove('grid-view', 'list-view');
        contentContainer.classList.add(viewStyle);
    }

    // Prepare data for the fetch request
    const formData = new FormData();
    formData.append('view_mode', viewStyle);

    // Ensure the element with the data-route attribute exists
    const fileListContainerView = document.querySelector('.file-list-container-view');
    if (!fileListContainerView) {
        console.error('Error: .file-list-container-view not found');
        return;
    }

    fetch(fileListContainerView.getAttribute('data-route'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        console.log(response);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


document.querySelector('.file-manager-root-container').addEventListener('input', function(event) {
    // Check if the input is from the search field
    if (event.target && event.target.matches('.file-search-input')) {
        event.preventDefault();

        const searchTerm = event.target.value; // Capture the search term from the input element
        const page = new URL(window.location.href).searchParams.get('page') ?? 1; // Get the current page or default to 1
        const url = new URL(window.location.href);
        const targetFolder = url.searchParams.get('targetFolder') ?? '/'; // Get targetFolder or default to root

        const formData = new FormData();
        formData.append('page', page);
        formData.append('targetFolder', targetFolder);
        formData.append('search', searchTerm); // Add the search term to the request

        // Update the URL with the page and search term
        url.searchParams.set('search', searchTerm);
        url.searchParams.delete('page');
        window.history.pushState({}, '', url);

        const contentContainer = document.querySelector('.file-manager-files-section');
        const searchInput = event.target; // Store the reference to the search input

        // Show loader before sending the request
        // loaderContainerRender('show');

        // Perform the fetch request
        fetch(document.querySelector('.file-manager-files-container').getAttribute('data-route'), {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Assuming the response is JSON
        .then(response => {
            // Hide the current content and update with the new content
            contentContainer.style.display = 'none';
            contentContainer.innerHTML = response?.html_files;
            contentContainer.style.display = 'block';

            // Re-set the search input value to the old one
            searchInput.value = searchTerm;
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Hide the loader after the request completes
            // loaderContainerRender('hide');
        });
    }
});

document.querySelector('.file-manager-root-container').addEventListener('change', function(event) {
    // Check if the input is from the search field
    if (event.target && event.target.matches('.quick-access-dropdown .custom-select')) {
        event.preventDefault();

        const driver = event.target.value;
        if (driver) {
            openFolderByAjax('', driver);
        }
    }
});


function loaderContainerRender(action) {
    const loaderContainer = document.querySelector(".advanced-file-manager-loader-container");
    const fileManagerFiles = document.querySelector(".file-manager-files-container");
    
    if (action === 'show') {
        fileManagerFiles.style.overflowY = 'hidden';
        loaderContainer.classList.remove('loader-container-hide');
    }
    
    if (action === 'hide') {
        setTimeout(() => {
            fileManagerFiles.style.overflowY = 'auto';
            loaderContainer.classList.add('loader-container-hide');
        }, 500);
    }
}
