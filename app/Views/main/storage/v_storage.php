<?= $this->include('template/v_header') ?>

<!-- STORAGE -->
<div id="storage-card" class="card-container w-100 bg-white rounded-3 shadow-sm p-2 mb-3">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">General Information</span>
    </div>
    <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Location</th>
                <th class="text-center">Product</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- DATATABLE -->
<div id="table-card" class="card-container d-none w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary detail-title">General Information</span>
        <div class="d-flex flex-nowrap gap-2">
            <button id="btn-detail-back" class="btn btn-secondary d-flex">Back</button>
            <button id="btn-create" class="btn btn-primary d-flex">Create New</button>
        </div>
    </div>
    <table id="detail-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Code</th>
                <th class="text-center">Product Name</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Created By</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- FORM -->
<div id="form-card" class="card-container d-none w-100 bg-white rounded-3 shadow-sm p-2 mb-3">
    <div class="border-bottom d-flex justify-content-start pb-2 mb-4 fw-semibold text-secondary">
        <span><?= $menu_title ?> Form</span>
    </div>
    <form id="form-storage">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="location" value="">
        <div class="row">
            <div class="col-6">
                <div class="mb-2 position-relative">
                    <label for="product" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Product</label>
                    <select class="form-select" id="product" name="product"></select>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label for="qty" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Qty</label>
                    <input type="number" class="form-control" id="qty" name="qty" value="0" required>
                </div>
            </div>
        </div>
        <div class="border-top d-flex justify-content-end pt-2 mt-4">
            <button type="button" id="btn-cancel" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<!-- TRANSACTION -->
<div id="transaction-card" class="card-container d-none w-100 bg-white rounded-3 shadow-sm p-2 mb-3">
    <div class="border-bottom d-flex justify-content-start pb-2 mb-4 fw-semibold text-secondary">
        <span class="transaction-title">General Information</span>
    </div>
    <form id="form-transaction">
        <input type="hidden" name="id" value="">
        <div class="row">
            <div class="col-6">
                <div class="mb-2 position-relative">
                    <label for="type" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Transaction Type</label>
                    <select class="form-select" id="type" name="type"></select>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label for="qty" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Qty</label>
                    <input type="number" class="form-control" id="qty" name="qty" min="0" value="0" required>
                </div>
            </div>
        </div>
        <div class="border-top d-flex justify-content-end pt-2 mt-4">
            <button type="button" id="btn-cancel-transaction" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        generateSelect2('#product', '<?= base_url('/product/select') ?>', 'Select Product', true)
        generateSelect2('#type', '<?= base_url('/category/select') ?>', 'Select Transaction', true, {
            type: 'transaction'
        })

        $('#example').DataTable( {
            ajax: {
                url: '<?= base_url('/storage/table') ?>',
                type: 'POST',
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '10%', className: 'text-center'},
                {data: 'nama'},
                {data: 'qty', width: '20%', className: 'text-end'},
                {data: 'action', width: '10%'},
            ]
        } );

        $('#btn-create').on('click', function () {
            showCard('form-card')
            $('input[name="id"]').val(null)
        })
        
        $('#btn-cancel').on('click', function () {
            showCard('table-card')
            $('input[name="id"]').val(null)
            $('select[name="product"]').html(null).trigger('change')
            if ($('#form-card').hasClass('d-none')) {
                $('#form-card').find('form').trigger('reset')
            }
        })

        $('#btn-cancel-transaction').on('click', function () {
            showCard('table-card')
            $('input[name="id"]').val(null)
            $('select[name="qty"]').val(null)
            $('select[name="type"]').html(null).trigger('change')
            if ($('#transaction-card').hasClass('d-none')) {
                $('#transaction-card').find('form').trigger('reset')
            }
        })

        $('#btn-detail-back').on('click', function () {
            showCard('storage-card')
            $('#form-card input[name="location"]').val(null)
            $('#detail-table').DataTable().destroy()
        })

        $('#form-storage').submit(function (e) {
            e.preventDefault();
            $('input').prop('readonly')

            $.ajax({
                'url': '<?= base_url('storage/save')?>',
                'type': 'POST',
                'data': $(this).serialize(),
                'success': function (res) {
                    if (res.success == 0) {
                        if (res.error != undefined) {
                            $(`input[name="${res.error}"]`).focus()
                            $(`select[name="${res.error}"]`).focus()
                        }
                    } else if (res.success == 1) {
                        $('#detail-table').DataTable().ajax.reload(null, true);
                        $('#btn-cancel').trigger('click')
                    }
                }
            })
        })

        $('#form-transaction').submit(function (e) {
            e.preventDefault();
            $('input').prop('readonly')

            $.ajax({
                'url': '<?= base_url('storage/transaction')?>',
                'type': 'POST',
                'data': $(this).serialize(),
                'success': function (res) {
                    if (res.success == 0) {
                        if (res.error != undefined) {
                            $(`input[name="${res.error}"]`).focus()
                            $(`select[name="${res.error}"]`).focus()
                        }
                    } else if (res.success == 1) {
                        $('#detail-table').DataTable().ajax.reload(null, true);
                        $('#btn-cancel-transaction').trigger('click')
                    }
                }
            })
        })
    })

    function detail(e) {
        showCard('table-card')
        $('.detail-title').text(`${$(e).data('location')} - General Information`)
        $('#form-card input[name="location"]').val($(e).data('id'))
        $('#detail-table').DataTable( {
            ajax: {
                url: '<?= base_url('/storage/detailtable') ?>',
                type: 'POST',
                data: {
                    location: $(e).data('id')
                },
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '10%', className: 'text-center align-middle'},
                {data: 'code', className: 'align-middle'},
                {data: 'nama', className: 'align-middle'},
                {data: 'qty', width: '10%', className: 'text-end align-middle'},
                {data: 'createdby', width: '15%'},
                {data: 'action', width: '10%'},
            ]
        } );
    }

    function edit(e) {
        row = $(e).data('row')
        showCard('form-card')
        $('input[name="id"]').val(row.id)
        $('input[name="qty"]').val(row.qty)
        $('select[name="product"]').append('<option value="' + row.id_product + '" selected>' + row.product + '</option>')
    }

    function deleted(e) {
        $.ajax({
            'url': '<?= base_url('storage/delete')?>',
            'type': 'POST',
            'data': {
                'id': $(e).data('id')
            },
            'success': function (res) {
                console.log(res)
                if (res.success == 0) {
                    if (res.error != undefined) alert(res.error)
                } else if (res.success == 1) {
                    $('#detail-table').DataTable().ajax.reload(null, false);
                }
            }
        })
    }

    function transaction(e) {
        showCard('transaction-card')
        $('.transaction-title').text(`${$(e).data('product')} - QTY: ${$(e).data('qty')}`)
        $('#transaction-card input[name="id"]').val($(e).data('id'))
    }

    function showCard(id) {
        $('.card-container').each(function (e) {
            if ($(this).attr('id') == id) {
                $(this).removeClass('d-none')
            } else {
                $(this).addClass('d-none')
            }
        })
    }
</script>
