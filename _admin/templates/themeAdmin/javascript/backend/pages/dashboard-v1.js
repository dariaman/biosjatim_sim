/* ========================================================================
 * dashboard-v1.js
 * Page/renders: index.html
 * Plugins used: flot, sparkline, selectize
 * ======================================================================== */

'use strict';

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define([
            'selectize',
            'jquery.flot',
            'jquery.flot.resize',
            'jquery.flot.categories',
            'jquery.flot.time',
            'jquery.flot.tooltip',
            'jquery.flot.spline'
        ], factory);
    } else {
        factory();
    }
}(function () {

    $(function () {

		// Stats
        // ================================
        // default options
        var option = {
            series: {
                lines: { show: false },
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 2,
                    fill: 0.5
                },
                points: {
                    show: false,
                    radius: 4
                }
            },
            grid: {
                borderColor: '#eee',
                borderWidth: 0,
                hoverable: true,
                backgroundColor: '#fcfcfc'
            },
            tooltip: true,
            tooltipOpts: { content: '%x : %y' },
            xaxis: {
                tickColor: '#fcfcfc',
                mode: 'categories'
            },
            yaxis: { tickColor: '#eee' },
            shadowSize: 0
        };

		// Selectize
        // ================================
        $('#selectize-customselect').selectize();

		// Stats #1
        $.plot('#stats1', [{
            color: '#DC554F',
            data: [ ['Mon', 5], ['Tue', 8], ['Wed', 15], ['Thu', 6], ['Fri', 10] ]
        }], option);

        // Sparkline
        // ================================
        $('.sparklines').sparkline('html', {
            enableTagOptions: true
        });

        // Area Chart - Spline
        // ================================
        $.plot('#chart-audience', [{
            label: 'Visit (All)',
            color: '#DC554F',
            data: [
                ['Jan', 47],
                ['Feb', 84],
                ['Mar', 60],
                ['Apr', 143],
                ['May', 39],
                ['Jun', 86],
                ['Jul', 87]
            ]
        }, {
            label: 'Visit (Mobile)',
            color: '#9365B8',
            data: [
                ['Jan', 83],
                ['Feb', 32],
                ['Mar', 16],
                ['Apr', 47],
                ['May', 98],
                ['Jun', 84],
                ['Jul', 18]
            ]
        }], {
            series: {
                lines: {
                    show: true
                },
                splines: {
                    show: false,
                    tension: 0.4,
                    lineWidth: 2,
                    fill: 0.8
                },
                points: {
                    show: true,
                    radius: 4
                }
            },
            grid: {
                borderColor: 'rgba(0, 0, 0, 0.05)',
                borderWidth: 1,
                hoverable: true,
                backgroundColor: 'transparent'
            },
            tooltip: true,
            tooltipOpts: {
                content: '%x : %y',
                defaultTheme: false
            },
            xaxis: {
                tickColor: 'rgba(0, 0, 0, 0.05)',
                mode: 'categories'
            },
            yaxis: {
                tickColor: 'rgba(0, 0, 0, 0.05)'
            },
            shadowSize: 0
        });
    });
}));