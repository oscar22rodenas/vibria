"use strict";
(function($) {
    function createProTooltip() {
        const tooltip = document.createElement('div');
        tooltip.className = 'king-addons-pro-tooltip';
        tooltip.innerHTML = `
            <p>This is available in <strong><a href="https://kingaddons.com/pricing/?utm_source=kng-module-upgrade-pro&utm_medium=plugin&utm_campaign=kng" target="_blank">King Addons PRO</a></strong> version</p>
        `;
        return tooltip;
    }

    function appendProTooltip(controlElement) {
        // Only append if a tooltip doesn't already exist
        if (!controlElement.querySelector('.king-addons-pro-tooltip')) {
            controlElement.appendChild(createProTooltip());
        }
    }

    $(window).on('elementor:init', function() {
        const panelEl = document.getElementById('elementor-panel');

        // Watch for newly-added .king-addons-pro-control elements
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {

                            // Find .king-addons-pro-control .elementor-control-content in newly added nodes
                            const proControls = node.querySelectorAll('.king-addons-pro-control .elementor-control-content');
                            proControls.forEach(appendProTooltip);
                        }
                    });
                }
            });
        });

        // Start observing if the elementor panel is found
        if (panelEl) {
            observer.observe(panelEl, {
                childList: true,
                subtree: true
            });
        }

        // Initial pass for any controls already in the DOM
        const initialProControls = document.querySelectorAll('.king-addons-pro-control .elementor-control-content');
        initialProControls.forEach(appendProTooltip);
    });
}(jQuery));