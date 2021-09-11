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
                    <?php foreach ($tins as $tin) : ?>

                        <div class="form-group row">
                            <b class="col-12 col-sm-3 col-form-label text-sm-right">
                                <?= $tin['title'] ?>:
                                <p class="small text-muted"> <?= $tin['comment'] ?></p>
                            </b>
                            <div class="col-12 col-sm-8 col-lg-6 pt-1">
                                <input type='hidden' name="id[]" value="<?= $tin['id'] ?>" />
                                <?php if ($tin['type'] == 'varchar') : ?>
                                    <input class="form-control" type='text' name="value[]" value="<?= $tin['value'] ?>" />
                                <?php elseif ($tin['type'] == 'text') : ?>

                                    <textarea class="form-control" name="value[]"><?= $tin['value'] ?></textarea>
                                <?php elseif ($tin['type'] == 'bool') : ?>
                                    <?php
                                    $checked = "";
                                    if ($tin['value'] != 0)
                                        $checked = "checked";
                                    ?>
                                    <div class="switch-button switch-button-success">
                                        <input type="checkbox" <?= $checked ?> name="value[]" id="switch<?= $tin['id'] ?>" value="1">
                                        <span>
                                            <label for="switch<?= $tin['id'] ?>"></label>
                                        </span>
                                    </div>
                                <?php elseif ($tin['type'] == 'page') : ?>
                                    <textarea class="form-control edit" name="value[]"><?= $tin['value'] ?></textarea>
                                <?php elseif ($tin['type'] == 'number') : ?>
                                    <input class="form-control" type='number' name="value[]" value='<?= $tin['value'] ?>' />
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm float-right" type="submit" name="post">Cập nhật</button>
                </div>
            </section>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        //$('.edit').froalaEditor({
        //    heightMin: 200,
        //    heightMax: 500, // Set the image upload URL.
        //    imageUploadURL: '<?= base_url() ?>admin/uploadimage',
        //    // Set request type.
        //    imageUploadMethod: 'POST',
        //    // Set max image size to 5MB.
        //    imageMaxSize: 5 * 1024 * 1024,
        //    // Allow to upload PNG and JPG.
        //    imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],
        //    htmlRemoveTags: [],
        //});
    })
</script>
<?= $this->endSection() ?>