<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<div class="row clearfix">
    <div class="col-12">

        <form id="form_advanced_validation" method="POST" novalidate="novalidate">
            <?= csrf_field() ?>

            <section class="card card-fluid">
                <h5 class="card-header drag-handle">
                    Cài đặt chung
                    <div style="margin-left:auto">
                        <button class="btn btn-primary btn-sm float-right" type="submit" name="post">Cập nhật</button>
                    </div>
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pannel">
                                <h3 class="text-on-pannel text-primary"><strong class="text-uppercase">Thông báo rà soát</strong></h3>
                                <?php foreach ($mail_review as $tin) : ?>
                                    <div class="form-group row">
                                        <b class="col-12 col-sm-3 col-form-label">
                                            <?= $tin['title'] ?>:
                                            <p class="small text-muted"> <?= $tin['comment'] ?></p>
                                        </b>
                                        <div class="col-12 col-sm-8 col-lg-6 pt-1">
                                            <input type='hidden' name="id[]" value="<?= $tin['id'] ?>" />
                                            <?php if ($tin['type'] == 'password') : ?>
                                                <input class="form-control form-control-sm" type='password' name="value<?= $tin['id'] ?>" value="<?= $tin['value'] ?>" />
                                            <?php elseif ($tin['type'] == 'varchar') : ?>
                                                <input class="form-control form-control-sm" type='text' name="value<?= $tin['id'] ?>" value="<?= $tin['value'] ?>" />
                                            <?php elseif ($tin['type'] == 'text') : ?>
                                                <textarea class="form-control form-control-sm" name="value<?= $tin['id'] ?>"><?= $tin['value'] ?></textarea>
                                            <?php elseif ($tin['type'] == 'bool') : ?>
                                                <?php
                                                $checked = "";
                                                if ($tin['value'] != 0)
                                                    $checked = "checked";
                                                ?>
                                                <div class="switch-button switch-button-xs switch-button-success">
                                                    <input type="checkbox" <?= $checked ?> name="value<?= $tin['id'] ?>" id="switch<?= $tin['id'] ?>" value="1">
                                                    <span>
                                                        <label for="switch<?= $tin['id'] ?>"></label>
                                                    </span>
                                                </div>
                                            <?php elseif ($tin['type'] == 'page') : ?>
                                                <textarea class="form-control form-control-sm edit" name="value<?= $tin['id'] ?>"><?= $tin['value'] ?></textarea>
                                            <?php elseif ($tin['type'] == 'textarea') : ?>
                                                <textarea class="form-control form-control-sm" name="value<?= $tin['id'] ?>"><?= $tin['value'] ?></textarea>
                                            <?php elseif ($tin['type'] == 'select' && $tin['key'] == 'type_send') : ?>
                                                <select class="form-control form-control-sm" name="value<?= $tin['id'] ?>">
                                                    <option value='1' <?= $tin['value'] == 1 ? "selected" : "" ?>>Chỉ gửi 1 lần</option>
                                                    <option value='2' <?= $tin['value'] == 2 ? "selected" : "" ?>>Gửi mỗi ngày</option>
                                                </select>
                                            <?php elseif ($tin['type'] == 'number') : ?>
                                                <input class="form-control form-control-sm" type='number' name="value<?= $tin['id'] ?>" value='<?= $tin['value'] ?>' />
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="pannel">
                                <h3 class="text-on-pannel text-primary"><strong class="text-uppercase">Thông báo hết hạn</strong></h3>
                                <?php foreach ($mail_expire as $tin) : ?>
                                    <div class="form-group row">
                                        <b class="col-12 col-sm-3 col-form-label">
                                            <?= $tin['title'] ?>:
                                            <p class="small text-muted"> <?= $tin['comment'] ?></p>
                                        </b>
                                        <div class="col-12 col-sm-8 col-lg-6 pt-1">
                                            <input type='hidden' name="id[]" value="<?= $tin['id'] ?>" />
                                            <?php if ($tin['type'] == 'password') : ?>
                                                <input class="form-control form-control-sm" type='password' name="value<?= $tin['id'] ?>" value="<?= $tin['value'] ?>" />
                                            <?php elseif ($tin['type'] == 'varchar') : ?>
                                                <input class="form-control form-control-sm" type='text' name="value<?= $tin['id'] ?>" value="<?= $tin['value'] ?>" />
                                            <?php elseif ($tin['type'] == 'text') : ?>
                                                <textarea class="form-control form-control-sm" name="value<?= $tin['id'] ?>"><?= $tin['value'] ?></textarea>
                                            <?php elseif ($tin['type'] == 'bool') : ?>
                                                <?php
                                                $checked = "";
                                                if ($tin['value'] != 0)
                                                    $checked = "checked";
                                                ?>
                                                <div class="switch-button switch-button-xs switch-button-success">
                                                    <input type="checkbox" <?= $checked ?> name="value<?= $tin['id'] ?>" id="switch<?= $tin['id'] ?>" value="1">
                                                    <span>
                                                        <label for="switch<?= $tin['id'] ?>"></label>
                                                    </span>
                                                </div>
                                            <?php elseif ($tin['type'] == 'page') : ?>
                                                <textarea class="form-control form-control-sm edit" name="value<?= $tin['id'] ?>"><?= $tin['value'] ?></textarea>
                                            <?php elseif ($tin['type'] == 'textarea') : ?>
                                                <textarea class="form-control form-control-sm" name="value<?= $tin['id'] ?>"><?= $tin['value'] ?></textarea>
                                            <?php elseif ($tin['type'] == 'select' && $tin['key'] == 'type_send') : ?>
                                                <select class="form-control form-control-sm" name="value<?= $tin['id'] ?>">
                                                    <option value='1' <?= $tin['value'] == 1 ? "selected" : "" ?>>Chỉ gửi 1 lần</option>
                                                    <option value='2' <?= $tin['value'] == 2 ? "selected" : "" ?>>Gửi mỗi ngày</option>
                                                </select>
                                            <?php elseif ($tin['type'] == 'number') : ?>
                                                <input class="form-control form-control-sm" type='number' name="value<?= $tin['id'] ?>" value='<?= $tin['value'] ?>' />
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm float-right" type="submit" name="post">Cập nhật</button>
                </div>
            </section>
        </form>
    </div>
</div>
<style>
    .pannel {
        margin-top: 25px !important;
        padding-top: 30px !important;
        border: 1px solid #cecece;
        border-radius: 5px;
        padding: 10px;
    }

    .text-on-pannel {
        background: #fff none repeat scroll 0 0;
        height: auto;
        margin-left: 20px;
        padding: 3px 5px;
        position: absolute;
        margin-top: -47px;
        border: 1px solid #337ab7;
        border-radius: 8px;
        font-size: 20px;
    }

    @media (max-width: 576px) {
        .pannel {
            border: 0;
        }

        .text-on-pannel {
            border: 0;
            text-align: center;
            position: relative;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
    $(document).ready(function() {})
</script>
<?= $this->endSection() ?>