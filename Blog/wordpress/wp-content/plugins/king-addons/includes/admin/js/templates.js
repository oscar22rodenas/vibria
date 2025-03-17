jQuery(document).ready(function ($) {
    $(document).on('click', '.template-item', function () {
        let templateKey = $(this).data('template-key');
        let templatePlan = $(this).data('template-plan');
        let templateBtn = $('#install-template');
        templateBtn.attr('data-template-key', templateKey);
        templateBtn.attr('data-template-plan', templatePlan);
        let planActive = templateBtn.attr('data-plan-active');

        if(templatePlan === 'premium') {
            if (planActive === 'premium') {
                templateBtn.text('Import Premium Template');
            } else {
                templateBtn.text('Import Premium Template');
            }
        } else {
            templateBtn.text('Import Free Template');
        }

        $('#template-preview-iframe').attr('src', 'https://demo.kingaddons.com/' + templateKey);
        $('#template-preview-link').attr('href', 'https://demo.kingaddons.com/' + templateKey);
        $('#template-preview-popup').fadeIn();
    });

    // Listen for clicks on any button inside .preview-mode-switcher
    $('.preview-mode-switcher button').on('click', function() {
        // Remove the .active class from all buttons
        $('.preview-mode-switcher button').removeClass('active');
        // Add .active to the clicked button
        $(this).addClass('active');

        // Determine which mode was clicked: desktop, tablet, or mobile
        var mode = $(this).data('mode');

        // Select the preview iframe
        var $iframe = $('#template-preview-iframe');

        // Remove any previous mode classes
        $iframe.removeClass('preview-tablet preview-mobile');

        // If tablet, add .preview-tablet class
        if (mode === 'tablet') {
            $iframe.addClass('preview-tablet');
        }
        // If mobile, add .preview-mobile class
        else if (mode === 'mobile') {
            $iframe.addClass('preview-mobile');
        }
        // If desktop, no extra class (width:100% by default).
    });

    $(document).on('click', '#close-popup', function () {
        $('#template-preview-popup').fadeOut();
    });

    $(document).on('click', '#activate-license-btn', function () {
        $('#templates-catalog').addClass('kng-whole-overlay');
        $('#license-activating-popup').fadeIn();
    });

    $(document).on('click', '#close-license-activating-popup', function () {
        $('#templates-catalog').removeClass('kng-whole-overlay');
        $('#license-activating-popup').fadeOut();
    });

    $(document).on('click', '#close-premium-promo-popup', function () {
        $('#templates-catalog').removeClass('kng-whole-overlay');
        $('#premium-promo-popup').fadeOut();
    });

    $(document).on('click', '#close-installing-popup', function () {
        $('#templates-catalog').removeClass('kng-whole-overlay');
        $('#template-installing-popup').fadeOut();
        $('#go-to-imported-page').fadeOut();
        $('#close-installing-popup').fadeOut();
        document.getElementById('final_response').innerText = '';
        document.getElementById('progress').innerText = '';
        document.getElementById('progress-bar').style.width = '0%';
        document.getElementById('progress-bar').innerText = '0%';
    });

    $(document).on('click', '#install-template', function () {

        $('#templates-catalog').addClass('kng-whole-overlay');
        $('#template-preview-popup').fadeOut();

        let templateBtn = $('#install-template');
        let templateKey = templateBtn.attr('data-template-key');
        let templatePlan = templateBtn.attr('data-template-plan');
        let planActive = templateBtn.attr('data-plan-active');

        let api_request_url;
        let installId;

        if (planActive === 'premium') {
            if (templatePlan === 'premium') {
                api_request_url = 'https://api.kingaddons.com/get-template.php';
                installId = window.kingAddons.installId;
            } else {
                api_request_url = 'https://api.kingaddons.com/get-template-free.php';
                installId = 0;
            }
        } else {
            if (templatePlan === 'free') {
                api_request_url = 'https://api.kingaddons.com/get-template-free.php';
                installId = 0;
            } else {
                $('#premium-promo-popup').fadeIn();
                return;
            }
        }

        fetch(api_request_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                key: templateKey,
                install: installId,
            }),
        })
            .then(response => {
                if (!response.ok) {
                    // The server returned a 4xx or 5xx, read the text and throw an error
                    return response.text().then(html => {
                        console.error('Server error:\n' + html);
                        throw new Error('Server error (not JSON).');
                    });
                }
                // If ok, parse JSON
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    console.error('API error:', data);
                }
                if (data.success) {
                    $('#template-installing-popup').fadeIn();
                    doImport(data);
                } else {
                    $('#premium-promo-popup').fadeIn();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Connection error: ' + error);
            });
    });

    function doImport(data) {
        document.getElementById('progress').innerText = 'Starting import...';
        document.getElementById('image-list').innerHTML = '';
        document.getElementById('progress').innerText = 'Import initialized.';

        let images = data.landing.images;

        if (images && images.length > 0) {
            let imageList = images.map(function (img) {
                return '<li data-id="' + img.id + '" data-url="' + img.url + '">' + img.url + ' (ID: ' + img.id + ')</li>';
            }).join('');

            document.getElementById('image-list').innerHTML = '<ul>' + imageList + '</ul>';

            startImport(data.landing);
        } else {
            document.getElementById('final_response').innerText = 'No images to import.';
        }
    }

    function startImport(initial_data) {
        fetch(kingAddonsData.ajaxUrl, {
            method: 'POST',
            body: new URLSearchParams({
                action: 'import_elementor_page_with_images',
                data: JSON.stringify(initial_data),
            })
        })
            .then(response => {
                if (!response.ok) {
                    // The server returned a 4xx or 5xx, read the text and throw an error
                    return response.text().then(html => {
                        console.error('Server error:\n' + html);
                        throw new Error('Server error:\n' + html);
                    });
                }
                // If ok, parse JSON
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    processNextImage()
                } else {
                    alert('Error getting template: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
    }

    function processNextImage() {
        const maxRetries = 3;
        let currentRetry = 0;
        let retryTimeout = 1000;

        function attemptProcessImage() {
            fetch(kingAddonsData.ajaxUrl, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'process_import_images',
                })
            })
                .then(response => {
                    if (!response.ok) {
                        // The server returned a 4xx or 5xx, read the text and throw an error
                        return response.text().then(html => {
                            console.error('Server error:\n' + html);
                            throw new Error('Server error:\n' + html);
                        });
                    }
                    // If ok, parse JSON
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.data.progress !== undefined) {

                            let progressBar = document.getElementById('progress-bar');
                            let progress = data.data.progress;
                            progressBar.style.width = progress + '%';
                            progressBar.innerText = progress + '%';

                            document.getElementById('progress').innerText = data.data.message;

                            let imageUrlElement = document.querySelector('li[data-url="' + data.data.image_url + '"]');
                            if (imageUrlElement) {
                                imageUrlElement.innerHTML += ' - done';
                            }
                            currentRetry = 0;
                            retryTimeout = 1000;
                            processNextImage();
                        } else {
                            document.getElementById('final_response').innerText = data.data.message;
                            document.getElementById('progress').innerText = 'Import completed.';
                            document.getElementById('progress-bar').style.width = '100%';
                            document.getElementById('progress-bar').innerText = '100%';
                            let goToPage = $('#go-to-imported-page');
                            goToPage.attr('href', data.data.page_url);
                            goToPage.fadeIn();
                            $('#close-installing-popup').fadeIn();
                        }
                    } else {
                        console.error('Process image issue:', data);
                    }
                })
                .catch(error => {
                    console.error('Catch Error:', error);
                    if (currentRetry < maxRetries) {
                        currentRetry++;
                        retryTimeout *= 2;
                        setTimeout(attemptProcessImage, retryTimeout);
                    } else {
                        let imageUrlElement = document.querySelector('li[data-url="unknown"]');
                        if (imageUrlElement) {
                            imageUrlElement.innerHTML += ' - SKIPPED';
                        }
                        console.error('Error:', error);
                        document.getElementById('final_response').innerText = 'Error: ' + error.message;
                        currentRetry = 0;
                        retryTimeout = 1000;
                        processNextImage();
                    }
                });
        }

        attemptProcessImage();
    }

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('paged=')[1];
        filterTemplates(page);
    });

    let templateSearch = $('#template-search');
    let templateCategory = $('#template-category');
    let templateCollection = $('#template-collection');

    function filterTemplates(page = 1) {
        let searchQuery = templateSearch.val().toLowerCase();
        let selectedCategory = templateCategory.val();
        let selectedCollection = templateCollection.val();
        let selectedTags = [];

        $('#template-tags input:checked').each(function () {
            selectedTags.push($(this).val());
        });

        $.ajax({
            url: kingAddonsData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'filter_templates',
                s: searchQuery,
                category: selectedCategory,
                collection: selectedCollection,
                tags: selectedTags.join(','),
                paged: page
            },
            success: function (response) {
                if (response.success) {

                    // Scroll to top after the new content is loaded with admin bar offset
                    const scrollOffset = $('#king-addons-templates-top').offset().top - 32; // Subtract 32px for admin bar
                    $('html, body').animate({scrollTop: scrollOffset}, 0);

                    $('.templates-grid').html(response.data.grid_html);
                    $('.pagination').html(response.data.pagination_html);
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    }

    templateSearch.on('keyup', function () {
        templateCategory.val('');
        templateCollection.val('');
        $('#template-tags input:checked').prop('checked', false);
        filterTemplates();
    });

    templateCategory.on('change', function () {
        templateSearch.val('');
        templateCollection.val('');
        $('#template-tags input:checked').prop('checked', false);
        filterTemplates();
    });

    templateCollection.on('change', function () {
        templateSearch.val('');
        templateCategory.val('');
        $('#template-tags input:checked').prop('checked', false);
        filterTemplates();
    });

    // TODO: For now the tags feature works as selector of sub-subcategories.
    // $('#template-tags input').on('change', filterTemplates);
    $('#template-tags input').on('change', function() {
        templateSearch.val('');
        templateCategory.val('');
        templateCollection.val('');
        if ($(this).is(':checked')) {
            $('#template-tags input').not(this).prop('checked', false);
        }
        filterTemplates();
    });

    $('#reset-filters').on('click', function () {
        templateSearch.val('');
        templateCategory.val('');
        templateCollection.val('');
        $('#template-tags input:checked').prop('checked', false);
        filterTemplates();
    });
});
