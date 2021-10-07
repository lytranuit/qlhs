<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html>
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url("assets/images/favicon.png") ?>" />
    <title>
        <?= $title ?>
    </title>
    <link rel="stylesheet" href="<?= base_url("assets/admin/css/main.css?v=1") ?>" ?>
    <link rel="stylesheet" href="<?= base_url("assets/admin/css/fileicon.css") ?>" ?>
    <link rel="stylesheet" href="<?= base_url("assets/admin/css/custom.css?v=1") ?>" ?>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <?php if (!empty($stylesheet_tag)) : ?>
        <?php foreach ($stylesheet_tag as $url) : ?>
            <link href="<?= $url ?>" rel="stylesheet" />
        <?php endforeach ?>
    <?php endif ?>

    <?= $this->renderSection('style') ?>
    <script>
        var path = '<?= base_url() ?>/';
        var replacepath = ["", ""];
    </script>

</head>

<body>
    <div class="page-loader-wrapper" style="display: none; opacity: 0.5;">
        <div class="loader">
            <div class="spinner-border"></div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="app-container app-theme-white body-tabs-shadow fixed-header">
        <!-- ============================================================== -->
        <!-- header -->
        <!-- ============================================================== -->

        <?= $this->include('backend/layouts/_header') ?>
        <div class="app-main">
            <?= $this->include('backend/layouts/_sidebar') ?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>

    </div>
    <script src="<?= base_url("assets/lib/jquery/jquery.min.js") ?>"></script>
    <script src="<?= base_url('assets/lib/jquery-ui/jquery-ui.js') ?>"></script>

    <script src="<?= base_url('assets/admin/js/all.js') ?>"></script>
    <script src="<?= base_url('assets/lib/jquery-validation/jquery.validate.js') ?>"></script>
    <script src="<?= base_url('assets/lib/inputmask/js/jquery.inputmask.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/lib/moment/js/moment.js') ?>"></script>
    <script src="<?= base_url('assets/admin/js/admin.js?v=1') ?>"></script>
    <?php if (!empty($javascript_tag)) : ?>
        <?php foreach ($javascript_tag as $url) : ?>
            <script src="<?= $url ?>" type="text/javascript"></script>
        <?php endforeach ?>
    <?php endif ?>
    <?= $this->renderSection('script') ?>
</body>

</html>