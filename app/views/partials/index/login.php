<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <style>
        body {
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4)),
                url("../../assets/images/bg1.jpeg") repeat;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-4">
                <div class="my-4 p-3 bg-light">
                    <div>
                        <h4><i class="fa fa-key"></i> User Login</h4>
                        <hr />
                        <?php $this::display_page_errors(); ?>
                        <form name="loginForm" action="<?php print_link('index/login/?csrf_token=' . Csrf::$token); ?>" class="needs-validation form page-form" method="post">
                            <div class="input-group form-group">
                                <input placeholder="Username" name="username" required="required" class="form-control" type="text" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="form-control-feedback fa fa-user"></i></span>
                                </div>
                            </div>
                            <div class="input-group form-group">
                                <input placeholder="Password" required="required" v-model="user.password" name="password" class="form-control " type="password" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="form-control-feedback fa fa-key"></i></span>
                                </div>
                            </div>
                            <div class="row clearfix mt-3 mb-3">
                                <div class="col-6">
                                    <label class="">
                                        <!-- <input value="true" type="checkbox" name="rememberme" />
                                        Remember Me -->
                                    </label>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="text-danger" data-toggle="modal" data-target="#passwordResetModal">Reset Password?</a>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary btn-block btn-md" type="submit">
                                    <i class="load-indicator">
                                        <clip-loader :loading="loading" color="#fff" size="20px"></clip-loader>
                                    </i>
                                    Login <i class="fa fa-key"></i>
                                </button>
                            </div>
                            <hr />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordResetModalLabel">Lupa Password?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tidak masalah, silahkan hubungi atau temui admin untuk reset password.</p>
                    <p><strong>Nama admin:</strong> [Isi Nama Admin]</p>
                    <p><strong>Nomor telepon:</strong> [Isi Nomor Telepon]</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>