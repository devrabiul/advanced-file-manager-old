document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("img.svg").forEach((img) => {
        const imgID = img.id;
        const imgClass = img.className;
        const imgURL = img.src;

        if (!imgURL) return;

        fetch(imgURL)
            .then((response) => response.text())
            .then((data) => {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(data, "image/svg+xml");
                const svg = xmlDoc.querySelector("svg");

                if (!svg) return;

                // Preserve ID and class
                if (imgID) svg.id = imgID;
                if (imgClass) svg.classList.add(...imgClass.split(" "), "replaced-svg");

                // Remove unnecessary attributes
                svg.removeAttribute("xmlns:a");

                // Ensure the viewBox is set
                if (!svg.hasAttribute("viewBox") && svg.hasAttribute("height") && svg.hasAttribute("width")) {
                    svg.setAttribute("viewBox", `0 0 ${svg.getAttribute("width")} ${svg.getAttribute("height")}`);
                }

                // Replace the image with the inline SVG
                img.replaceWith(svg);
            })
            .catch((error) => console.error("Error loading SVG:", error));
    });
});

$(document).ready(function() {
    let pond = null;

    // Modal controls
    $('#createBtn').click(function() {
        $('#createModal').css('display', 'flex');
        // Initialize FilePond when modal opens
        initializeFilePond();
    });

    $('.close-modal, .btn-cancel').click(function() {
        $('#createModal').css('display', 'none');
        // Destroy FilePond instance when modal closes
        if (pond) {
            pond.destroy();
            pond = null;
        }
    });

    $('#createModal').click(function(e) {
        if (e.target === this) {
            $(this).css('display', 'none');
            // Destroy FilePond instance when modal closes
            if (pond) {
                pond.destroy();
                pond = null;
            }
        }
    });

    function initializeFilePond() {
        // Initialize FilePond
        const inputElement = document.querySelector('input[type="file"].filepond');
        
        pond = FilePond.create(inputElement, {
            allowMultiple: true,
            instantUpload: false,
            allowRevert: true,
            maxFiles: 10,
            storeAsFile: true,
            labelIdle: 'Drag & drop files or <span class="filepond--label-action">Browse</span>',
            styleItemPanelAspectRatio: 0.5,
            imagePreviewHeight: 100,
            acceptedFileTypes: ['image/*', 'application/pdf', '.doc', '.docx', '.xls', '.xlsx'],
            onaddfile: (error, file) => {
                if (error) {
                    console.log('File add error:', error);
                    return;
                }
                console.log('File added:', file);
            }
        });
    }

    // Handle upload button
    $('.btn-upload').click(function() {
        if (!pond || pond.getFiles().length === 0) {
            alert('Please select files to upload');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true)
            .html('<i class="bi bi-arrow-repeat spin"></i> Processing...');

        // Here you would typically handle the files
        console.log('Files to process:', pond.getFiles());
        
        // For demo purposes, we'll just log the files and close the modal
        setTimeout(() => {
            if (pond) {
                pond.removeFiles();
            }
            $('#createModal').css('display', 'none');
            $btn.prop('disabled', false)
                .html('<i class="bi bi-cloud-arrow-up"></i> Upload');
            
            // Destroy FilePond instance
            if (pond) {
                pond.destroy();
                pond = null;
            }
        }, 1000);
    });

    // Sidebar Toggle Functionality
    $('#sidebarToggle').click(function(e) {
        e.stopPropagation(); // Prevent event bubbling
        $('.file-manager-sidebar-container').toggleClass('show');
        $('.sidebar-overlay').toggleClass('show');
        $('body').toggleClass('sidebar-open'); // Add this to prevent body scroll
    });

    // Close sidebar with mobile X button
    $('.sidebar-close-mobile').click(function(e) {
        e.stopPropagation(); // Prevent event bubbling
        closeSidebar();
    });

    // Close sidebar when clicking overlay
    $('.sidebar-overlay').click(function(e) {
        e.stopPropagation(); // Prevent event bubbling
        closeSidebar();
    });

    // Close sidebar when clicking outside
    $(document).click(function(e) {
        if ($(window).width() <= 768) {
            const sidebar = $('.file-manager-sidebar-container');
            const sidebarToggle = $('#sidebarToggle');
            
            if (!sidebar.is(e.target) && 
                sidebar.has(e.target).length === 0 && 
                !sidebarToggle.is(e.target) && 
                sidebarToggle.has(e.target).length === 0) {
                closeSidebar();
            }
        }
    });

    // Close sidebar with escape key
    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            closeSidebar();
        }
    });

    // Close sidebar on window resize if larger than mobile breakpoint
    $(window).resize(function() {
        if ($(window).width() > 768) {
            closeSidebar();
        }
    });

    // Helper function to close sidebar
    function closeSidebar() {
        $('.file-manager-sidebar-container').removeClass('show');
        $('.sidebar-overlay').removeClass('show');
        $('body').removeClass('sidebar-open');
    }

    // Prevent sidebar content clicks from closing the sidebar
    $('.file-manager-sidebar-container').click(function(e) {
        e.stopPropagation();
    });
});

// Calculate and set file manager height
function setFileManagerHeight() {
    const fileManagerMainContainer = document.querySelector('.file-manager-main-container');
    if (!fileManagerMainContainer) return;

    // Get the offset from top of the container
    const offsetTop = fileManagerMainContainer.offsetTop;
    // Calculate height (100vh - offset)
    const height = `calc(100vh - ${offsetTop}px)`;
    // Set the height
    fileManagerMainContainer.style.height = height;
}

// Run on load
document.addEventListener('DOMContentLoaded', setFileManagerHeight);
// Run on resize
window.addEventListener('resize', setFileManagerHeight);