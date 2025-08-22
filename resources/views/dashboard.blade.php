@extends('layouts.material')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dashboard') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <label for="start_date" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-5">
                            <label for="end_date" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mb-0">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">weekend</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Ventas de Hoy</p>
            <h4 class="mb-0">${{ number_format($ventasHoy, 2) }}</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>que la semana pasada</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Productos Activos</p>
            <h4 class="mb-0">{{ $productosActivos }}</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>que el mes pasado</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Productos Bajo Stock</p>
            <h4 class="mb-0">{{ $productosBajoStock }}</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> que ayer</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">weekend</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Compras del Mes</p>
            <h4 class="mb-0">${{ number_format($comprasMes, 2) }}</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>que ayer</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
      <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
          <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
            <div class="chart">
              <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
            </div>
          </div>
        </div>
        <div class="card-body">
          <h6 class="mb-0 ">Ventas de Hoy</h6>
          <p class="text-sm">Total por hora.</p>
          <hr class="dark horizontal">
          <div class="d-flex ">
            <i class="material-icons text-sm my-auto me-1">schedule</i>
            <p class="mb-0 text-sm">Actualizado ahora</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
      <div class="card z-index-2  ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
          <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
            <div class="chart">
              <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
            </div>
          </div>
        </div>
        <div class="card-body">
          <h6 class="mb-0 "> Ventas de la Semana </h6>
          <p class="text-sm">Total por día.</p>
          <hr class="dark horizontal">
          <div class="d-flex ">
            <i class="material-icons text-sm my-auto me-1">schedule</i>
            <p class="mb-0 text-sm">Semana actual</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 mt-4 mb-3">
      <div class="card z-index-2 ">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
          <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
            <div class="chart">
              <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
            </div>
          </div>
        </div>
        <div class="card-body">
          <h6 class="mb-0 ">Ventas del Mes</h6>
          <p class="text-sm">Total por día.</p>
          <hr class="dark horizontal">
          <div class="d-flex ">
            <i class="material-icons text-sm my-auto me-1">schedule</i>
            <p class="mb-0 text-sm">Mes actual</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Predicción Ventas Diarias</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-predict-daily" class="chart-canvas" height="170"></canvas>
                </div>
                <div class="text-center mt-2">
                    <p class="text-sm mb-0">
                        {{ number_format($prediccionVentasDiarias, 0) }} unidades (~${{ number_format($prediccionValorDiario, 2) }})
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Predicción Ventas Semanales</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-predict-weekly" class="chart-canvas" height="170"></canvas>
                </div>
                 <div class="text-center mt-2">
                    <p class="text-sm mb-0">
                        {{ number_format($prediccionVentasSemanales, 0) }} unidades (~${{ number_format($prediccionValorSemanal, 2) }})
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Predicción Ventas Mensuales</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-predict-monthly" class="chart-canvas" height="170"></canvas>
                </div>
                 <div class="text-center mt-2">
                    <p class="text-sm mb-0">
                        {{ number_format($prediccionVentasMensuales, 0) }} unidades (~${{ number_format($prediccionValorMensual, 2) }})
                    </p>
                </div>
            </div>
        </div>
    </div>
  </div>
@stop

@section('js')
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");
    var conteosHoy = @json($conteosHoy);

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: @json($horasHoy),
        datasets: [{
          label: "Monto",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "rgba(255, 255, 255, .8)",
          data: @json($montosHoy),
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.y !== null) {
                  label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                }
                var conteo = `Nº Ventas: ${conteosHoy[context.dataIndex]}`;
                return [label, conteo];
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");
    var conteosSemana = @json($conteosSemana);

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: @json($diasSemana),
        datasets: [{
          label: "Monto",
          tension: 0,
          borderWidth: 0,
          pointRadius: 5,
          pointBackgroundColor: "rgba(255, 255, 255, .8)",
          pointBorderColor: "transparent",
          borderColor: "rgba(255, 255, 255, .8)",
          borderWidth: 4,
          backgroundColor: "transparent",
          fill: true,
          data: @json($montosSemana),
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.y !== null) {
                  label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                }
                var conteo = `Nº Ventas: ${conteosSemana[context.dataIndex]}`;
                return [label, conteo];
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");
    var conteosMes = @json($conteosMes);

    new Chart(ctx3, {
      type: "line",
      data: {
        labels: @json($diasMes),
        datasets: [{
          label: "Monto",
          tension: 0,
          borderWidth: 0,
          pointRadius: 5,
          pointBackgroundColor: "rgba(255, 255, 255, .8)",
          pointBorderColor: "transparent",
          borderColor: "rgba(255, 255, 255, .8)",
          borderWidth: 4,
          backgroundColor: "transparent",
          fill: true,
          data: @json($montosMes),
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.y !== null) {
                  label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                }
                var conteo = `Nº Ventas: ${conteosMes[context.dataIndex]}`;
                return [label, conteo];
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#f8f9fa',
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    // --- GRÁFICOS DE PREDICCIÓN ---
    function createPredictionChart(ctx, label, value) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [label, ''],
                datasets: [{
                    label: "Predicción",
                    weight: 9,
                    cutout: 60,
                    tension: 0.9,
                    pointRadius: 2,
                    borderWidth: 2,
                    backgroundColor: ['#2dce89', '#27293d'],
                    data: [value, 100 - value], // Asumiendo que el valor es un porcentaje
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Normalizar valores de predicción a un porcentaje para el gráfico (ejemplo simple)
    // Esto es solo una representación visual, el valor real se muestra debajo.
    var prediccionDiariaPct = ({{ $prediccionVentasDiarias }} / ({{ $prediccionVentasDiarias }} + 10)) * 100;
    var prediccionSemanalPct = ({{ $prediccionVentasSemanales }} / ({{ $prediccionVentasSemanales }} + 50)) * 100;
    var prediccionMensualPct = ({{ $prediccionVentasMensuales }} / ({{ $prediccionVentasMensuales }} + 200)) * 100;

    createPredictionChart(document.getElementById("chart-predict-daily").getContext("2d"), "Día", prediccionDiariaPct);
    createPredictionChart(document.getElementById("chart-predict-weekly").getContext("2d"), "Semana", prediccionSemanalPct);
    createPredictionChart(document.getElementById("chart-predict-monthly").getContext("2d"), "Mes", prediccionMensualPct);

  </script>
@endsection
