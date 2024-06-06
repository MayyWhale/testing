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

// Pastikan bahwa user_role_id diinisialisasi dengan benar
$user_role_id = isset($data['user_role_id']) ? $data['user_role_id'] : null;
?>
<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit" data-display-type="" data-page-url="<?php print_link($current_page); ?>">
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
                        <h4 class="record-title">Edit Pengguna</h4>
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
                        <form novalidate id="user-edit-form" role="form" enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("user/edit/$page_id/?csrf_token=$csrf_token"); ?>" method="post">
                            <div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="username">Username <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-username" value="<?php echo $data['username']; ?>" type="text" placeholder="Masukkan Username" required="" name="username" data-url="api/json/user_username_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control ctrl-check-duplicate" />
                                                <div class="check-status"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="nama_pengguna">Nama Pengguna <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-nama_pengguna" value="<?php echo $data['nama_pengguna']; ?>" type="text" placeholder="Masukkan Nama Pengguna" required="" name="nama_pengguna" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($user_role_id == 3) : // Siswa 
                                ?>
                                    <div class="form-group" id="nis_input">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="nis">NIS <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input id="ctrl-nis" value="<?php echo $data['nis']; ?>" type="text" placeholder="Masukkan NIS" name="nis" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($user_role_id == 2) : // Guru 
                                ?>
                                    <div class="form-group" id="nip_input">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label" for="nip">NIP <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <input id="ctrl-nip" value="<?php echo $data['nip']; ?>" type="text" placeholder="Masukkan NIP" name="nip" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="password">Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input id="ctrl-password" value="<?php echo $data['password']; ?>" type="password" placeholder="Enter Password" maxlength="255" required="" name="password" class="form-control password password-strength" />
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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input id="ctrl-password-confirm" data-match="#ctrl-password" class="form-control password-confirm" type="password" name="confirm_password" required placeholder="Confirm Password" />
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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <input id="ctrl-email" value="<?php echo $data['email']; ?>" type="email" placeholder="Masukkan Email" required="" name="email" data-url="api/json/user_email_value_exist/" data-loading-msg="Checking availability ..." data-available-msg="Available" data-unavailable-msg="Not available" class="form-control ctrl-check-duplicate" />
                                                <div class="check-status"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="photo">Photo </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="">
                                                <div class="dropzone" input="#ctrl-photo" fieldname="photo" data-multiple="false" dropmsg="Choose files or drag and drop files to upload" btntext="Browse" extensions=".jpg,.png,.gif,.jpeg" filesize="3" maximum="1">
                                                    <input name="photo" id="ctrl-photo" class="dropzone-input form-control" value="<?php echo $data['photo']; ?>" type="text" />
                                                    <!--<div class="invalid-feedback animated bounceIn text-center">Please a choose file</div>-->
                                                    <div class="dz-file-limit animated bounceIn text-center text-danger"></div>
                                                </div>
                                            </div>
                                            <?php Html::uploaded_files_list($data['photo'], '#ctrl-photo'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-ajax-status"></div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary" type="submit">
                                    Update
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