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
            $('#course').empty();
            $('#course').append(data);
            // console.log(data);
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


    // Disabling on load 
    $('#quiz').prop('disabled',true);
    $('#student').prop('disabled',true);

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
        series: [0],
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

    // Section Overview
    // var donutChartEl = document.querySelector('#section_marks_chart'),
    // donutChartConfigGrades = {
    //     chart: {
    //         height: 300,
    //         type: 'donut'
    //     },
    //     legend: {
    //         show: true,
    //         position: 'right'
    //     },
    //     labels: [],
    //     series: [],
    //     // colors: [
    //     //     chartColors.donut.green,
    //     //     chartColors.donut.red,
    //     // ],
    //     dataLabels: {
    //         enabled: true,
    //         formatter: function (val, opt) {
    //             return parseInt(val) + '%';
    //         }
    //     },
    //     plotOptions: {
    //         pie: {
    //             donut: {
    //                 labels: {
    //                     show: true,
    //                     name: {
    //                         fontSize: '0.5rem',
    //                         fontFamily: 'Montserrat'
    //                     },
    //                     value: {
    //                         fontSize: '0.5rem',
    //                         fontFamily: 'Montserrat',
    //                         formatter: function (val) {
    //                             return parseInt(val) + '%';
    //                         }
    //                     },
    //                 }
    //             }
    //         }
    //     },
    //     responsive: [
    //         {
    //             breakpoint: 992,
    //             options: {
    //                 chart: {
    //                     height: 250
    //                 }
    //             }
    //         },
    //         {
    //             breakpoint: 576,
    //             options: {
    //                 chart: {
    //                     height: 250
    //                 },
    //                 plotOptions: {
    //                     pie: {
    //                         donut: {
    //                             labels: {
    //                                 show: true,
    //                                 name: {
    //                                     fontSize: '0.5rem'
    //                                 },
    //                                 value: {
    //                                     fontSize: '0.5rem'
    //                                 },
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     ]
    // };
    // if (typeof donutChartEl !== undefined && donutChartEl !== null) {
    //     var donutChartSections = new ApexCharts(donutChartEl, donutChartConfigGrades);
    //     donutChartSections.render();
    // }

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
                    barHeight: '50%',
                    endingShape: 'flat',
                    dataLabels: {
                        position: 'top',
                    }
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
                enabled: true,
                offsetX: 40,
                formatter: function (val, opts) {
                    return val+' %'
                },
                // position: 'right',
            },
            series: [],            
            xaxis: {
                categories : ['A','B','C']
                // type: 'categories',
                // categories : []
            },
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var individual_barChart = new ApexCharts(barChartEl, barChartConfig);
        individual_barChart.render();
    }

    // Questions overview chart
    // pie Chart
    // --------------------------------------------------------------------
    var donutChartEl2 = document.querySelector('#questions_overview_chart'),
        donutChartConfig = {
            chart: {
                height: 300,
                type: 'pie'
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            labels: ['Right','Wrong','Gave Up'],
            series: [0,0,0],
            // colors: [
            //     chartColors.donut.series2,
            //     chartColors.donut.series5,
            //     chartColors.donut.series1,
            // ],
            colors:['#17efcb', '#ed5b2f', '#fc9e5f'],

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
                                color: '#FFFFFF',
                                label: 'Total Questions',
                                // formatter: function (w) {
                                //     return '31%';
                                // }
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
                height: 350,
                type: 'bar',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                dataLabels: {
                    position: 'top',
                }
            },
            plotOptions: {
                bar: {
                    vertical: true,
                    barHeight: '30%',
                    distributed: true,
                    columnWidth: '20%',
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
            dataLabels: {
                enabled: true,
                position: 'top',
                // color:['#fff'],
                // offsetY: -30,
                formatter: function (val, opt) {
                    return parseInt(val) + '%';
                }
            },
            series: [],
            xaxis: {
                type: 'category'
            },
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var barChart = new ApexCharts(barChartEl, barChartConfig);
        barChart.render();
    }


    /* ---------------- END CHARTS DECLERATION ---------------------------*/



    
    /* Drop downs */
    load_Courses('');

    $('#reset').on('click',function(e){
        $('#course').prop('selectedIndex',0);
        $('#quiz').prop('selectedIndex',0);
        $('#student').prop('selectedIndex',0);
      
        $('#quiz').prop('disabled',true);
        $('#student').prop('disabled',true);
       
        
    });

    $('#course').on('change',function(e){

        e.preventDefault();

        var courseid = $('#course').val();

        /* For Quiz dropdown */
        $.ajax({
            type: "POST",
            url: AJAXURL,
            data: {
                'function': 'get_course_quiz',
                'cid': courseid,
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                $('#quiz').empty();
                $('#quiz').append(data);
                $('#quiz').prop('disabled',false);
             
            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });

        /* For Student dropdown */
        // $.ajax({
        //     type: "POST",
        //     url: AJAXURL,
        //     data: {
        //         'function': 'get_course_enrollments',
        //         'cid': courseid,
        //     }, // Serializes the form's elements.
        //     dataType: 'json',
        //     success: function (data) {
        //         $('#student').empty();
        //         $('#student').append(data);
        //         $('#student').prop('disabled',false);
        //     },
        //     error: function (request) {
        //         alert("Request: " + JSON.stringify(request));
        //     }
        // });
    });

    $('#view').on('click',function(e){


        e.preventDefault();

        var cid = $('#course').val();
        var qid = $('#quiz').val();
        // var uid = $('#student').val();
        var uid = $('#userid_input_hidden').val();

       
        $.ajax({
        type: "POST",
        url: AJAXURL,
        data: {
            'function': 'individual_dash_view',
            'cid': cid,
            'qid': qid,
            'uid': uid,
        }, // Serializes the form's elements.
        dataType: 'json',
        success: function (data) {

            console.log(data);

            // Setting employee Info box data
            var userinfo = data['userinfo'];
            $('#userinfo_username').html(userinfo['username']);
            $('#userinfo_designation').html(userinfo['designation']);
            $('#userinfo_team').html(userinfo['team']);
            $('#userinfo_location').html(userinfo['location']);
            $('#userinfo_department').html(userinfo['department']);
            $('#userinfo_manager').html(userinfo['manager']);

            
            $('#totalquestions_count_div').html(data['quiz_questions_info']['questions_total']);
            $('#teammember_count_div').html(data['totalteammembers']);
            $('#certificate_status_div').html(data['quiz_certificate']);


            // Quiz Marks Chart
            var quizmarks = data['quiz_marks'];
            goalChart.updateOptions({
                series: quizmarks['percentage'],
                labels: ['grade'],
            });


            $('#marks_overview_totalmarks').html(quizmarks['total']);
            $('#marks_overview_obtainedmarks').html(quizmarks['obtained']);


            // Sections donut chart update.
            // donutChartSections.updateOptions({
            //     series: data['section_grade_series'],
            //     labels: data['section_grade_labels']
            // });


            // Questions Overview Chart Update
            var q_overview = data['quiz_questions_info'];
            var q_overivew_series = data['questions_overview_series'];
            var q_overivew_labels = data['questions_overview_labels'];

            $('#q_overview_right').html(q_overview['questions_ttlcorrect']);
            $('#q_overview_wrong').html(q_overview['questions_ttlwrong']);
            $('#q_overview_gaveup').html(q_overview['questions_ttlgaveup']);

            // Sections donut chart update.
            pieChart.updateOptions({
                series: q_overivew_series,
                labels: q_overivew_labels
            });


            // All Quiz Comparison Data
            barChart.updateSeries([{
                data: data['allquiz_data'],
            }]);


            // Individual Quiz Section comparison chart
            individual_barChart.updateOptions({
                xaxis: {
                    categories : data['indi_team_averages_label'],
                },
                series: data['indi_team_averages_series'],
            });

        },
        error: function (request) {
            console.log("Request: " + JSON.stringify(request));
            }
        });

    });




});




