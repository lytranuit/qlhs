<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <title>Đăng nhập</title>
    <!------ Include the above in your HEAD tag ---------->
    <style>
        .login-block {
            background-image: url(/assets/images/bg.jpg);
            background-size: cover;
            background-position: center center;
            width: 100%;
            height: 100vh;
        }

        .banner-sec {
            background-size: cover;
            border-radius: 0 10px 10px 0;
        }

        .box_login {
            background: #fff;
            border-radius: 10px;
            box-shadow: 15px 20px 0px rgb(148 108 108 / 10%)
        }

        .carousel-inner {
            border-radius: 0 10px 10px 0;
        }

        .carousel-caption {
            text-align: left;
            left: 5%;
        }

        .login-sec {
            padding: 50px 30px;
            position: relative;
        }

        .login-sec .copy-text {
            position: absolute;
            width: 80%;
            bottom: 20px;
            font-size: 13px;
            text-align: center;
        }

        .login-sec .copy-text i {
            color: #FEB58A;
        }

        .login-sec .copy-text a {
            color: #E36262;
        }

        .login-sec h2 {
            margin-bottom: 30px;
            font-weight: 800;
            font-size: 30px;
            color: #DE6262;
        }

        .login-sec h2:after {
            content: " ";
            width: 100px;
            height: 5px;
            background: #FEB58A;
            display: block;
            margin-top: 20px;
            border-radius: 3px;
            margin-left: auto;
            margin-right: auto
        }

        .btn-login {
            background: #DE6262;
            color: #fff;
            font-weight: 600;
        }

        .banner-text {
            width: 70%;
            position: absolute;
            bottom: 40px;
            padding-left: 20px;
        }

        .banner-text h2 {
            color: #fff;
            font-weight: 600;
        }

        .banner-text h2:after {
            content: " ";
            width: 100px;
            height: 5px;
            background: #FFF;
            display: block;
            margin-top: 20px;
            border-radius: 3px;
        }

        .banner-text p {
            color: #fff;
        }
    </style>
</head>

<body>
    <section class="login-block">
        <div class="container">
            <div class="row align-items-start" style="height: 100vh;">
                <div class="col-md-12  my-auto">
                    <div class="box_login">
                        <div class="row">
                            <div class="col-md-4 login-sec">
                                <h2 class="text-center"><?= lang('Custom.login') ?></h2>
                                <?= view('Myth\Auth\Views\_message_block') ?>
                                <form action="<?= route_to('login') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <input class="form-control form-control-sm" id="username" name="login" type="text" placeholder="<?= lang('Custom.login_identity_label') ?>" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control form-control-sm" id="password" name="password" type="password" placeholder="<?= lang('Custom.login_password_label') ?>">
                                    </div>


                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="remember" value="1">
                                            <small><?= lang('Custom.remember_me') ?></small>
                                        </label>
                                        <button type="submit" class="btn btn-login float-right"><?= lang('Custom.login') ?></button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-8 banner-sec">
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                    </ol>
                                    <div class="carousel-inner" role="listbox">
                                        <div class="carousel-item active">
                                            <img class="d-block img-fluid" src="<?= base_url("assets/images/nhamay2.jpg") ?>" alt="First slide">
                                            <div class="carousel-caption d-none d-md-block">
                                                <div class="banner-text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block img-fluid" src="<?= base_url("assets/images/hinh-nha-may-hoang-van-thu.jpg") ?>" alt="First slide">
                                            <div class="carousel-caption d-none d-md-block">
                                                <div class="banner-text">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </section>
</body>