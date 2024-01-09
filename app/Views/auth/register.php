<?= $this->extend('auth/template/index'); ?>
<?= $this->section('content'); ?>
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Buat Akunmu!</h1>
                        </div>

                        <?= view('Myth\Auth\Views\_message_block') ?>

                        <form action="<?= url_to('register') ?>" method="post" class="user">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="Masukkan Nama Pengguna" value="<?= old('username') ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user <?php if (session('errors.nama_lengkap')) : ?>is-invalid<?php endif ?>" name="nama_lengkap" placeholder="Masukkan Nama Lengkap Anda" value="<?= old('nama_lengkap') ?>">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" placeholder="Alamat Email/Gmail" value="<?= old('email') ?>">
                                <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user <?php if (session('errors.nomor_hp')) : ?>is-invalid<?php endif ?>" name="nomor_hp" placeholder="Nomor HP Anda" value="<?= old('nomor_hp') ?>">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" name="password" placeholder="Buat Kata Sandi Anda" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm" placeholder="Masukkan Kembali Kata Sandi" autocomplete="off">
                            </div>
                            <button class="btn btn-primary btn-user btn-block">
                                Buat Akun
                            </button>
                            <hr>
                        </form>
                        <div class="text-center">
                            <a class="small" href="forgot-password.html">Lupa Kata Sandi?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="/login">Sudah Punya AKun? Masuk!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>