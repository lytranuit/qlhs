<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/lib/bootstrap/css/bootstrap.min.css">
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            font-family: 'Circular Std Book';
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            color: #71748d;
            background-color: #efeff6;
            -webkit-font-smoothing: antialiased;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            font-size: 14px;
        }

        .splash-container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            margin: auto;
        }

        .splash-container .card-header {
            padding: 20px;
        }

        .splash-description {
            text-align: center;
            display: block;
            line-height: 20px;
            font-size: 1rem;
            margin-top: 5px;
            padding-bottom: 10px;
        }

        .splash-title {
            text-align: center;
            display: block;
            font-size: 14px;
            font-weight: 300;
        }

        .splash-container .card-footer-item {
            padding: 12px 28px;
        }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card">
            <div class="card-header text-center"><span><?= lang('Custom.login') ?></span></div>
            <div class="card-body">

                <?= view('Myth\Auth\Views\_message_block') ?>

                <form action="<?= route_to('login') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <input class="form-control" id="username" name="login" type="text" placeholder="<?= lang('Custom.login_identity_label') ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control" id="password" name="password" type="password" placeholder="<?= lang('Custom.login_password_label') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="button_login"><?= lang('Custom.login') ?> </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>