<?php
$comp_model = new SharedController;
$page_element_id = "edit-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$data = $this->view_data;
$page_id = $this->route->page_id;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit" data-display-type="" data-page-url="<?php print_link($current_page); ?>">
        <?php if ($show_header == true) { ?>
            <div class="bg-light p-3 mb-3">
                <div class="container">
                    <div class="row ">
                        <div class="col ">
                            <a class="btn btn-primary" href="<?php print_link("nilai/list") ?>">
                                kembali
                            </a>
                        </div>
                        <div class="col-md-8 comp-grid">
                            <h4 class="record-title">Edit Data Nilai Siswa</h4>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="">
            <div class="container">
                <div class="row ">
                    <div class="col-md-7 comp-grid">
                        <?php $this::display_page_errors(); ?>
                        <div class="bg-light p-3 animated fadeIn page-content">
                            <form id="nilai-edit-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("nilai/edit/$page_id/?csrf_token=$csrf_token") ?>" method="post">
                                <div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="id_alternatif">Alternatif <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <select id="ctrl-id_alternatif" name="id_alternatif" required="" class="custom-select select2">
                                                        <option value="">Pilih Alternatif...</option>
                                                        <?php
                                                        $id_alternatif_options = $comp_model->nilai_id_alternatif_option_list();
                                                        if (!empty($id_alternatif_options)) {
                                                            foreach ($id_alternatif_options as $option) {
                                                                $value = (!empty($option['value']) ? $option['value'] : null);
                                                                $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                $selected = $this->set_field_selected('id_alternatif', $value, $data['id_alternatif'] ?? "");
                                                        ?>
                                                                <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                    <?php echo $label; ?>
                                                                </option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <small class="form-text text-muted">Pilih alternatif yang tersedia dari daftar.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    // Fetch all criteria from the kriteria table
                                    $kriteria_options = $comp_model->get_kriteria_options();
                                    $nilai = isset($data['nilai']) ? $data['nilai'] : [];
                                    $kriteria_ids = isset($data['id_kriteria']) ? $data['id_kriteria'] : [];
                                    foreach ($kriteria_options as $index => $kriteria) {
                                        $id_kriteria = $kriteria['id_kriteria'];
                                        $nama_kriteria = $kriteria['nama_kriteria'];
                                        $placeholder = "Masukkan nilai untuk " . $nama_kriteria;
                                        $help_text = ""; // Tambahkan help text untuk setiap kriteria
                                        switch ($nama_kriteria) {
                                            case "Nilai Raport":
                                                $help_text = "Gunakan Tanda Titik (.) untuk nilai raport.";
                                                break;
                                            case "Nilai Mapel Pendukung":
                                                $help_text = "Gunakan Tanda Titik (.) untuk nilai mapel pendukung.";
                                                break;
                                            case "Prestasi":
                                                $help_text = "Masukkan jumlah prestasi yang dimiliki.";
                                                break;
                                            case "Kesesuaian Pilihan":
                                                $help_text = "Pilih kesesuaian pilihan dengan jurusan SMA/SMK/MA.";
                                                break;
                                            case "Alumni PTN":
                                                $help_text = "Masukkan jumlah alumni di PTN pilihan.";
                                                break;
                                            case "Alumni Prodi":
                                                $help_text = "Masukkan jumlah alumni di prodi pilihan.";
                                                break;
                                            default:
                                                $help_text = "Masukkan nilai untuk " . $nama_kriteria . ".";
                                        }
                                        $required = "required"; // Semua input harus diisi

                                        // Check if there are sub-criteria (variabel) for this kriteria
                                        $variabel_options = $comp_model->get_variabel_options($id_kriteria);
                                        if (!empty($variabel_options)) {
                                    ?>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="k<?php echo $id_kriteria; ?>"><?php echo $nama_kriteria; ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <select required="" id="ctrl-k<?php echo $id_kriteria; ?>" name="nilai[<?php echo $id_kriteria; ?>]" class="custom-select select2">
                                                                <option value="">Pilih <?php echo $nama_kriteria; ?> ...</option>
                                                                <?php
                                                                foreach ($variabel_options as $option) {
                                                                    $value = (!empty($option['value']) ? $option['value'] : null);
                                                                    $label = (!empty($option['label']) ? $option['label'] : $value);
                                                                    $selected = $this->set_field_selected('nilai[' . $id_kriteria . ']', $value, $nilai[$index] ?? "");
                                                                ?>
                                                                    <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                        <?php echo $label; ?>
                                                                    </option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <small class="form-text text-muted"><?php echo $help_text; ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="form-group ">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="k<?php echo $id_kriteria; ?>"><?php echo $nama_kriteria; ?> <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="">
                                                            <input id="ctrl-k<?php echo $id_kriteria; ?>" value="<?php echo $nilai[$index] ?? ""; ?>" type="text" placeholder="<?php echo $placeholder; ?>" <?php echo $required; ?> name="nilai[<?php echo $id_kriteria; ?>]" class="form-control " />
                                                            <small class="form-text text-muted"><?php echo $help_text; ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="form-group form-submit-btn-holder text-center mt-3">
                                        <div class="form-ajax-status"></div>
                                        <button class="btn btn-primary" type="submit">
                                            Simpan
                                            <i class="fa fa-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>

</html>