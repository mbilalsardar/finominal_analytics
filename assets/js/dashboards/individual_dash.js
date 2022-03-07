// var $ = $.noConflict();

AJAXURL = "https://phpstack-734511-2463855.cloudwaysapps.com/blocks/finominal_analytics/corelibs/ajax.php";

function load_Courses(elementid) {
    // Populate courses dropdown
        $.ajax({
            type: "POST",
            url: AJAXURL,
            data: {
                'function': 'get_all_courses',
                'userid': 0,
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                // $('#select_course').attr('disabled', false);
                // $('#select_course').html(data);
                console.log(data);
            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });
        // e.preventDefault();
}

$(document).ready(function () {

    ajaxurl = '';

    var $textHeadingColor = '#5e5873';
    var $strokeColor = '#ebe9f1';
    var $labelColor = '#e7eef7';
    var $avgSessionStrokeColor2 = '#ebf0f7';
    var $budgetStrokeColor2 = '#dcdae3';
    var $goalStrokeColor2 = '#51e5a8';
    var $revenueStrokeColor2 = '#d0ccff';
    var $textMutedColor = '#b9b9c3';
    var $salesStrokeColor2 = '#df87f2';
    var $white = '#fff';
    var $earningsStrokeColor2 = '#28c76f66';
    var $earningsStrokeColor3 = '#28c76f33';

    chartColors = {
        column: {
            series1: '#826af9',
            series2: '#d2b0ff',
            bg: '#f8d3ff'
        },
        success: {
            shade_100: '#7eefc7',
            shade_200: '#06774f'
        },
        donut: {
            series1: '#ffe700',
            series2: '#00d4bd',
            series3: '#826bf8',
            series4: '#2b9bf4',
            series5: '#FFA1A1'
        },
        area: {
            series3: '#a4f8cd',
            series2: '#60f2ca',
            series1: '#2bdac7'
        }
    };


    /* CHARTS DECLARATION */

    var $goalOverviewChart = document.querySelector('#marks-overview-chart');

    // Marks Overview  Chart

    goalChartOptions = {
        chart: {
            // height: 245,
            height: 200,
            type: 'radialBar',
            sparkline: {
                enabled: true
            },
            dropShadow: {
                enabled: true,
                blur: 3,
                left: 1,
                top: 1,
                opacity: 0.1
            }
        },
        colors: [$goalStrokeColor2],
        plotOptions: {
            radialBar: {
                offsetY: -10,
                startAngle: -150,
                endAngle: 150,
                hollow: {
                    size: '77%'
                },
                track: {
                    background: $strokeColor,
                    strokeWidth: '50%'
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        color: $textHeadingColor,
                        fontSize: '2.86rem',
                        fontWeight: '600'
                    }
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.2,
                // gradientToColors: [window.colors.solid.success],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        series: [83],
        stroke: {
            lineCap: 'round'
        },
        grid: {
            padding: {
                bottom: 30
            }
        }
    };
    goalChart = new ApexCharts($goalOverviewChart, goalChartOptions);
    goalChart.render();



    // Section wise marks chart
    // Donut Chart
    // --------------------------------------------------------------------
    var donutChartEl = document.querySelector('#section_marks_chart'),
        donutChartConfig = {
            chart: {
                height: 400,
                type: 'donut'
            },
            legend: {
                show: true,
                position: 'right'
            },
            labels: ['Operational', 'Networking', 'Hiring', 'R&D'],
            series: [85, 16, 50, 50],
            colors: [
                chartColors.donut.series1,
                chartColors.donut.series5,
                chartColors.donut.series3,
                chartColors.donut.series2
            ],
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    return parseInt(val) + '%';
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                fontSize: '2rem',
                                fontFamily: 'Montserrat'
                            },
                            value: {
                                fontSize: '1rem',
                                fontFamily: 'Montserrat',
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            total: {
                                show: true,
                                fontSize: '1.5rem',
                                label: 'Operational',
                                formatter: function (w) {
                                    return '31%';
                                }
                            }
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 380
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 320
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '1.5rem'
                                        },
                                        value: {
                                            fontSize: '1rem'
                                        },
                                        total: {
                                            fontSize: '1.5rem'
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ]
        };
    if (typeof donutChartEl !== undefined && donutChartEl !== null) {
        var donutChart = new ApexCharts(donutChartEl, donutChartConfig);
        donutChart.render();
    }


    // individual averages Chart
    // --------------------------------------------------------------------
    var barChartEl = document.querySelector('#individual_averages_chart'),
        barChartConfig = {
            chart: {
                height: 400,
                type: 'bar',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '30%',
                    endingShape: 'rounded'
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    top: -15,
                    bottom: -10
                }
            },
            // colors: window.colors.solid.info,
            dataLabels: {
                enabled: false
            },
            series: [{
                data: [44, 55, 41, 64, 22, 43, 21]
            }, {
                data: [53, 32, 33, 52, 13, 44, 32]
            }],
            xaxis: {
                categories: ['section 1', 'section 2', 'section 3', 'section 4', 'section 5', 'section 6', 'section 7']
            },
            // yaxis: {
            //     opposite: isRtl
            // }
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var barChart = new ApexCharts(barChartEl, barChartConfig);
        barChart.render();
    }


    // Questions overview chart
    // pie Chart
    // --------------------------------------------------------------------
    var donutChartEl2 = document.querySelector('#questions_overview_chart'),
        donutChartConfig = {
            chart: {
                height: 400,
                type: 'pie'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: ['Right', 'Wrong', 'Gave Up'],
            series: [85, 20, 20],
            colors: [
                chartColors.donut.series1,
                chartColors.donut.series5,
                chartColors.donut.series2
            ],
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    return parseInt(val) + '%';
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                fontSize: '2rem',
                                fontFamily: 'Montserrat'
                            },
                            value: {
                                fontSize: '1rem',
                                fontFamily: 'Montserrat',
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            total: {
                                show: true,
                                fontSize: '1.5rem',
                                label: 'Operational',
                                formatter: function (w) {
                                    return '31%';
                                }
                            }
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 380
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 320
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '1.5rem'
                                        },
                                        value: {
                                            fontSize: '1rem'
                                        },
                                        total: {
                                            fontSize: '1.5rem'
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ]
        };
    if (typeof donutChartEl2 !== undefined && donutChartEl2 !== null) {
        var pieChart = new ApexCharts(donutChartEl2, donutChartConfig);
        pieChart.render();
    }


    // quiz comparision Chart
    // --------------------------------------------------------------------
    var barChartEl = document.querySelector('#quizcomparision_chart'),
        barChartConfig = {
            chart: {
                height: 450,
                type: 'bar',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    vertical: true,
                    barHeight: '30%',
                    endingShape: 'flat'
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    top: 20,
                    bottom: 20
                }
            },
            // colors: window.colors.solid.info,
            dataLabels: {
                enabled: false
            },
            series: [{
                data: [44, 55, 41, 64]
            }],
            xaxis: {
                categories: ['quiz 1', 'quiz 2', 'quiz 3', 'quiz 4']
            },
            // yaxis: {
            //     opposite: isRtl
            // }
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var barChart = new ApexCharts(barChartEl, barChartConfig);
        barChart.render();
    }


    /* ---------------- END CHARTS DECLERATION ---------------------------*/



    
    /* Drop downs */
    load_Courses('');




});




