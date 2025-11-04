(function ($) {
    $(document).ready(function () {
        // Debug: check ccpo_ajax_object presence
        if (typeof ccpo_ajax_object === 'undefined') {
            console.error('ccpo_ajax_object is not defined');
            return;
        }

        // Log object

        // // Handle checkbox click for user_ordering
        $('#user_ordering_category').on('click', function () {
           
            try {
                let checkbox = $(this)[0];
                let category = checkbox.getAttribute('rel');
                let checked = checkbox.checked;

                $.post(ccpo_ajax_object.ajax_url, {
                    checked: checked,
                    category: category,
                    action: 'user_ordering',
                    nonce: ccpo_ajax_object.nonces.user_ordering
                });
            }catch(e) {
                console.log(e)
            }
        });

        // Make posts sortable and send new order
        $('#sortable').sortable({
            update: function () {
                var newOrder = $(this).sortable('toArray').toString();
                var category = $('#category').val();

                $.post(ccpo_ajax_object.ajax_url, {
                    order: newOrder,
                    category: category,
                    action: 'build_order',
                    nonce: ccpo_ajax_object.nonces.build_order || '' // Optional if not using nonce here
                });
            }
        });
    });

    // AJAX remove post from order
   
    //when post type change
    $('#post_type').on('change', function() {
        const postType = $(this).val();

        if (!postType) {
            $('#category').html('<option value="">Select Category / Taxonomy</option>');
            
            return;
        }
        $('#ccpo-sort-popup-btn').css('display','none');    

        $('#ccpo-post-list').html('<p>Please select taxonomy and then term to load posts...</p>');
        // Make AJAX request to fetch taxonomies
        $.post(ccpo_ajax_object.ajax_url, {
            action: 'ccpo_get_taxonomies',
            nonce: ccpo_ajax_object.nonces.get_taxonomies,
            post_type: postType
        }, function(response) {
            if (response.success && response.data) {
                let options = '<option value="">Select Category / Taxonomy</option>';
                response.data.forEach(function(tax) {
                    options += `<option value="${tax.name}">${tax.label}</option>`;
                });
                $('#taxonomy').html(options);
                $('#term').html('<option value="">Select Term</option>');
            } else {
                $('#taxonomy').html('<option value="">No taxonomies found</option>');
                $('#term').html('<option value="">Select Term</option>');
                console.error('Error:', response.data);
            }
        });
    });


})(jQuery);


//when taxonomy changes
jQuery(document).ready(function ($) {
	$('#taxonomy').on('change', function () {
		const taxonomy = $(this).val();
		$('#term').html('<option value="">Loading...</option>');

		if (!taxonomy) {
			$('#term').html('<option value="">Select Term</option>');
			return;
		}

		$.post(ccpo_ajax_object.ajax_url, {
			action: 'ccpo_get_terms',
			nonce: ccpo_ajax_object.nonces.get_terms,
			taxonomy: taxonomy
		}, function (response) {
			if (response.success && response.data.length > 0) {
				let options = '<option value="">Select Term</option>';
				$.each(response.data, function (index, term) {
					options += `<option value="${term.term_id}">${term.name}</option>`;
				});
				$('#term').html(options);
			} else {
				$('#term').html('<option value="">No terms found</option>');
			}
		});
	});
});


//when load button is clicked

jQuery(document).ready(function ($) {
	$('#load_posts_btn').on('click', function () {
		const postType = $('#post_type').val();
		const taxonomy = $('#taxonomy').val();
		const term = $('#term').val();

		$('#ccpo-post-list').html('<p>Loading posts...</p>');

		$.post(ccpo_ajax_object.ajax_url, {
			action: 'ccpo_load_posts',
			nonce: ccpo_ajax_object.nonces.load_posts,
			post_type: postType,
			taxonomy: taxonomy,
			term: term
		}, function (response) {
			if (response.success) {
				$('#ccpo-post-list').html(response.data.html);
                const checkbox = $('#user_ordering_category');
				if (response.data.ordering_enabled) {
					checkbox.prop('checked', true);
				} else {
					checkbox.prop('checked', false);
				}
                $('#ccpo-sort-popup-btn').css('display','block');
				// Make it sortable
				$('#sortable').sortable({
					update: function (event, ui) {
						const newOrder = $(this).sortable('toArray').toString();
						$.post(ccpo_ajax_object.ajax_url, {
							action: 'build_order',
							order: newOrder,
							category: term,
							nonce: ccpo_ajax_object.nonces.build_order
						});
					}
				});
			} else {
				$('#ccpo-post-list').html('<p>No posts found.</p>');
			}
		});
	});
});


jQuery(document).ready(function ($) {
	// When the term select dropdown changes
	$('#term').on('change', function () {
		const selectedTerm = $(this).val();
		$('#user_ordering_category').attr('rel', selectedTerm);
	});
});




jQuery(document).ready(function ($) {
    const $btn = $('#ccpo-sort-options-btn');
    const $widget = $('#ccpo-sort-widget');

    $btn.on('click', function () {
        $widget.toggle();
    });

    $('.ccpo-sort-btn').on('click', function () {
        const sortBy = $(this).data('sort');

        $.post(ccpo_ajax_object.ajax_url, {
            action: 'ccpo_sort_records',
            sort: sortBy,
            _ajax_nonce: ccpo_ajax_object.nonces.ccpo_sort_nonce
        }, function (response) {
            // if (response.success) {
            //     location.reload(); // or refresh only the sorted part
            // } else {
            //     alert("Error: " + response.data);
            // }
        });
    });

    $('.ccpo-sort-btn2').on('click', function () {
        const sortBy = $(this).data('sort');

        $.post(ccpo_ajax_object.ajax_url, {
            action: 'ccpo_sort_records',
            sort: sortBy,
            _ajax_nonce: ccpo_ajax_object.nonces.ccpo_sort_nonce
        }, function (response) {
            // if (response.success) {
            //     location.reload(); // or refresh only the sorted part
            // } else {
            //     alert("Error: " + response.data);
            // }
        });
    });

});




