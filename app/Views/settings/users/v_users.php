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
                    <label for="username" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Username</label>
                    <input type="text" class="form-control" style="font-size: .9rem;" id="username" name="username" placeholder="@ex: user123" required>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label for="password" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Password</label>
                    <input type="text" class="form-control" style="font-size: .9rem;" id="password" name="password" placeholder="@ex: admin#1234" required>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label for="role" class="form-label fw-semibold" style="font-size: .8rem; margin-bottom: 4px;">Role</label>
                    <select class="form-select" style="font-size: .9rem;" id="role" name="role" aria-label="Default select example"></select>
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
                <th class="text-center">Username</th>
                <th class="text-center">Password</th>
                <th class="text-center">Role</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        generateSelect('#role', '<?= base_url('/role/select') ?>', 'Select Role', true)

        $('#example').DataTable( {
            ajax: {
                url: '<?= base_url('/users/table') ?>',
                type: 'POST',
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '10%', className: 'text-center'},
                {data: 'username'},
                {data: 'password'},
                {data: 'rolename'},
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
                'url': '<?= base_url('users/save')?>',
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
        $('input[name="username"]').val(row.username)
        $('input[name="password"]').val(row.password)
        $('select[name="role"] option').prop('selected', false)
        $('select[name="role"] option').prop('selected', false).filter(function() {
            return $(this).text() === row.rolename;
        }).prop('selected', true);
    }

    function deleted(e) {
        $.ajax({
            'url': '<?= base_url('users/delete')?>',
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
