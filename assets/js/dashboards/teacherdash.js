var jq = $.noConflict();
jq(document).ready(function () {


    var table_ungraded = jq('#ungraded').DataTable({
        pageLength: 5,
        "scrollX": true,
    });
    var table_progress = jq('#progress').DataTable({
        pageLength: 5,
        "scrollX": true,
    });
    var table_attend = jq('#attendance').DataTable({
        "scrollX": true
    });

    var lineAreaChart1 = document.querySelector('#line-area-chart-1');
    var lineAreaChart2 = document.querySelector('#line-area-chart-2');


    // jugar for profile dropdown



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
            series5: '#FFA1A1',
            green: '#00cc44',
            red: '#ff3333',
        },
        area: {
            series3: '#a4f8cd',
            series2: '#60f2ca',
            series1: '#2bdac7'
        }
    };

    // view ungraded asiignments. 
    jq('#ungradedassignments').click(function (e) {
        e.preventDefault();
        table_ungraded.destroy();

        var courseid = jq('#courseslect_ungraded').val();

        url = 'ajax.php';
        jq.ajax({
            type: "POST",
            url: url,
            data: {
                'function': 'teacher_dash_ungraded',
                'data': courseid
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                jq('#report_ungraded').empty();
                jq('#report_ungraded').html(data);
                table_ungraded = jq('#ungraded').DataTable({
                    pageLength: 5,
                });
            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });


    function progress_data() {
        table_progress.destroy();

        var courseid = jq('#courseslect_progress').val();

        url = 'ajax.php';

        jq.ajax({
            type: "POST",
            url: url,
            data: {
                'function': 'teacher_dash_progress',
                'data': courseid
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                jq('#report_progress').empty();
                jq('#report_progress').html(data['table']);
                table_progress = jq('#progress').DataTable({
                    pageLength: 5,
                    "scrollX": true,
                });

                barChartProgress.updateSeries([{
                    data: data['chart']
                }]);

                barChartmarksdistribution.updateSeries([{
                    data: data['marks_dist']
                }]);

                donutChartGrades.updateOptions({
                    series: data['grade_series'],
                    labels: data['grade_labels']
                });

            },
            error: function (request) {
                console.log(request);
                // alert("Request: "+JSON.stringify(request));
            }
        });
    }

    function attendance_data() {
        table_attend.destroy();

        var courseid = jq('#courseslect_attendance').val();

        url = 'ajax.php';

        jq.ajax({
            type: "POST",
            url: url,
            data: {
                'function': 'teacher_dash_attendance',
                'data': courseid
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                jq('#report_attendance').empty();
                jq('#report_attendance').html(data['table']);
                // console.log(data['chart']);
                table_attend = jq('#attendance').DataTable({
                    "scrollX": true
                });

                barChartAttendance.updateSeries([{
                    data: data['chart']
                }]);

            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    }

    // view progress. 
    jq('#progressdropdown').click(function (e) {
        e.preventDefault();
        progress_data();
    });


    // view attendance. 
    jq('#attendencedropdown').click(function (e) {
        e.preventDefault();
        attendance_data();
    });


    // Enrolled Students Chart
    // ----------------------------------
    gainedChartOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: {
                show: false
            },
            sparkline: {
                enabled: true
            },
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            }
        },
        colors: [window.colors.solid.primary],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.9,
                opacityFrom: 0.7,
                opacityTo: 0.5,
                stops: [0, 80, 100]
            }
        },
        series: [
            {
                name: 'Subscribers',
                data: [28, 40, 36, 52, 38, 60, 55]
            }
        ],
        xaxis: {
            labels: {
                show: false
            },
            axisBorder: {
                show: false
            }
        },
        yaxis: [
            {
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 }
            }
        ],
        tooltip: {
            x: { show: false }
        }
    };

    gainedChart = new ApexCharts(lineAreaChart1, gainedChartOptions);
    gainedChart.render();

    // Revenue Generated Chart
    // ----------------------------------
    revenueChartOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: {
                show: false
            },
            sparkline: {
                enabled: true
            },
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            }
        },
        colors: [window.colors.solid.success],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.9,
                opacityFrom: 0.7,
                opacityTo: 0.5,
                stops: [0, 80, 100]
            }
        },
        series: [
            {
                name: 'Revenue',
                data: [350, 275, 400, 300, 350, 300, 450]
            }
        ],
        xaxis: {
            labels: {
                show: false
            },
            axisBorder: {
                show: false
            }
        },
        yaxis: [
            {
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 }
            }
        ],
        tooltip: {
            x: { show: false }
        }
    };

    revenueChart = new ApexCharts(lineAreaChart2, revenueChartOptions);
    revenueChart.render();


    // Donut info Chart.
    // --------------------------------------------------------------------
    var donutChartEl = document.querySelector('#donut-chart'),
        donutChartConfig = {
            chart: {
                height: 250,
                type: 'donut'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: ['Passed', 'Failed'],
            series: [allpassedstudents, allfailedstudents],
            colors: [
                chartColors.donut.green,
                chartColors.donut.red,
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
                                fontSize: '0.5rem',
                                fontFamily: 'Montserrat'
                            },
                            value: {
                                fontSize: '0.5rem',
                                fontFamily: 'Montserrat',
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            // total: {
                            //     show: true,
                            //     fontSize: '1.5rem',
                            //     label: 'Operational',
                            //     formatter: function (w) {
                            //         return '31%';
                            //     }
                            // }
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 250
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 250
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '0.5rem'
                                        },
                                        value: {
                                            fontSize: '0.5rem'
                                        },
                                        // total: {
                                        //     fontSize: '1.5rem'
                                        // }
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


    // Bar Chart // student progress 
    // --------------------------------------------------------------------
    var barChartEl = document.querySelector('#bar-chart-progress'),
        barChartConfig = {
            chart: {
                height: 500,
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
            colors: window.colors.solid.info,
            dataLabels: {
                enabled: false
            },
            series: [],
            noData: {
                text: 'Select Course'
            },
            xaxis: {
                type: 'category'
            },
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var barChartProgress = new ApexCharts(barChartEl, barChartConfig);
        barChartProgress.render();
    }


    // Donut info Chart. grades
    // --------------------------------------------------------------------
    var donutChartEl2 = document.querySelector('#donut-chart-grades'),
        donutChartConfigGrades = {
            chart: {
                height: 500,
                type: 'donut'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: [],
            series: [],
            // colors: [
            //     chartColors.donut.green,
            //     chartColors.donut.red,
            // ],
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
                                fontSize: '0.5rem',
                                fontFamily: 'Montserrat'
                            },
                            value: {
                                fontSize: '0.5rem',
                                fontFamily: 'Montserrat',
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                        }
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 250
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 250
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '0.5rem'
                                        },
                                        value: {
                                            fontSize: '0.5rem'
                                        },
                                    }
                                }
                            }
                        }
                    }
                }
            ]
        };
    if (typeof donutChartEl2 !== undefined && donutChartEl2 !== null) {
        var donutChartGrades = new ApexCharts(donutChartEl2, donutChartConfigGrades);
        donutChartGrades.render();
    }


    // Bar Chart // student Attendance 
    // --------------------------------------------------------------------
    var barChartEl2 = document.querySelector('#bar-chart-attendance'),
        barChart_attend_Config = {
            chart: {
                height: 500,
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
            colors: window.colors.solid.primary,
            dataLabels: {
                enabled: false
            },
            series: [],
            noData: {
                text: 'Select Course'
            },
            xaxis: {
                type: 'category'
            },
        };
    if (typeof barChartEl2 !== undefined && barChartEl2 !== null) {
        var barChartAttendance = new ApexCharts(barChartEl2, barChart_attend_Config);
        barChartAttendance.render();
    }


    // Bar Chart // Student Marks Redistribution 
    // --------------------------------------------------------------------
    var barChartEl3 = document.querySelector('#donut-chart-marksdistribution'),
        barChart_attend_marksdistribution = {
            chart: {
                height: 470,
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
            colors: window.colors.solid.primary,
            dataLabels: {
                enabled: false
            },
            series: [],
            noData: {
                text: 'Select Course'
            },
            xaxis: {
                type: 'category'
            },
        };
    if (typeof barChartEl3 !== undefined && barChartEl3 !== null) {
        var barChartmarksdistribution = new ApexCharts(barChartEl3, barChart_attend_marksdistribution);
        barChartmarksdistribution.render();
    }

    // Populating charts and tables on page load

    jq('select[name=courseslect_progress] option:eq(3)').attr('selected', 'selected');
    jq('select[name=courseslect_attendance] option:eq(2)').attr('selected', 'selected');

    progress_data();
    attendance_data();

});

