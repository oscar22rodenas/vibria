"use strict";
(function ($) {
    $(window).on('elementor:init', () => {
        elementor.hooks.addAction('panel/open_editor/widget/king-addons-data-table', (panel, model, view) => {
            elementor.channels.editor.on('king-addons-data-table-export', () => {

                // Retrieve all table rows
                const rows = view.$el.find('.king-addons-data-table .king-addons-table-row');
                const data = [];

                // Iterate over each row and collect text from relevant cells
                rows.each((_, row) => {
                    const cols = row.querySelectorAll('.king-addons-table-text');
                    const rowData = Array.from(cols, col => col.innerText).join(',');
                    data.push(rowData);
                });

                // Construct CSV content from array of rows
                const csvContent = data.join('\n');

                // Create a Blob from CSV data and generate a download link
                const blob = new Blob([csvContent], { type: 'text/csv' });
                const downloadLink = document.createElement('a');
                downloadLink.download = 'placeholder.csv';
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.style.display = 'none';

                // Append link to DOM, trigger download, and then remove the link
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                URL.revokeObjectURL(downloadLink.href);
            });
        });
    });
})(jQuery);