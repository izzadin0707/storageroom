<?= $this->include('template/v_header') ?>

<!-- FORM -->
<div id="form-card" class="d-none w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-start pb-2 mb-4 fw-semibold text-secondary">
        <span><?= $menu_title ?> Form</span>
    </div>
    <form>
        <input type="hidden" name="id" value="">
        <div class="row">
            <div class="col-6">
                <div class="mb-2">
                    <label for="nama" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Product Name</label>
                    <input type="text" class="form-control" style="font-size: .9rem;" id="nama" name="nama" placeholder="@ex: Kotak Susu" required>
                </div>
                <div class="mb-2">
                    <label for="description" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Description</label>
                    <textarea class="form-control" style="font-size: .9rem; resize: none; height: 106px;" id="description" name="description" placeholder="@ex: Rasa Vanila"></textarea>
                </div>
                <div class="mb-2">
                    <label for="expired" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Expired Date</label>
                    <input type="date" class="form-control" style="font-size: .9rem;" id="expired" name="expired" required>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label for="qty" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Qty</label>
                    <input type="number" class="form-control" style="font-size: .9rem;" id="qty" name="qty" min="0" value="0" required>
                </div>
                <div class="mb-2">
                    <label for="uom" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">UOM</label>
                    <select class="form-select" style="font-size: .9rem;" id="uom" name="uom" aria-label="Default select example"></select>
                </div>
                <div class="mb-2">
                    <label for="category" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Category</label>
                    <select class="form-select" style="font-size: .9rem;" id="category" name="category" aria-label="Default select example"></select>
                </div>
            </div>
        </div>
        <div class="border-top d-flex justify-content-end pt-2 mt-4">
            <button type="button" id="btn-cancel" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
<!-- DATATABLE -->
<div id="table-card" class="w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">General Information</span>
        <button id="btn-create" class="btn btn-primary d-flex">Create New</button>
    </div>
    <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Product Name</th>
                <th class="text-center">Category</th>
                <th class="text-center">UOM</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Expired</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        generateSelect('#category', '<?= base_url('/category/select') ?>', 'Select Category', true, {
            type: 'product'
        })
        generateSelect('#uom', '<?= base_url('/category/select') ?>', 'Select UOM', true, {
            type: 'uom'
        })

        $('#example').DataTable( {
            ajax: {
                url: '<?= base_url('/product/table') ?>',
                type: 'POST',
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '10%', className: 'text-center'},
                {data: 'nama'},
                {data: 'qty'},
                {data: 'category'},
                {data: 'uom'},
                {data: 'expired'},
                {data: 'action', width: '10%'},
            ]
        } );

        $('#btn-create').on('click', function () {
            $('#form-card').removeClass('d-none')
            $('#table-card').addClass('d-none')
            $('input[name="id"]').val(null)
        })
        
        $('#btn-cancel').on('click', function () {
            $('#form-card').addClass('d-none')
            $('#table-card').removeClass('d-none')
            $('input[name="id"]').val(null)
            if ($('#form-card').hasClass('d-none')) {
                $('#form-card').find('form').trigger('reset')
            }
        })

        $('form').submit(function (e) {
            e.preventDefault();
            $('input').prop('readonly')

            $.ajax({
                'url': '<?= base_url('product/save')?>',
                'type': 'POST',
                'data': $(this).serialize(),
                'success': function (res) {
                    if (res.success == 0) {
                        if (res.error != undefined) {
                            $(`input[name="${res.error}"]`).focus()
                            $(`select[name="${res.error}"]`).focus()
                        }
                    } else if (res.success == 1) {
                        $('#example').DataTable().ajax.reload(null, true);
                        $('#btn-cancel').trigger('click')
                    }
                }
            })
        })
    })

    function edit(e) {
        row = $(e).data('row')
        $('#form-card').removeClass('d-none')
        $('#table-card').addClass('d-none')
        $('input[name="id"]').val(row.id)
        $('input[name="nama"]').val(row.nama)
        $('textarea[name="description"]').val(row.description)
        $('input[name="qty"]').val(row.qty)
        $('input[name="expired"]').val(row.expired)
        $('select[name="uom"] option').prop('selected', false)
        $('select[name="uom"] option').prop('selected', false).filter(function() {
            return $(this).text() === row.uom;
        }).prop('selected', true);
        $('select[name="category"] option').prop('selected', false)
        $('select[name="category"] option').prop('selected', false).filter(function() {
            return $(this).text() === row.category;
        }).prop('selected', true);
    }

    function deleted(e) {
        $.ajax({
            'url': '<?= base_url('product/delete')?>',
            'type': 'POST',
            'data': {
                'id': $(e).data('id')
            },
            'success': function (res) {
                console.log(res)
                if (res.success == 0) {
                    if (res.error != undefined) alert(res.error)
                } else if (res.success == 1) {
                    $('#example').DataTable().ajax.reload(null, false);
                }
            }
        })
    }
</script>
