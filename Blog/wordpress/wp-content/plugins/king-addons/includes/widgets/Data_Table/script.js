"use strict";
(function ($) {
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/king-addons-data-table.default', function ($scope) {
            // Add elementor handler
            elementorFrontend.elementsHandler.addHandler(
                elementorModules.frontend.handlers.Base.extend({
                    onInit: function onInit() {
                        const $scope        = this.$element;
                        const $tableContainer = $scope.find('.king-addons-table-inner-container');
                        const $tableBody     = $tableContainer.find('tbody');
                        const initialRows    = $tableBody.find('tr');
                        const paginationListItems = $scope.find('.king-addons-table-custom-pagination-list-item');
                        let beforeFilter = $tableBody.find('.king-addons-table-row');
                        let value = "";

                        // Initialize Perfect Scrollbar if needed
                        new PerfectScrollbar($tableContainer[0], {});

                        // Helper: get number of items per page (as integer)
                        const getItemsPerPage = () => +$tableContainer.attr('data-rows-per-page') || 0;

                        /**
                         * Updates the table with the filtered set of rows for a given page index (1-based).
                         * Also optionally checks the live-search value for resetting the table if empty.
                         */
                        const displayRowsForPage = (pageIndex, checkSearch) => {
                            // Hide table while updating
                            $tableBody.hide();

                            const itemsPerPage = getItemsPerPage();
                            // Filter rows that should appear on the given page
                            const newRows = initialRows.filter((i) => {
                                // Original code uses index++ inside filter; equivalently use (i+1) here
                                const rowNumber = i + 1;
                                return (
                                    rowNumber > itemsPerPage * (pageIndex - 1) &&
                                    rowNumber <= itemsPerPage * pageIndex
                                );
                            });

                            // Update the table with these new rows
                            $tableBody.html(newRows);

                            // If checkSearch is true and the current search is empty, revert back to beforeFilter
                            if (checkSearch && value === "") {
                                $tableBody.html(beforeFilter);
                            }

                            $tableBody.show();
                            // Refresh the 'beforeFilter' cache
                            beforeFilter = $tableBody.find('.king-addons-table-row');
                            // Remove highlight classes if any
                            beforeFilter
                                .find('.king-addons-table-tr-before-remove')
                                .removeClass('king-addons-table-tr-before-remove');

                            updateEntryInfo();
                        };

                        /**
                         * Removes 'active' class from all pagination items,
                         * sets the correct item as active, and adjusts their visibility.
                         */
                        const setActivePaginationItem = (pageIndex) => {
                            paginationListItems.removeClass('king-addons-active-pagination-item');
                            paginationListItems.each((i, el) => {
                                // Page indices in UI are basically i+1 if user numbered them from 1..n
                                if (i + 1 === pageIndex) {
                                    $(el).addClass('king-addons-active-pagination-item');
                                }
                            });
                            adjustPaginationList();
                        };

                        /**
                         * Called by previous/next clicks to move forward/back by `delta` pages.
                         * If the new page is out of range, does nothing.
                         */
                        const changePage = (delta) => {
                            const currentPage = +$scope.find('.king-addons-active-pagination-item').text() || 1;
                            const totalPages  = paginationListItems.length;
                            const newPage     = currentPage + delta;
                            if (newPage < 1 || newPage > totalPages) return;

                            setActivePaginationItem(newPage);
                            // For Prev/Next we do want to check if search is empty
                            displayRowsForPage(newPage, true);
                        };

                        /**
                         * Adjusts pagination list items to show/hide neighbors correctly.
                         */
                        const adjustPaginationList = () => {
                            const paginationIndex = $scope.find('.king-addons-active-pagination-item').index();
                            paginationListItems.each((i, el) => {
                                // Always display the first/last, and the 2 neighbors around current item
                                if (
                                    i === 0 ||
                                    i === paginationListItems.length - 1 ||
                                    (i <= paginationIndex && i >= paginationIndex - 2)
                                ) {
                                    $(el).css('display', 'flex');
                                } else {
                                    $(el).css('display', 'none');
                                }
                            });
                        };

                        /**
                         * Updates the "Showing X to Y of Z Entries" text (if enabled).
                         */
                        const updateEntryInfo = () => {
                            if ($tableContainer.attr('data-entry-info') !== 'yes') return;

                            const itemsPerPage = getItemsPerPage();
                            const entryPage = +$scope.find('.king-addons-active-pagination-item').text() || 1;
                            const rowsOnPage = $tableBody.find('tr').length;

                            const lastEntry = itemsPerPage * entryPage - (itemsPerPage - rowsOnPage);
                            const firstEntry = lastEntry - rowsOnPage + 1;

                            const info = `Showing ${firstEntry} to ${lastEntry} of ${initialRows.length} Entries.`;
                            $scope.find('.king-addons-entry-info').html(info);
                        };

                        /**
                         * Live search logic: filters rows by matching text in any table cell.
                         */
                        const initLiveSearch = () => {
                            $scope.find(".king-addons-table-live-search").on("keyup", function () {
                                value = this.value.toLowerCase().trim();

                                if (value !== "") {
                                    $scope.find('.king-addons-table-pagination-cont')
                                        .addClass('king-addons-hide-pagination-on-search');
                                } else {
                                    $scope.find('.king-addons-table-pagination-cont')
                                        .removeClass('king-addons-hide-pagination-on-search');
                                }

                                const filteredRows = [];
                                initialRows.each((i, row) => {
                                    const $row = $(row);
                                    $row.find("td").each((_, cell) => {
                                        const cellText = $(cell).text().toLowerCase().trim();
                                        // If found a match, add row to `filteredRows` once
                                        if (cellText.indexOf(value) !== -1) {
                                            filteredRows.push($row);
                                            return false; // break out of .each loop for this row
                                        }
                                    });
                                });

                                // If user cleared search, revert to all filtered items
                                $tableBody.html(value === "" ? beforeFilter : filteredRows);
                                updateEntryInfo();
                            });
                        };

                        /**
                         * Table sorting logic by clicking on <th>.
                         */
                        const initTableSorting = () => {
                            // Only if data-table-sorting is 'yes'
                            if ($tableContainer.attr('data-table-sorting') !== 'yes') return;

                            // Remove highlight class if user clicks outside any TH or highlight cell
                            $(window).on('click', (e) => {
                                const isTh        = $(e.target).hasClass('king-addons-table-th');
                                const inTh        = $(e.target).closest('.king-addons-table-th').length > 0;
                                const isActiveTd  = $(e.target).hasClass('king-addons-active-td-bg-color');
                                const inActiveTd  = $(e.target).closest('.king-addons-active-td-bg-color').length > 0;

                                if (!isTh && !inTh && !isActiveTd && !inActiveTd) {
                                    $scope.find('td.king-addons-active-td-bg-color').removeClass('king-addons-active-td-bg-color');
                                }
                            });

                            const getCellValue = (row, idx) =>
                                $(row).children('td').eq(idx).text();

                            const comparer = (idx) => (a, b) => {
                                const valA = getCellValue(a, idx);
                                const valB = getCellValue(b, idx);
                                // Numeric vs. text comparison
                                if ($.isNumeric(valA) && $.isNumeric(valB)) {
                                    return valA - valB;
                                }
                                return valA.toString().localeCompare(valB);
                            };

                            // Handle clicks on <th> for sorting
                            $scope.find('th').on('click', function () {
                                const $this = $(this);
                                const indexOfTr = $this.index();

                                // Highlight the column's cells
                                $scope.find('td').each(function () {
                                    $(this).toggleClass(
                                        'king-addons-active-td-bg-color',
                                        $(this).index() === indexOfTr
                                    );
                                });

                                // Reset the sorting icon on every TH
                                $scope.find('th .king-addons-sorting-icon').html('<i class="fas fa-sort" aria-hidden="true"></i>');

                                // Sort the rows
                                const table = $this.closest('table');
                                let rows = table.find('tr:gt(0)').toArray().sort(comparer(indexOfTr));

                                // Toggle ascending/descending
                                this.asc = !this.asc;
                                const isCustom = $scope.hasClass('king-addons-data-table-type-custom');

                                if ((isCustom && !this.asc) || (!isCustom && this.asc)) {
                                    // Sort reversed
                                    rows = rows.reverse();
                                    $this.find('.king-addons-sorting-icon').html(
                                        isCustom
                                            ? '<i class="fas fa-sort-down" aria-hidden="true"></i>'
                                            : '<i class="fas fa-sort-up" aria-hidden="true"></i>'
                                    );
                                } else {
                                    // Normal ascending
                                    $this.find('.king-addons-sorting-icon').html(
                                        isCustom
                                            ? '<i class="fas fa-sort-up" aria-hidden="true"></i>'
                                            : '<i class="fas fa-sort-down" aria-hidden="true"></i>'
                                    );
                                }

                                // Re-append sorted rows
                                for (let i = 0; i < rows.length; i++) {
                                    table.append(rows[i]);
                                }

                                // Remove leftover classes from "expanded" or appended rows
                                beforeFilter.find('.king-addons-table-tr-before-remove').each(function () {
                                    $(this)
                                        .closest('.king-addons-table-row')
                                        .next('.king-addons-table-appended-tr')
                                        .remove();
                                    $(this).removeClass('king-addons-table-tr-before-remove');
                                });
                            });
                        };

                        /**
                         * If data-row-pagination="yes", prepend an index column (#).
                         */
                        const initRowPagination = () => {
                            if ($tableContainer.attr('data-row-pagination') !== 'yes') return;

                            $scope.find('.king-addons-table-head-row').prepend(
                                '<th class="king-addons-table-th-pag" style="vertical-align: middle;">#</th>'
                            );
                            initialRows.each(function (index) {
                                $(this).prepend(
                                    `<td class="king-addons-table-td-pag" style="vertical-align: middle;">
                    <span style="vertical-align: middle;">${index + 1}</span>
                  </td>`
                                );
                            });
                        };

                        /**
                         * Export buttons logic (CSV/XLS).
                         */
                        const initTableExport = () => {
                            if (!$scope.find('.king-addons-table-export-button-cont').length) return;

                            const exportBtn = $scope.find('.king-addons-table-export-button-cont .king-addons-button');

                            const htmlToCSV = (filename, $view) => {
                                const rows = $view.find(".king-addons-table-row");
                                const data = [];
                                rows.each((_, row) => {
                                    const cols = row.querySelectorAll(".king-addons-table-text");
                                    const rowData = Array.from(cols).map(cell => cell.innerText);
                                    data.push(rowData.join(","));
                                });
                                downloadCSVFile(data.join("\n"), filename);
                            };

                            const downloadCSVFile = (csv, filename) => {
                                const csvFile = new Blob([csv], { type: "text/csv" });
                                const downloadLink = document.createElement("a");
                                downloadLink.download = filename;
                                downloadLink.href = window.URL.createObjectURL(csvFile);
                                downloadLink.style.display = "none";
                                document.body.appendChild(downloadLink);
                                downloadLink.click();
                            };

                            exportBtn.each(function () {
                                const $btn = $(this);
                                if ($btn.hasClass('king-addons-xls')) {
                                    // XLS export
                                    $btn.on('click', () => {
                                        const $table = $scope.find('table');
                                        TableToExcel.convert($table[0], {
                                            name: 'export.xlsx',
                                            sheet: { name: 'Sheet 1' },
                                        });
                                    });
                                } else if ($btn.hasClass('king-addons-csv')) {
                                    // CSV export
                                    $btn.on('click', () => {
                                        htmlToCSV("placeholder.csv", $scope.find('.king-addons-data-table'));
                                    });
                                }
                            });
                        };

                        /**
                         * Initializes custom pagination if enabled.
                         */
                        const initCustomPagination = () => {
                            if ($tableContainer.attr('data-custom-pagination') !== 'yes') return;

                            // Show only the first set of rows (page 1) initially
                            const itemsPerPage = getItemsPerPage();
                            const firstPageRows = initialRows.filter((i) => i < itemsPerPage);
                            $tableBody.html(firstPageRows);

                            adjustPaginationList();

                            // When clicking on a specific page number
                            paginationListItems.on('click', function () {
                                // Remove the old active, set the new active
                                paginationListItems.removeClass('king-addons-active-pagination-item');
                                $(this).addClass('king-addons-active-pagination-item');
                                adjustPaginationList();

                                const pageIndex = +$(this).text();
                                // For direct page clicks, we do NOT revert to beforeFilter if search is empty
                                displayRowsForPage(pageIndex, false);
                            });

                            // Handle prev/next clicks
                            $scope.find('.king-addons-table-prev-next').each(function () {
                                if ($(this).hasClass('king-addons-table-custom-pagination-prev')) {
                                    $(this).on('click', () => changePage(-1));
                                } else {
                                    $(this).on('click', () => changePage(1));
                                }
                            });
                        };

                        // ---------------------------------------------------
                        // Execute initializations
                        // ---------------------------------------------------
                        initCustomPagination();
                        initLiveSearch();
                        initTableSorting();
                        initRowPagination();
                        initTableExport();

                        // Finally, remove "hidden" class so the table becomes visible
                        $tableContainer.removeClass('king-addons-hide-table-before-arrange');
                        updateEntryInfo();
                    },
                }),
                { $element: $scope }
            );
        });
    });
})(jQuery);