/**
 * Dashboard Analytics
 */

'use strict';

(function () {
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;

  
  // Order Statistics Chart
  // --------------------------------------------------------------------
  var data = JSON.parse(document.getElementById("pembelajaranPerbulan").value);
  var jmlTidakHadir = JSON.parse(document.getElementById("kehadiranPerbulan").value);
  jmlTidakHadir.unshift('');
  console.log('Ini data');
  console.log(jmlTidakHadir);

  
  //Persentase TERLAKSANA VS TIDAK TERLAKSANA
  var jmlPembelajaran = JSON.parse(document.getElementById("jmlPembelajaran").value);
  var terlaksana = JSON.parse(document.getElementById("terlaksana").value);
  var tidakTerlaksana = JSON.parse(document.getElementById("tidakTerlaksana").value);
  var persenTerlaksana = terlaksana/jmlPembelajaran * 100 ; 
  var persenTidakTerlaksana = 100-persenTerlaksana;
  console.log(persenTidakTerlaksana);
  
  // console.log('Hallo');
  // console.log(data);
  var bulan = [''];
  var jmlTidakTerlaksana = [''];
  data.forEach(element => {
    bulan.push(element['bulan']);
    jmlTidakTerlaksana.push(element['jml']);
  });
  // console.log(bulan);

  const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
    orderChartConfig = {
      chart: {
        height: 250,
        width: 200,
        type: 'donut'
      },
      labels: ['Terlaksana', 'Tidak Terlaksana'],
      series: [persenTerlaksana, persenTidakTerlaksana],
      colors: [config.colors.primary, config.colors.secondary],
      stroke: {
        width: 5,
        colors: cardColor
      },
      dataLabels: {
        enabled: false,
        formatter: function (val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: false
      },
      grid: {
        padding: {
          top: 0,
          bottom: 0,
          right: 15
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '70%',
            labels: {
              show: true,
              value: {
                fontSize: '1.5rem',
                fontFamily: 'Public Sans',
                color: headingColor,
                offsetY: -15,
                formatter: function (val) {
                  return parseInt(val) + '%';
                }
              },
              name: {
                offsetY: 20,
                fontFamily: 'Public Sans'
              },
              total: {
                show: true,
                fontSize: '0.6125rem',
                color: axisColor,
                // label: '%',
                formatter: function (w) {
                  return '100%';
                }
              }
            }
          }
        }
      }
    };
  

  if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
    const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
    // statisticsChart.destroy();
    // statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
    statisticsChart.render();
  } 

  window.addEventListener('refresh-chart', event => {
    console.log('MAsyuk Nih');
    //Persentase TERLAKSANA VS TIDAK TERLAKSANA
    var jmlPembelajaran = JSON.parse(document.getElementById("jmlPembelajaran").value);
    var terlaksana = JSON.parse(document.getElementById("terlaksana").value);
    var tidakTerlaksana = JSON.parse(document.getElementById("tidakTerlaksana").value);
    var persenTerlaksana = terlaksana/jmlPembelajaran * 100 ; 
    var persenTidakTerlaksana = 100-persenTerlaksana;
    console.log(persenTidakTerlaksana);
  
    // console.log('Hallo');
    // console.log(data);
    var bulan = [''];
    data.forEach(element => {
      bulan.push(element['tanggal']);
    });
    // console.log(bulan);

    const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
      orderChartConfig = {
        chart: {
          height: 250,
          width: 200,
          type: 'donut'
        },
        labels: ['Terlaksana', 'Tidak Terlaksana'],
        series: [persenTerlaksana, persenTidakTerlaksana],
        colors: [config.colors.primary, config.colors.secondary],
        stroke: {
          width: 5,
          colors: cardColor
        },
        dataLabels: {
          enabled: false,
          formatter: function (val, opt) {
            return parseInt(val) + '%';
          }
        },
        legend: {
          show: false
        },
        grid: {
          padding: {
            top: 0,
            bottom: 0,
            right: 15
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: '70%',
              labels: {
                show: true,
                value: {
                  fontSize: '1.5rem',
                  fontFamily: 'Public Sans',
                  color: headingColor,
                  offsetY: -15,
                  formatter: function (val) {
                    return parseInt(val) + '%';
                  }
                },
                name: {
                  offsetY: 20,
                  fontFamily: 'Public Sans'
                },
                total: {
                  show: true,
                  fontSize: '0.6125rem',
                  color: axisColor,
                  // label: '%',
                  formatter: function (w) {
                    return '100%';
                  }
                }
              }
            }
          }
        }
      };
    
    if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
      const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
      // statisticsChart.destroy();
      // statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
      statisticsChart.render();
      }
    });
  
  // console.log(chartOrderStatistics);

  // Income Chart - Area chart
  // --------------------------------------------------------------------
  const incomeChartEl = document.querySelector('#incomeChart'),
    incomeChartConfig = {
      series: [
        {
          data: jmlTidakTerlaksana
        }
      ],
      chart: {
        height: 215,
        parentHeightOffset: 0,
        parentWidthOffset: 0,
        toolbar: {
          show: false
        },
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 2,
        curve: 'smooth'
      },
      legend: {
        show: false
      },
      markers: {
        size: 6,
        colors: 'transparent',
        strokeColors: 'transparent',
        strokeWidth: 4,
        discrete: [
          {
            fillColor: config.colors.white,
            seriesIndex: 0,
            dataPointIndex: 7,
            strokeColor: config.colors.danger,
            strokeWidth: 2,
            size: 6,
            radius: 8
          }
        ],
        hover: {
          size: 7
        }
      },
      colors: [config.colors.danger],
      fill: {
        type: 'gradient',
        gradient: {
          shade: shadeColor,
          shadeIntensity: 0.6,
          opacityFrom: 0.5,
          opacityTo: 0.25,
          stops: [0, 95, 100]
        }
      },
      grid: {
        borderColor: borderColor,
        strokeDashArray: 3,
        padding: {
          top: -20,
          bottom: -8,
          left: -10,
          right: 8
        }
      },
      xaxis: {
        // categories: ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul','Agu','Sep','Nov','Des'],
        categories: bulan,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: true,
          style: {
            fontSize: '13px',
            colors: axisColor
          }
        }
      },
      yaxis: {
        labels: {
          show: true
        },
        min: 0,
        max: 60,
        tickAmount: 4
      }
    };
  if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
    const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
    incomeChart.render();
  }

  // Income Chart - Area chart
  // --------------------------------------------------------------------
  const incomeChartEl2 = document.querySelector('#incomeChart2'),
    incomeChartConfig2 = {
      series: [
        {
          data: jmlTidakHadir
        }
      ],
      chart: {
        height: 215,
        parentHeightOffset: 0,
        parentWidthOffset: 0,
        toolbar: {
          show: false
        },
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 2,
        curve: 'smooth'
      },
      legend: {
        show: false
      },
      markers: {
        size: 6,
        colors: 'transparent',
        strokeColors: 'transparent',
        strokeWidth: 4,
        discrete: [
          {
            fillColor: config.colors.white,
            seriesIndex: 0,
            dataPointIndex: 7,
            strokeColor: config.colors.warning,
            strokeWidth: 2,
            size: 6,
            radius: 8
          }
        ],
        hover: {
          size: 7
        }
      },
      colors: [config.colors.warning],
      fill: {
        type: 'gradient',
        gradient: {
          shade: shadeColor,
          shadeIntensity: 0.6,
          opacityFrom: 0.5,
          opacityTo: 0.25,
          stops: [0, 95, 100]
        }
      },
      grid: {
        borderColor: borderColor,
        strokeDashArray: 3,
        padding: {
          top: -20,
          bottom: -8,
          left: -10,
          right: 8
        }
      },
      xaxis: {
        categories: bulan,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: true,
          style: {
            fontSize: '13px',
            colors: axisColor
          }
        }
      },
      yaxis: {
        labels: {
          show: true
        },
        min: 0,
        max: 60,
        tickAmount: 4
      }
    };
  if (typeof incomeChartEl2 !== undefined && incomeChartEl2 !== null) {
    const incomeChart2 = new ApexCharts(incomeChartEl2, incomeChartConfig2);
    incomeChart2.render();
  }

})();
