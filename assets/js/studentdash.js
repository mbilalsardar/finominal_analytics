var jq = $.noConflict();
jq(document).ready(function () {

    var table_assignment = jq('#assignemntbl').DataTable({
        "scrollX": true
    });
    var table_attendance = jq('#attendanceTable').DataTable({
    });
    

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

    // Donut -- Course Grades.
    // --------------------------------------------------------------------
    var donutChartEl = document.querySelector('#donut-chart-grades'),
        donutChartConfig = {
            chart: {
                height: 300,
                type: 'donut'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: gradeLabels,
            series: gradeValues,
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
                            height: 300
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 300
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



    console.log('passed ' + allpassedstudents);
    console.log('passed ' + allfailedstudents);

    // Donut -- Performance .
    // --------------------------------------------------------------------
    var donutChartEl2 = document.querySelector('#donut-chart-performance'),
        donutChartConfig = {
            chart: {
                height: 300,
                type: 'pie'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: ['Passed,Failed'],
            series: [allpassedstudents,allfailedstudents],
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
                            height: 300
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 300
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
    if (typeof donutChartEl2 !== undefined && donutChartEl2 !== null) {
        var donutChart2 = new ApexCharts(donutChartEl2, donutChartConfig);
        donutChart2.render();
    }


    // Bar Chart -- Overall Attendance 
    // --------------------------------------------------------------------
    var barChartEl3 = document.querySelector('#bar-chart-attendance'),
        barChartConfig = {
            chart: {
                height: 269,
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
            // noData: {
            //     text: 'Select Course'
            // },
            xaxis: {
                type: 'category'
            },
        };
    if (typeof barChartEl3 !== undefined && barChartEl3 !== null) {
        var barChartAttendance = new ApexCharts(barChartEl3, barChartConfig);
        barChartAttendance.render();
    }

    barChartAttendance.updateSeries([{
        data: attendancebarchart,
    }]);

});