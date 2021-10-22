<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <h5 class="card-header drag-handle">
                <?php if (in_groups(array("admin", "editor"))) : ?>
                    <a class="btn btn-success btn-sm" href="<?= base_url("admin/$controller/add") ?>">Thêm</a>

                    <div style="margin-left:auto;">
                        <a class="btn btn-sm btn-primary" id='save' href="#">Save</a>
                    </div>
                <?php endif ?>
            </h5>
            <div class="card-body">
                <div class="dd" id="nestable2">
                    <?= $html_nestable ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Style --->
<?= $this->section("style") ?>

<link rel="stylesheet" href="<?= base_url("assets/lib/sortable/sortable.css") ?> " ?>
<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url("assets/lib/sortable/jquery.mjs.nestedSortable.js") ?>"></script>
<script type="text/javascript">
    var controller = '<?= $controller ?>';
    $(document).ready(function() {
        $('#nestable').nestedSortable({
            forcePlaceholderSize: true,
            items: 'li',
            opacity: .6,
            placeholder: 'dd-placeholder',
        });
        $("#save").click(function() {

            var arraied = $('#nestable').nestedSortable('toArray', {
                excludeRoot: true
            });

            $.ajax({
                type: "POST",
                data: {
                    '<?= csrf_token() ?>': "<?= csrf_hash() ?>",
                    data: JSON.stringify(arraied)
                },
                url: path + `admin/${controller}/saveorder`,
                success: function(msg) {
                    alert("Success!");
                }
            })
        });
        $(document).off("click", ".dd-item-delete").on("click", ".dd-item-delete", async function() {
            var parent = $(this).closest(".dd-item");
            var id = parent.data("id");
            var array = [id];
            $(".dd-item", parent).each(function() {
                var id = $(this).data("id");
                array.push(id);
            });
            var r = confirm("Delete it?");
            if (r == true) {
                var promiseAll = [];
                for (var i = 0; i < array.length; i++) {
                    var id = array[i]
                    var promise = $.ajax({
                        type: "POST",
                        data: {
                            '<?= csrf_token() ?>': "<?= csrf_hash() ?>",
                            id: id
                        },
                        url: path + `admin/${controller}/deletemenu`
                    })
                    promiseAll.push(promise);
                }
                await Promise.all(promiseAll);
                location.reload();
            }
        })
        $(document).on("click", ".dd-handle", function() {
            // $(".showhide", $(this)).html("-")
            let parent = $(this).closest(".dd-item");
            $(".dd-list", parent).toggle();
            if ($(".dd-list", parent).is(":hidden")) {
                $(".showhide", $(this)).html("+")
            } else {
                $(".showhide", $(this)).html("-")
            }
        })
    });
</script>

<?= $this->endSection() ?>