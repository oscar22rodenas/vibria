(function ($) {
    "use strict";

    let KingAddonsPB_ModalPopups = {

        init: function () {
            if (!$('body').hasClass('elementor-editor-king-addons-pb-popups')) {
                return;
            }
            window.elementor.on('preview:loaded', KingAddonsPB_ModalPopups.onPreviewLoad);
            elementor.settings.page.model.on('change', KingAddonsPB_ModalPopups.onControlChange);
        },

        onPreviewLoad: function () {

            setTimeout(function () {
                $('#elementor-panel-footer-settings').trigger('click');
            }, 2000);

            KingAddonsPB_ModalPopups.settingsNotification();

            window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
                let popup = $scope.closest('.king-addons-pb-template-popup');
                KingAddonsPB_ModalPopups.fixPopupLayout(popup);
            });
        },

        onControlChange: function (model) {
            let iframe = document.getElementById('elementor-preview-iframe'),
                iframeContent = iframe.contentDocument || iframe.contentWindow.document;

            let popup = $('.king-addons-pb-template-popup', iframeContent);

            if (model.changed.hasOwnProperty('popup_display_as')) {
                if ('notification' === model.changed['popup_display_as']) {
                    popup.addClass('king-addons-pb-popup-notification');
                } else {
                    popup.removeClass('king-addons-pb-popup-notification');
                }
            }

            if (model.changed.hasOwnProperty('popup_animation')) {
                let popupContainer = popup.find('.king-addons-pb-popup-container');

                popupContainer.removeAttr('class');
                popupContainer.addClass('king-addons-pb-popup-container animated ' + model.changed['popup_animation']);
            }
        },

        fixPopupLayout: function (popup) {
            let settings = KingAddonsPB_ModalPopups.getDocumentSettings();

            if (!popup.find('.king-addons-pb-popup-container-inner').hasClass('ps')) {
                new PerfectScrollbar(popup.find('.king-addons-pb-popup-container-inner')[0], {
                    suppressScrollX: true
                });
            }

            if ('notification' === settings.popup_display_as) {
                popup.addClass('king-addons-pb-popup-notification');
            }
        },

        getDocumentSettings: function () {
            let documentSettings = {},
                settings = elementor.settings.page.model;

            jQuery.each(settings.getActiveControls(), function (controlKey) {
                documentSettings[controlKey] = settings.attributes[controlKey];
            });

            return documentSettings;
        },

        settingsNotification: function () {
            let closeTime = JSON.parse(localStorage.getItem('KingAddonsPopupEditorNotificationDate')) || 0;

            // 7 days
            if (closeTime + 604800000 >= Date.now()) {
                return;
            }

            const body = $('body');

            const isNewEditor = body.find('#elementor-editor-wrapper-v2').length > 0;

            const nHTML = `<div id="king-addons-editor-settings-notification" class="${isNewEditor ? 'king-addons-new-editor-bar' : ''}">
        <p>Click here to access <strong>Popup Settings</strong>.</p>
        <i class="eicon-close"></i>
        </div>`;

            const target = isNewEditor
                ? $('body .MuiBox-root button[value="document-settings"]')
                : body;

            target.append(nHTML).hide().fadeIn();

            $('#king-addons-editor-settings-notification .eicon-close').on('click', function () {
                $('#king-addons-editor-settings-notification').fadeOut();

                localStorage.setItem('KingAddonsPopupEditorNotificationDate', JSON.stringify(Date.now()));
            });
        },
    };

    $(window).on('elementor:init', KingAddonsPB_ModalPopups.init);
}(jQuery));