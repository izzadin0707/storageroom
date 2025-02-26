<?= $this->include('template/v_header') ?>

<!-- STORAGE -->
<div id="storage-card" class="w-100 bg-white rounded-3 shadow-sm p-2 mb-3">
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
<div id="table-card" class="d-none w-100 bg-white rounded-3 shadow-sm p-2">
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
<div id="form-card" class="d-none w-100 bg-white rounded-3 shadow-sm p-2 mb-3">
    <div class="border-bottom d-flex justify-content-start pb-2 mb-4 fw-semibold text-secondary">
        <span><?= $menu_title ?> Form</span>
    </div>
    <form>
        <input type="hidden" name="id" value="">
        <input type="hidden" name="location" value="">
        <div class="row">
            <div class="col-6">
                <div class="mb-2 position-relative">
                    <label for="product" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Product</label>
                    <input type="text" class="form-control" style="font-size: .9rem;" id="product-input" placeholder="Select Product" required>
                    <select class="form-select" style="font-size: .9rem; display: none; position: absolute;" id="product" name="product" aria-label="Default select example"></select>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label for="qty" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Qty</label>
                    <input type="number" class="form-control" style="font-size: .9rem;" id="qty" name="qty" min="0" value="0" required>
                </div>
            </div>
        </div>
        <div class="border-top d-flex justify-content-end pt-2 mt-4">
            <button type="button" id="btn-cancel" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        // Product Select
        $('#product-input').on('keyup', function() {
            select = $('#product')
            select.val(null)
            if ($(this).val() != '') {
                $.ajax({
                    'url' : '<?= base_url('/product/select') ?>',
                    'type': 'POST',
                    'data': {
                        search: $(this).val()
                    },
                    'success': function (res) {
                        select.html('')
                        if (res.data.length != 0) {
                            $.each(res.data, function (index, row) {
                                select.append(`<option value="${row.value}">${row.text}</option>`)
                            })
                        } else {
                            select.append(`<option selected disabled>No Result</option>`)
                        }
                        select.append(`<option disabled></option>`)
                        select.show()
                        select[0].size= select.find('option').length > 5 ? 5 : select.find('option').length;
                    }
                })
            } else {
                select.html('')
                select.hide()
            }
        })

        $('#product').on('click', function () {
            $('#product-input').val($(this).find(":selected").text())
            $(this).hide()
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
                {data: 'qty', width: '20%', className: 'text-start'},
                {data: 'action', width: '10%'},
            ]
        } );
        // Product Select

        $('#btn-create').on('click', function () {
            $('#form-card').removeClass('d-none')
            $('#table-card').addClass('d-none')
            $('input[name="id"]').val(null)
        })
        
        $('#btn-cancel').on('click', function () {
            $('#form-card').addClass('d-none')
            $('#table-card').removeClass('d-none')
            $('input[name="id"]').val(null)
            $('select[name="product"]').hide()
            if ($('#form-card').hasClass('d-none')) {
                $('#form-card').find('form').trigger('reset')
            }
        })

        $('#btn-detail-back').on('click', function () {
            $('#table-card').addClass('d-none')
            $('#storage-card').removeClass('d-none')
            $('#form-card input[name="location"]').val(null)
            $('#detail-table').DataTable().destroy()
        })

        $('form').submit(function (e) {
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
    })

    function detail(e) {
        $('#table-card').removeClass('d-none')
        $('#storage-card').addClass('d-none')
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
                {data: 'qty', width: '10%', className: 'text-start align-middle'},
                {data: 'createdby', width: '15%'},
                {data: 'action', width: '10%'},
            ]
        } );
    }

    // function edit(e) {
    //     row = $(e).data('row')
    //     $('#form-card').removeClass('d-none')
    //     $('#table-card').addClass('d-none')
    //     $('input[name="id"]').val(row.id)
    //     $('#product-input').val(row.product)
    //     $('input[name="qty"]').val(row.qty)
    //     $.ajax({
    //         'url' : '<?= base_url('/product/select') ?>',
    //         'type': 'POST',
    //         'data': {
    //             search: row.product
    //         },
    //         'success': function (res) {
    //             $('#product').html('')
    //             if (res.data.length != 0) {
    //                 $.each(res.data, function (index, row) {
    //                     $('#product').append(`<option value="${row.value}">${row.text}</option>`)
    //                 })
    //             } else {
    //                 $('#product').append(`<option selected disabled>No Result</option>`)
    //             }
    //             $('#product').append(`<option disabled></option>`)
    //             select[0].size= select.find('option').length > 5 ? 5 : select.find('option').length;

    //             $('select[name="product"] option').prop('selected', false)
    //             $('select[name="product"] option').prop('selected', false).filter(function() {
    //                 return $(this).text() === row.product;
    //             }).prop('selected', true);
    //         }
    //     })
    // }

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
</script>
