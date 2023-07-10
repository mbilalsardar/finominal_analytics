var jq = $.noConflict();

function load_Courses(elementid) {
    // Populate courses dropdown
    jq.ajax({
        type: "POST",
        url: AJAXURL,
        data: {
            'function': 'get_all_courses',
            'userid': 0,
        }, // Serializes the form's elements.
        dataType: 'json',
        success: function (data) {
            // jq('#select_course').attr('disabled', false);
            jq('#'+ elementid).empty();
            jq('#'+ elementid).append(data);
            // console.log(data);
        },
        error: function (request) {
            alert("Request: " + JSON.stringify(request));
        }
    });
    // e.preventDefault();
}


jq(document).ready(function () {

    
    // Attendance Overview Chart
    var jqtextHeadingColor = '#5e5873';
    var jqstrokeColor = '#ebe9f1';
    var jqlabelColor = '#e7eef7';
    var jqavgSessionStrokeColor2 = '#ebf0f7';
    var jqbudgetStrokeColor2 = '#dcdae3';
    var jqgoalStrokeColor2 = '#FF4D00';
    var jqrevenueStrokeColor2 = '#d0ccff';
    var jqtextMutedColor = '#b9b9c3';
    var jqsalesStrokeColor2 = '#df87f2';
    var jqwhite = '#fff';
    var jqearningsStrokeColor2 = '#28c76f66';
    var jqearningsStrokeColor3 = '#28c76f33';

    var table = jq('#dtable').DataTable();


    jq('#manager').prop( "disabled", true );
    jq('#department').prop( "disabled", true );
    jq('#location').prop( "disabled", true );
    jq('#team').prop('disabled',true);
    jq('#quiz').prop('disabled',true);
    jq('#designation').prop( "disabled", true );


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

    var jqradialbar_attendance = document.querySelector('#attendance-chart');
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
        colors: [jqgoalStrokeColor2],
        plotOptions: {
            radialBar: {
                offsetY: -10,
                startAngle: -150,
                endAngle: 150,
                hollow: {
                    size: '77%'
                },
                track: {
                    background: jqstrokeColor,
                    strokeWidth: '50%'
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        color: jqtextHeadingColor,
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
    attendance = new ApexCharts(jqradialbar_attendance, goalChartOptions);
    attendance.render();


    /* Certification Over view donut chart */
    var donutChartEl_cert = document.querySelector('#certification-chart'),
    donutChartConfigGrades = {
        chart: {
            height: 170,
            type: 'donut',
            offsetX: -25
        },
        legend: {
            show: true,
            position: 'left',
            offsetX: 25,

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
                                return parseInt(val);
                            }
                        },
                    },
                    
                    size: '65%',
                }
            }
        },
        responsive: [
            {
                breakpoint: 992,
                options: {
                    chart: {
                        height: 200
                    }
                }
            },
            {
                breakpoint: 576,
                options: {
                    chart: {
                        height: 200
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
                                },
                                size: '65%',
                            }
                        }
                    }
                }
            }
        ]
    };
    if (typeof donutChartEl_cert !== undefined && donutChartEl_cert !== null) {
        var certification_overview = new ApexCharts(donutChartEl_cert, donutChartConfigGrades);
        certification_overview.render();
    }




    /* Section Wise Marks Overview */
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
    //     labels: ['sections'],
    //     series: [0],
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


    /* Section Averages Bar Chart */
    var barChartEl = document.querySelector('#section_average_marks_chart'),
        barChartConfig = {
            chart: {
                height: 300,
                type: 'bar',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        position: 'top'
                    },
                    vertical: true,
                    barHeight: '30%',
                    distributed: true,
                    columnWidth: '20%',
                    endingShape: 'flat'
                }
            },

            legend: {
                show: false,
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
            dataLabels: {
                enabled: true,
                offsetY: -30,
                // formatter: function (val, opt) {
                //     return parseInt(val) + '%';
                // }
            },
            // colors: window.colors.solid.info,
            // dataLabels: {
            //     enabled: false
            // },
            series: [],            
            xaxis: {
                // categories : ['A','B','C']
                type: 'category',
                
            },
            yaxis: {
                tickAmount: 5,
                max: 100,
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            }
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var sec_avg_bar_chart = new ApexCharts(barChartEl, barChartConfig);
        sec_avg_bar_chart.render();
    }



     /* Top Performers Chart */
    var barChartEl = document.querySelector('#top_performers_bar_chart'),
        barChartConfig = {
            chart: {
                height: 300,
                // width : '100%',
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
                offsetX: 30,
                style: {
                    // fontSize: '14px',
                    // fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 'bold',
                    // colors: ['#ffffff'],
                },
                
            },
            series: [],            
            xaxis: {
                // categories : ['name']
                type: 'category',
                tickAmount: 05,
                max: 100,
            },
            
        };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var top_performers = new ApexCharts(barChartEl, barChartConfig);
        top_performers.render();
    }


    // // Questions overview chart
    // // pie Chart
    // // --------------------------------------------------------------------
    // var donutChartEl2 = document.querySelector('#questions_overview_chart'),
    //     donutChartConfig = {
    //         chart: {
    //             height: 300,
    //             type: 'pie'
    //         },
    //         legend: {
    //             show: true,
    //             position: 'bottom'
    //         },
    //         labels: ['Right','Wrong','Gave Up'],
    //         series: [0,0,0],
    //         // colors: [
    //         //     chartColors.donut.series2,
    //         //     chartColors.donut.series5,
    //         //     chartColors.donut.series1,
    //         // ],
    //         colors:['#17efcb', '#ed5b2f', '#fc9e5f'],
    //         dataLabels: {
    //             enabled: true,
    //             formatter: function (val, opt) {
    //                 return parseInt(val) + '%';
    //             }
    //         },
    //         plotOptions: {
    //             pie: {
    //                 donut: {
    //                     labels: {
    //                         show: true,
    //                         name: {
    //                             fontSize: '2rem',
    //                             fontFamily: 'Montserrat'
    //                         },
    //                         value: {
    //                             fontSize: '1rem',
    //                             fontFamily: 'Montserrat',
    //                             formatter: function (val) {
    //                                 return parseInt(val) + '%';
    //                             }
    //                         },
    //                         total: {
    //                             show: true,
    //                             fontSize: '1.5rem',
    //                             color: '#FFFFFF',
    //                             label: 'Total Questions',
    //                             // formatter: function (w) {
    //                             //     return '31%';
    //                             // }
    //                         }
    //                     }
    //                 }
    //             }
    //         },
    //         responsive: [
    //             {
    //                 breakpoint: 992,
    //                 options: {
    //                     chart: {
    //                         height: 380
    //                     }
    //                 }
    //             },
    //             {
    //                 breakpoint: 576,
    //                 options: {
    //                     chart: {
    //                         height: 320
    //                     },
    //                     plotOptions: {
    //                         pie: {
    //                             donut: {
    //                                 labels: {
    //                                     show: true,
    //                                     name: {
    //                                         fontSize: '1.5rem'
    //                                     },
    //                                     value: {
    //                                         fontSize: '1rem'
    //                                     },
    //                                     total: {
    //                                         fontSize: '1.5rem'
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         ]
    //     };
    // if (typeof donutChartEl2 !== undefined && donutChartEl2 !== null) {
    //     var pieChart = new ApexCharts(donutChartEl2, donutChartConfig);
    //     pieChart.render();
    // }


    /* Marks Summary Chart */
    var barChartEl = document.querySelector('#marks_summary'),
    barChartConfig = {
        chart: {
            height: 300,
            type: 'bar',
            parentHeightOffset: 0,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                vertical: true,
                barHeight: '25%',
                endingShape: 'flat',
                columnWidth: '30%',
                dataLabels: {
                    position: 'top'
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
            offsetY: -20
        },
        series: [
            { data : [0,0,0] }
        ],
        colors: [
            chartColors.column.series1,
            // chartColors.donut.series5,
            // chartColors.donut.series1,
        ],  
        fill: {
            colors: ['#174700']
        },          
        xaxis: {
            categories : ['Minimum','Average','Maximum']
            // type: 'category',
        },
        yaxis: {
            tickAmount: 5,
            max: 100,
            labels: {
                formatter: function(val) {
                    return val.toFixed(0);
                }
            }
        }
    };
    if (typeof barChartEl !== undefined && barChartEl !== null) {
        var markssummary = new ApexCharts(barChartEl, barChartConfig);
        markssummary.render();
    }




   load_Courses('course');

   jq('#course').on('change',function(e){

        e.preventDefault();

        var courseid = jq('#course').val();

        /* For Quiz dropdown */
        jq.ajax({
            type: "POST",
            url: AJAXURL,
            data: {
                'function': 'get_course_quiz',
                'cid': courseid,
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                jq('#quiz').empty();
                jq('#quiz').append(data);
                jq('#quiz').prop('disabled',false);
            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });

        /* For Student dropdown */
        jq.ajax({
            type: "POST",
            url: AJAXURL,
            data: {
                'function': 'get_team_in_course',
                'cid': courseid,
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                jq('#team').empty();
                jq('#team').append(data);
                jq('#team').prop('disabled',false);
            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });

    jq('#reset').on('click',function(e){
        jq('#course').prop('selectedIndex',0);
        jq('#quiz').prop('selectedIndex',0);
        jq('#team').prop('selectedIndex',0);
        jq('#manager').prop('selectedIndex',0);
        jq('#department').prop('selectedIndex',0);
        jq('#location').prop('selectedIndex',0);
        jq('#designation').prop( "selectedIndex", 0 );

        jq('#quiz').prop('disabled',true);
        jq('#team').prop('disabled',true);
        jq('#manager').prop('disabled',true);
        jq('#department').prop('disabled',true);
        jq('#location').prop('disabled',true);
        jq('#designation').prop( "disabled", true );
        
    });

    jq('#team').on('change',function(e){
        
        e.preventDefault();

        var teamid = jq('#team').val();

        /* For Quiz dropdown */
        jq.ajax({
            type: "POST",
            url: AJAXURL,
            data: {
                'function': 'get_team_managers',
                'teamid': teamid,
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
                
                // Manager
                jq('#manager').empty();
                jq('#manager').append(data['manager']);
                jq('#manager').prop( "disabled", false );

                // Department
                jq('#department').empty();
                jq('#department').append(data['department']);
                jq('#department').prop( "disabled", false );

                // Location
                jq('#location').empty();
                jq('#location').append(data['location']);
                jq('#location').prop( "disabled", false );

                // Designation
                jq('#designation').empty();
                jq('#designation').append(data['designation']);
                jq('#designation').prop( "disabled", false );

            },
            error: function (request) {
                alert("Request: " + JSON.stringify(request));
            }
        });
    });


    /* View Dashboard button click */
    jq('#view').on('click',function(e){

        e.preventDefault();

        var cid = jq('#course').val();
        var qid = jq('#quiz').val();
        var teamid = jq('#team').val();
        var location = jq('#location').val();
        var manageremail = jq('#manager').val();
        var department = jq('#department').val();
        var designation = jq('#designation').val();
        

        jq.ajax({
            type: "POST",
            url: AJAXURL,
            data: {
                'function': 'team_dash_view',
                'cid': cid,
                'qid': qid,
                'teamid': teamid,
                'manageremail': manageremail,
                'designation': designation,
                'location': location,
                'department': department,
            }, // Serializes the form's elements.
            dataType: 'json',
            success: function (data) {
    
                // console.log(data);

                // Total members 
                jq('#ttl_members_count').html(data['ttlparticipants']);
                // Total Questions 
                jq('#totalquestions_count_div').html(data['ttlquestions']);
                // Total Sections
                jq('#total_sections_div').html(data['ttlsections']);
                
                // Section Performance.
                // donutChartSections.updateOptions({
                //     labels: data['section_performance_labels'],
                //     series: data['section_performance_series']
                // });


                // Certificate donut chart
                certification_overview.updateOptions({
                    series: data['certificate_series'],
                    labels: ['Pass','Fail'],
                });
                
                jq('#ttlcertissued').html(data['totalpass']);
                jq('#ttlcertnotissued').html(data['totalfail']);

                /* Question Overview chart */
                // pieChart.updateOptions({
                //     series: data['question_overview_series'],
                //     labels: data['question_overview_labels'],
                // });

                jq('#q_overview_right').html(data['ttlrightquest']);                
                jq('#q_overview_wrong').html(data['ttlwrongquest']);
                jq('#q_overview_gaveup').html(data['ttlgaveupquest']);


                /* Marks Summary */
                markssummary.updateSeries([{
                    data: data['marks_summary'],
                }]);

                /* Top Performers */
                top_performers.updateSeries([{
                    data: data['top_performer'],
                }]);

                /* Section averages bar chart */
                sec_avg_bar_chart.updateSeries([{
                    data: data['sectionaveragemarks'],
                }]);


                /* Attendance */
                jq('#participated').html(data['quizparticipated']);
                jq('#notparticipated').html(data['quiznotparticipated']);

                attendance.updateOptions({
                    series: [data['quizparticipatedpercent']],
                    labels: ['participated'],
                });


                // Table 

                console.log(data['table']);
           

                table.clear().destroy();
                jq('#dtable-body').html(data['table']);
                table = jq('#dtable').DataTable();
             


            },
            error: function (request) {
                console.log("Request: " + JSON.stringify(request));
            }
        });

    });

});

