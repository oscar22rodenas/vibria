<!-- Trigger Button -->
<button id="ccpo-sort-popup-btn" class="button button-primary hidden"><?php esc_html_e('Advance Sort Options', 'custom-category-post-order'); ?></button>

<!-- Modal Popup -->
<div id="ccpo-sort-modal" class="ccpo-modal">
  <div class="ccpo-modal-content">
    <span class="ccpo-close">&times;</span>
    <h3><?php esc_html_e('Advance Sort Options (pro)', 'custom-category-post-order'); ?></h3>
    <ul style="list-style:none; padding: 0;">
        <li><button class="ccpo-sort-btn" disabled data-sort="title_asc"><?php esc_html_e('Title A–Z', 'custom-category-post-order'); ?></button></li>
        <li><button class="ccpo-sort-btn" data-sort="title_desc"><?php esc_html_e('Title Z–A', 'custom-category-post-order'); ?></button></li>
        <li><button class="ccpo-sort-btn" data-sort="date_desc"><?php esc_html_e('Newest First', 'custom-category-post-order'); ?></button></li>
        <li><button class="ccpo-sort-btn" data-sort="date_asc"><?php esc_html_e('Oldest First', 'custom-category-post-order'); ?></button></li>
        <li>
            <button class="ccpo-sort-meta-field-btn" data-sort="metat_field"><?php esc_html_e('Meta Field', 'custom-category-post-order'); ?></button>
            <div class="meta-key-select">
                <label for="ccpo-meta-key-select"><strong><?php esc_html_e('Meta field', 'custom-category-post-order'); ?></strong></label>
                <select id="ccpo-meta-key-select" style="width:100%;margin-bottom:10px">
                    <option value=""><?php esc_html_e('Loading…', 'custom-category-post-order'); ?></option>  
                </select>
            </div>
        </li>
        <li><button class="ccpo-meta-sort-apply" data-sort="author_asc"><?php esc_html_e('Sort on Meta Field', 'custom-category-post-order'); ?></button></li>
    </ul>
  </div>
</div>
<style>
.ccpo-modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.ccpo-modal-content {
  background-color: #fff;
  margin: 10% auto;
  padding: 20px;
  border: 1px solid #ccc;
  width: 300px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

.ccpo-close {
  float: right;
  font-size: 24px;
  cursor: pointer;
}

.ccpo-sort-btn {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  background:rgb(136, 141, 143);
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

.ccpo-sort-meta-field-btn {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  background:rgb(136, 141, 143);
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

.ccpo-sort-meta-field-btn .hidden {
  display: none !important;
}

.ccpo-meta-sort-apply {
  display: none;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  background:rgb(136, 141, 143);
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}



.meta-key-select {
    display: none;
}

.hidden {
    display:none;
}
</style>
<script>
jQuery(document).ready(function($) {
    const $modal = $('#ccpo-sort-modal');
    const $openBtn = $('#ccpo-sort-popup-btn');
    const $closeBtn = $('.ccpo-close');

    $openBtn.on('click', function(e) {
        e.preventDefault();
        $modal.show();
        let post_type = $("#post_type").val();
        if(post_type !== 'home') {
            $(".ccpo-sort-meta-field-btn").removeClass('hidden')
        }else {
            $(".ccpo-sort-meta-field-btn").addClass('hidden')
        }
    });

    $closeBtn.on('click', function(e) {
        //if (e.target.id === 'ccpo-sort-modal') {
            e.preventDefault();
            $(".meta-key-select").css("display","none");
            $('#ccpo-meta-key-select').html('<option>-select-</option>');
            $(".ccpo-meta-sort-apply").css("display","none");
            $modal.hide();
        //}
    });

    $(window).on('click', function(e) {
         
        if (e.target.id === 'ccpo-sort-modal') {
            e.preventDefault();
            $modal.hide();
        }
    });


});

jQuery(function ($) {

	/* 2‑A – fetch meta keys whenever post‑type changes */
	function loadMetaKeys(pt) {
		$('#ccpo-meta-key-select').html('<option>Loading…</option>');
        let post_type = $("#post_type").val();

		$.post(ajaxurl, {
			action : 'ccpo_get_meta_keys',
			post_type : post_type,
			nonce : ccpo_ajax_object.nonces.ccpo_get_meta_key_nonce
		}, function (res) {
			if (!res.success) { alert(res.data); return; }
			let opts = res.data.keys.length
				? res.data.keys.map(k => `<option value="${k}" disabled>${k}</option>`).join('')
				: '<option value="">— none found —</option>';
			$('#ccpo-meta-key-select').html(opts);
		});
	}

	$('#ccpo-post-type-select').on('change', function () {
		loadMetaKeys(this.value);
	});

	

    $('.ccpo-sort-meta-field-btn').on('click', function (e) {
        e.preventDefault();
        let post_type = $("#post_type").val();
        if(post_type !== 'home') {
            $(".meta-key-select").css("display","block");
            $(".ccpo-meta-sort-apply").css("display","block");
            loadMetaKeys();
        }
    })
});


</script>
