<?php
$comp_model = new SharedController;
$page_element_id = "add-page-" . random_str();
$current_page = $this->set_current_page_link();
$csrf_token = Csrf::$token;
$show_header = $this->show_header;
$view_title = $this->view_title;
$redirect_to = $this->redirect_to;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="add" data-display-type="" data-page-url="<?php print_link($current_page); ?>">
    <?php
    if ($show_header == true) {
    ?>
        <div class="bg-light p-3 mb-3">
            <div class="container">
                <div class="row ">
                    <div class="col ">
                        <a class="btn btn-primary" href="<?php print_link("user/list") ?>">
                            kembali
                        </a>
                    </div>
                    <div class="col-md-8 comp-grid">
                        <h4 class="record-title">Tambah Data Pengguna</h4>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="">
        <div class="container">
            <div class="row ">
                <div class="col-md-7 comp-grid">
                    <?php $this::display_page_errors(); ?>
                    <div class="bg-light p-3 animated fadeIn page-content">
                        <form id="user-add-form" role="form" novalidate enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("user/add?csrf_token=$csrf_token") ?>" method="post">
                            <div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="username">Username <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-username" value="<?php echo $this->set_field_value('username', ""); ?>" type="text" placeholder="Masukkan Username" required="" name="username" data-url="api/json/user_username_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                <div class="check-status"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nama_pengguna">Nama Pengguna <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nama_pengguna" value="<?php echo $this->set_field_value('nama_pengguna', ""); ?>" type="text" placeholder="Masukkan Nama Pengguna" required="" name="nama_pengguna" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="user_role_id">Level Pengguna <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <select required="" id="ctrl-user_role_id" name="user_role_id" placeholder="Pilih Level Pengguna ..." class="custom-select" onchange="showNisNipInput(this.value)">
                                                    <option value="">Pilih Level Pengguna ...</option>
                                                    <?php
                                                    $user_role_id_options = $comp_model->user_user_role_id_option_list();
                                                    if (!empty($user_role_id_options)) {
                                                        foreach ($user_role_id_options as $option) {
                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                            $selected = $this->set_field_selected('user_role_id', $value, "");
                                                    ?>
                                                            <option <?php echo $selected; ?> value="<?php echo $value; ?>">
                                                                <?php echo $label; ?>
                                                            </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="nis_input" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nis">NIS <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nis" value="<?php echo $this->set_field_value('nis', ""); ?>" type="text" placeholder="Masukkan NIS" name="nis" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="nip_input" style="display:none;">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nip">NIP <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nip" value="<?php echo $this->set_field_value('nip', ""); ?>" type="text" placeholder="Masukkan NIP" name="nip" class="form-control " />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="password">Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input id="ctrl-password" value="<?php echo $this->set_field_value('password', ""); ?>" type="password" placeholder="Enter Password" maxlength="255" required="" name="password" class="form-control  password password-strength" />
                                                <div class="input-group-append cursor-pointer btn-toggle-password">
                                                    <span class="input-group-text"><i class="fa fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="password-strength-msg">
                                                <small class="font-weight-bold">Should contain</small>
                                                <small class="length chip">6 Characters minimum</small>
                                                <small class="caps chip">Capital Letter</small>
                                                <small class="number chip">Number</small>
                                                <small class="special chip">Symbol</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input id="ctrl-password-confirm" data-match="#ctrl-password" class="form-control password-confirm " type="password" name="confirm_password" required placeholder="Confirm Password" />
                                                <div class="input-group-append cursor-pointer btn-toggle-password">
                                                    <span class="input-group-text"><i class="fa fa-eye"></i></span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Password does not match
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-email" value="<?php echo $this->set_field_value('email', ""); ?>" type="email" placeholder="Masukkan Email" required="" name="email" data-url="api/json/user_email_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control  ctrl-check-duplicate" />
                                                <div class="check-status"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="photo">Photo </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <div class="dropzone " input="#ctrl-photo" fieldname="photo" data-multiple="false" dropmsg="Choose files or drag and drop files to upload" btntext="Browse" extensions=".jpg,.png,.gif,.jpeg" filesize="3" maximum="1">
                                                    <input name="photo" id="ctrl-photo" class="dropzone-input form-control" value="<?php echo $this->set_field_value('photo', ""); ?>" type="text" />
                                                    <!--<div class="invalid-feedback animated bounceIn text-center">Please a choose file</div>-->
                                                    <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-submit-btn-holder text-center mt-3">
                                <div class="form-ajax-status"></div>
                                <button class="btn btn-primary" type="submit">
                                    Simpan
                                    <i class="fa fa-send"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function showNisNipInput(value) {
        console.log("Role ID selected: ", value); // Debugging line
        // Ganti dengan role ID sebenarnya dari database Anda
        const siswa_role_id = "3"; // Misal: 3 untuk siswa
        const guru_role_id = "2"; // Misal: 2 untuk guru

        if (value == siswa_role_id) {
            console.log("Showing NIS input");
            document.getElementById('nis_input').style.display = 'block';
            document.getElementById('nip_input').style.display = 'none';
        } else if (value == guru_role_id) {
            console.log("Showing NIP input");
            document.getElementById('nis_input').style.display = 'none';
            document.getElementById('nip_input').style.display = 'block';
        } else {
            console.log("Hiding both NIS and NIP inputs");
            document.getElementById('nis_input').style.display = 'none';
            document.getElementById('nip_input').style.display = 'none';
        }
    }
</script>