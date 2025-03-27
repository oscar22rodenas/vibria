// noinspection JSUnresolvedReference

"use strict";
(function ($) {
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-charts.default",
            function ($scope) {
                const BaseHandler = elementorModules.frontend.handlers.Base.extend({
                    onInit() {
                        const $container = this.$element.find(".king-addons-charts-container");
                        const chartSettings = JSON.parse($container.attr("data-settings"));
                        const labels = chartSettings.chart_labels;
                        const customDatasets = chartSettings.chart_datasets
                            ? JSON.parse(chartSettings.chart_datasets)
                            : "";

                        // Helper for common grid/ticks setup
                        const axisConfig = (axis, gridColor, lineWidth, dashed = false) => ({
                            display: chartSettings[`display_${axis}_axis`],
                            drawBorder: chartSettings[`display_${axis}_axis`],
                            drawOnChartArea: chartSettings[`display_${axis}_axis`],
                            drawTicks: chartSettings[`display_${axis}_axis`],
                            color: gridColor,
                            borderDash: dashed
                                ? [chartSettings[`border_dash_length_${axis[0]}`], chartSettings[`border_dash_spacing_${axis[0]}`]]
                                : undefined,
                            borderDashOffset: dashed
                                ? chartSettings[`border_dash_offset_${axis[0]}`]
                                : undefined,
                            lineWidth: lineWidth,
                        });

                        // Reusable tick/font setup
                        const tickConfig = (axis) => ({
                            stepSize: chartSettings[`${axis}_step_size`],
                            display: chartSettings[`display_${axis}_ticks`],
                            padding: chartSettings[`ticks_padding_${axis}`],
                            autoSkip: false,
                            maxRotation: chartSettings[`rotation_${axis}`],
                            minRotation: chartSettings[`rotation_${axis}`],
                            color: chartSettings[`ticks_color_${axis}`],
                            font: {
                                size: chartSettings[`ticks_font_size_${axis}`],
                                family: chartSettings[`ticks_font_family_${axis}`],
                                style: chartSettings[`ticks_font_style_${axis}`],
                                weight: chartSettings[`ticks_font_weight_${axis}`],
                            },
                        });

                        // Configure scales based on chart type
                        const scales =
                            ["bar", "bar_horizontal", "line"].includes(chartSettings.chart_type)
                                ? {
                                    x: {
                                        reverse: chartSettings.reverse_x === "yes",
                                        stacked: chartSettings.stacked_bar_chart === "yes",
                                        type:
                                            chartSettings.chart_type === "bar_horizontal"
                                                ? chartSettings.data_type
                                                : "category",
                                        min: chartSettings.min_value || null,
                                        max: chartSettings.max_value || null,
                                        grid: axisConfig("x", chartSettings.axis_grid_line_color_x, chartSettings.grid_line_width_x, true),
                                        title: {
                                            display: chartSettings.display_x_axis_title,
                                            text: chartSettings.x_axis_title,
                                            color: chartSettings.axis_title_color_x,
                                            font: {
                                                size: chartSettings.axis_title_font_size_x,
                                                family: chartSettings.axis_title_font_family_x,
                                                style: chartSettings.axis_title_font_style_x,
                                                weight: chartSettings.axis_title_font_weight_x,
                                            },
                                        },
                                        ticks: {
                                            stepSize:
                                                chartSettings.chart_type === "bar_horizontal"
                                                    ? chartSettings.x_step_size
                                                    : "",
                                            ...tickConfig("x"),
                                        },
                                    },
                                    y: {
                                        reverse: chartSettings.reverse_y === "yes",
                                        stacked: chartSettings.stacked_bar_chart === "yes",
                                        type:
                                            ["bar", "line"].includes(chartSettings.chart_type)
                                                ? chartSettings.data_type
                                                : "category",
                                        min: chartSettings.min_value || null,
                                        max: chartSettings.max_value || null,
                                        grid: axisConfig("y", chartSettings.axis_grid_line_color_y, chartSettings.grid_line_width_y, true),
                                        title: {
                                            display: chartSettings.display_y_axis_title,
                                            text: chartSettings.y_axis_title,
                                            color: chartSettings.axis_title_color_y,
                                            font: {
                                                size: chartSettings.axis_title_font_size_y,
                                                family: chartSettings.axis_title_font_family_y,
                                                style: chartSettings.axis_title_font_style_y,
                                                weight: chartSettings.axis_title_font_weight_y,
                                            },
                                        },
                                        ticks: tickConfig("y"),
                                    },
                                }
                                : {};

                        // Global options
                        const globalOptions = {
                            responsive: true,
                            showLine: chartSettings.show_lines,
                            animation: chartSettings.chart_animation === "yes",
                            animations: {
                                tension: {
                                    duration: chartSettings.chart_animation_duration,
                                    easing: chartSettings.animation_transition_type,
                                    from: 1,
                                    to: 0,
                                    loop: chartSettings.chart_animation_loop === "yes",
                                },
                            },
                            events: [
                                chartSettings.trigger_tooltip_on,
                                chartSettings.exclude_dataset_on_click === "yes" ? "click" : "",
                            ],
                            interaction: {
                                mode: chartSettings.chart_interaction_mode || "nearest",
                            },
                            elements: {
                                point: {
                                    radius:
                                        chartSettings.line_dots === "yes"
                                            ? window.innerWidth >= 768
                                                ? chartSettings.line_dots_radius
                                                : chartSettings.line_dots_radius_mobile
                                            : 0,
                                },
                            },
                            scales,
                            plugins: {
                                datalabels: {
                                    color: chartSettings.inner_datalabels_color,
                                    font: {
                                        size: chartSettings.inner_datalabels_font_size,
                                        style: chartSettings.inner_datalabels_font_style,
                                        weight: chartSettings.inner_datalabels_font_weight,
                                    },
                                },
                                legend: {
                                    onHover: (event) => {
                                        event.native.target.style.cursor = "pointer";
                                    },
                                    onLeave: (event) => {
                                        event.native.target.style.cursor = "default";
                                    },
                                    onClick: (e, legendItem, legend) => {
                                        if (
                                            ["bar", "bar_horizontal", "line"].includes(chartSettings.chart_type) ||
                                            chartSettings.chart_type === "radar"
                                        ) {
                                            const ci = legend.chart;
                                            const index = legendItem.datasetIndex;
                                            legendItem.hidden
                                                ? (ci.show(index), (legendItem.hidden = false))
                                                : (ci.hide(index), (legendItem.hidden = true));
                                        }
                                    },
                                    reverse: chartSettings.reverse_legend === "yes",
                                    display: chartSettings.show_chart_legend === "yes",
                                    position: chartSettings.legend_position || "top",
                                    align: chartSettings.legend_align || "center",
                                    labels: {
                                        usePointStyle: chartSettings.legend_shape === "point",
                                        padding: chartSettings.legend_padding,
                                        boxWidth: chartSettings.legend_box_width,
                                        boxHeight: chartSettings.legend_font_size,
                                        color: chartSettings.legend_text_color,
                                        font: {
                                            family: chartSettings.legend_font_family,
                                            size: chartSettings.legend_font_size,
                                            style: chartSettings.legend_font_style,
                                            weight: chartSettings.legend_font_weight,
                                        },
                                    },
                                },
                                title: {
                                    display: chartSettings.show_chart_title === "yes",
                                    text: chartSettings.chart_title,
                                    align: chartSettings.chart_title_align || "center",
                                    position: chartSettings.chart_title_position || "top",
                                    color: chartSettings.chart_title_color || "#000",
                                    padding: chartSettings.title_padding,
                                    font: {
                                        family: chartSettings.title_font_family,
                                        size: chartSettings.title_font_size,
                                        style: chartSettings.title_font_style,
                                        weight: chartSettings.title_font_weight,
                                    },
                                },
                                tooltip: {
                                    callbacks: {
                                        footer(tooltipItems) {
                                            let sum = 0;
                                            tooltipItems.forEach((item) => {
                                                sum += item.parsed.y;
                                            });
                                            if (chartSettings.chart_type === "bar_horizontal") {
                                                sum = 0;
                                                tooltipItems.forEach((item) => {
                                                    sum += item.parsed.x;
                                                });
                                            }
                                            if (
                                                ["radar", "pie", "doughnut", "polarArea"].includes(
                                                    chartSettings.chart_type
                                                )
                                            ) {
                                                return false;
                                            }
                                            return "Sum: " + sum;
                                        },
                                    },
                                    enabled: chartSettings.show_chart_tooltip === "yes",
                                    position: chartSettings.tooltip_position || "nearest",
                                    padding: chartSettings.tooltip_padding || 10,
                                    caretSize:
                                        window.innerWidth >= 768
                                            ? chartSettings.tooltip_caret_size
                                            : chartSettings.chart_tooltip_caret_size_mobile,
                                    backgroundColor:
                                        chartSettings.chart_tooltip_bg_color || "rbga(0, 0, 0, 0.2)",
                                    titleColor: chartSettings.chart_tooltip_title_color || "#FFF",
                                    titleFont: {
                                        family: chartSettings.chart_tooltip_title_font,
                                        size: chartSettings.chart_tooltip_title_font_size,
                                    },
                                    titleAlign: chartSettings.chart_tooltip_title_align,
                                    titleMarginBottom: chartSettings.chart_tooltip_title_margin_bottom,
                                    bodyColor: chartSettings.chart_tooltip_item_color || "#FFF",
                                    bodyFont: {
                                        family: chartSettings.chart_tooltip_item_font,
                                        size: chartSettings.chart_tooltip_item_font_size,
                                    },
                                    bodyAlign: chartSettings.chart_tooltip_item_align,
                                    bodySpacing: chartSettings.chart_tooltip_item_spacing,
                                    boxPadding: 3,
                                },
                            },
                        };

                        // Adjust scales for non-standard chart types
                        if (
                            !["bar", "bar_horizontal", "line"].includes(chartSettings.chart_type) &&
                            !["doughnut", "pie", "polarArea"].includes(chartSettings.chart_type)
                        ) {
                            globalOptions.scales = {
                                r: {
                                    angleLines: { color: chartSettings.angle_lines_color },
                                    pointLabels: {
                                        color: chartSettings.point_labels_color_r,
                                        font: {
                                            size: chartSettings.point_labels_font_size_r,
                                            family: chartSettings.point_labels_font_family_r,
                                            style: chartSettings.point_labels_font_style_r,
                                            weight: chartSettings.point_labels_font_weight_r,
                                        },
                                    },
                                    ticks: {
                                        stepSize: chartSettings.r_step_size,
                                        display: chartSettings.display_r_ticks,
                                        backdropColor: chartSettings.axis_labels_bg_color,
                                        backdropPadding: +chartSettings.axis_labels_padding,
                                        color: chartSettings.axis_labels_color,
                                    },
                                    grid: axisConfig("r", chartSettings.axis_grid_line_color_r, chartSettings.grid_line_width_r, true),
                                },
                            };
                        }

                        // Chart creation
                        let config;
                        if (chartSettings.data_source === "custom") {
                            const data = {
                                labels,
                                datasets: JSON.parse(chartSettings.chart_datasets),
                            };
                            config = {
                                type:
                                    chartSettings.chart_type === "bar_horizontal"
                                        ? "bar"
                                        : chartSettings.chart_type,
                                data,
                                options: globalOptions,
                                plugins: chartSettings.inner_datalabels ? [ChartDataLabels] : [],
                            };
                            if (chartSettings.chart_type === "bar_horizontal") {
                                config.options.indexAxis = "y";
                            }
                            if (
                                chartSettings.tooltips_percent ||
                                ["pie", "doughnut", "polarArea"].includes(chartSettings.chart_type)
                            ) {
                                config.options.plugins.tooltip.callbacks.label = (data) => {
                                    let prefix = data.dataset.label + ": ";
                                    if (["pie", "doughnut", "polarArea"].includes(chartSettings.chart_type)) {
                                        prefix = `${data.label} (${data.dataset.label}): `;
                                    }
                                    const total = data.dataset.data.reduce(
                                        (sum, val) => parseFloat(sum) + parseFloat(val),
                                        0
                                    );
                                    const percentage = ((data.formattedValue / total) * 100).toPrecision(3);
                                    return prefix + (chartSettings.tooltips_percent ? percentage + "%" : data.formattedValue);
                                };
                            }
                            new Chart($scope.find(".king-addons-chart"), config);
                        } else {
                            // Handle CSV data source or errors
                            if (chartSettings.url && (["bar", "bar_horizontal", "line", "radar"].includes(chartSettings.chart_type))) {
                                $.ajax({
                                    url: chartSettings.url,
                                    type: "GET",
                                    success(res) {
                                        $scope.find(".king-addons-rotating-plane").remove();
                                        renderCSVChart(res);
                                    },
                                    error(err) {
                                        console.error(err);
                                    },
                                });
                            } else {
                                $scope.find(".king-addons-rotating-plane").remove();
                                const errorMsg =
                                    !chartSettings.url
                                        ? '<p class="king-addons-charts-error-notice">Provide a csv file or remote URL</p>'
                                        : '<p class="king-addons-charts-error-notice">doughnut, pie and polarArea charts only work with custom data source</p>';
                                $scope.find(".king-addons-charts-container").html(errorMsg);
                            }
                        }

                        // Window resize handler
                        $(window).resize(() => {
                            const newRadius =
                                window.innerWidth >= 768
                                    ? chartSettings.line_dots_radius
                                    : chartSettings.line_dots_radius_mobile;
                            config.options.elements.point.radius = newRadius;
                            config.options.plugins.tooltip.caretSize =
                                window.innerWidth >= 768
                                    ? chartSettings.tooltip_caret_size
                                    : chartSettings.chart_tooltip_caret_size_mobile;
                        });

                        // Render chart from CSV data
                        function renderCSVChart(csvData) {
                            const ctx = $scope.find(".king-addons-chart");
                            const rows = csvData.split(/\r?\n|\r/);
                            const csvLabels = rows.shift().split(chartSettings.separator);
                            const data = { labels: csvLabels, datasets: [] };
                            const csvConfig = {
                                type: chartSettings.chart_type === "bar_horizontal" ? "bar" : chartSettings.chart_type,
                                data,
                                options: globalOptions,
                                plugins: [
                                    ...(chartSettings.inner_datalabels ? [ChartDataLabels] : []),
                                    {
                                        beforeInit(chart) {
                                            chart.legend.afterFit = function () {
                                                this.height += 50;
                                            };
                                        },
                                    },
                                ],
                            };
                            if (chartSettings.chart_type === "bar_horizontal") {
                                csvConfig.options.indexAxis = "y";
                            }
                            if (chartSettings.tooltips_percent) {
                                csvConfig.options.plugins.tooltip.callbacks.label = (data) => {
                                    let prefix = data.dataset.label + ": ";
                                    if (["pie", "doughnut", "polarArea"].includes(chartSettings.chart_type)) {
                                        prefix = `${data.label} (${data.dataset.label}): `;
                                    }
                                    const total = data.dataset.data.reduce(
                                        (sum, val) => parseFloat(sum) + parseFloat(val),
                                        0
                                    );
                                    const percentage = ((data.formattedValue / total) * 100).toPrecision(3);
                                    return prefix + (chartSettings.tooltips_percent ? percentage + "%" : data.formattedValue);
                                };
                            }
                            const csvChart = new Chart(ctx, csvConfig);
                            rows.forEach((row, idx) => {
                                if (row) {
                                    const dataset = { data: row.split(chartSettings.separator) };
                                    if (customDatasets[idx]) {
                                        Object.assign(dataset, customDatasets[idx]);
                                    }
                                    data.datasets.push(dataset);
                                    csvChart.update();
                                }
                            });
                        }
                    },
                });

                elementorFrontend.elementsHandler.addHandler(BaseHandler, {
                    $element: $scope,
                });
            }
        );
    });
})(jQuery);