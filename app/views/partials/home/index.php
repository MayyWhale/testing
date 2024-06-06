<?php
$page_id = null;
$comp_model = new SharedController;
$current_page = $this->set_current_page_link();
?>
<div>
    <div class="bg-light p-3 mb-3">
        <div class="container">
            <div class="row ">
                <div class="col-md-12 comp-grid text-center">
                    <h4>Dashboard Aplikasi Pemilihan Program Studi pada SNBP</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_siswa();  ?>
                    <a class="animated zoomIn record-count alert alert-warning" href="<?php print_link("siswa/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-user fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Siswa</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_guru();  ?>
                    <a class="animated zoomIn record-count alert alert-secondary" href="<?php print_link("guru/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Guru</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_user();  ?>
                    <a class="animated zoomIn record-count alert alert-info" href="<?php print_link("user/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-user fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">User</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_kriteria();  ?>
                    <a class="animated zoomIn record-count card bg-warning text-white" href="<?php print_link("kriteria/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-filter fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Kriteria</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_subkriteria();  ?>
                    <a class="animated zoomIn record-count card bg-success text-white" href="<?php print_link("kriteria/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-balance-scale fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Sub Kriteria</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 comp-grid">
                    <?php $rec_count = $comp_model->getcount_dataperguruantinggidanprogramstudi();  ?>
                    <a class="animated zoomIn record-count card bg-secondary text-white" href="<?php print_link("data_ptn_prodi/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-graduation-cap fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Data Perguruan Tinggi dan Program Studi</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-6 comp-grid">
                    <?php $rec_count = $comp_model->getcount_nilaisiswa();  ?>
                    <a class="animated zoomIn record-count alert alert-info" href="<?php print_link("nilai_siswa/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-book fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Nilai Siswa</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 comp-grid">
                    <?php $rec_count = $comp_model->getcount_perhitungan();  ?>
                    <a class="animated zoomIn record-count alert alert-warning" href="<?php print_link("perhitungan/") ?>">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-calculator fa-2x"></i>
                            </div>
                            <div class="col-10">
                                <div class="flex-column justify-content align-center">
                                    <div class="title">Perhitungan</div>
                                    <small class=""></small>
                                </div>
                            </div>
                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="container">
            <div class="row">
                <div class="col-md-6 comp-grid">
                    <div class="card card-body">
                        <?php
                        $chartdata = $comp_model->piechart_presentasesiswalolos();
                        ?>
                        <div>
                            <h4>Presentase Siswa Lolos</h4>
                            <small class="text-muted"></small>
                        </div>
                        <hr />
                        <canvas id="piechart_presentasesiswalolos"></canvas>
                        <script>
                            $(function() {
                                var chartData = {
                                    labels: <?php echo json_encode($chartdata['labels']); ?>,
                                    datasets: [{
                                        label: 'Presentase',
                                        backgroundColor: <?php echo json_encode($chartdata['datasets'][0]['backgroundColor']); ?>,
                                        borderWidth: 3,
                                        data: <?php echo json_encode($chartdata['datasets'][0]['data']); ?>,
                                    }]
                                }
                                var ctx = document.getElementById('piechart_presentasesiswalolos');
                                var chart = new Chart(ctx, {
                                    type: 'pie',
                                    data: chartData,
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        var label = context.label || '';
                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        if (context.raw !== null) {
                                                            label += context.raw;
                                                        }
                                                        return label;
                                                    }
                                                }
                                            },
                                            datalabels: {
                                                formatter: (value, ctx) => {
                                                    let sum = ctx.chart._metasets[0].total;
                                                    let percentage = (value * 100 / sum).toFixed(2) + "%";
                                                    return percentage;
                                                },
                                                color: '#fff',
                                            }
                                        }
                                    },
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-6 comp-grid">
                    <div class="card card-body">
                        <?php
                        $chartdata = $comp_model->piechart_sebaranpilihanptndanprogramstudi();
                        ?>
                        <div>
                            <h4>Sebaran Pilihan PTN dan Program Studi</h4>
                            <small class="text-muted"></small>
                        </div>
                        <hr />
                        <canvas id="piechart_sebaranpilihanptndanprogramstudi"></canvas>
                        <script>
                            $(function() {
                                var chartData = {
                                    labels: <?php echo json_encode($chartdata['labels']); ?>,
                                    datasets: [{
                                        label: 'Sebaran Pilihan',
                                        backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'],
                                        borderWidth: 3,
                                        data: <?php echo json_encode($chartdata['datasets'][0]['data']); ?>,
                                    }]
                                }
                                var ctx = document.getElementById('piechart_sebaranpilihanptndanprogramstudi');
                                var chart = new Chart(ctx, {
                                    type: 'pie',
                                    data: chartData,
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        var label = context.label || '';
                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        if (context.raw !== null) {
                                                            label += context.raw;
                                                        }
                                                        return label;
                                                    }
                                                }
                                            }
                                        },
                                        onClick: function(event, elements) {
                                            if (elements.length > 0) {
                                                var index = elements[0].index;
                                                var label = chartData.labels[index];
                                                var value = chartData.datasets[0].data[index];
                                                // Call AJAX to get data
                                                $.ajax({
                                                    url: "<?php echo 'http://localhost/appspk/index.php?url=SharedController/get_siswa_by_alternatif'; ?>",
                                                    method: "POST",
                                                    data: {
                                                        id_alternatif: label
                                                    },
                                                    success: function(response) {
                                                        var data = JSON.parse(response);
                                                        var modalContent = "<ul>";
                                                        data.forEach(function(item) {
                                                            modalContent += "<li>" + item.nama_siswa + " - " + item.kelas_siswa + "</li>";
                                                        });
                                                        modalContent += "</ul>";
                                                        $("#modalBody").html(modalContent);
                                                        $("#myModal").modal("show");
                                                    }
                                                });
                                            }
                                        }
                                    },
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Detail Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Data akan dimuat di sini melalui AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>