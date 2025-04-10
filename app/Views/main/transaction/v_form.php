<?= $this->include('template/v_header') ?>

<div id="form-card" class="w-100 bg-white rounded-3 shadow-sm p-2 mb-3">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">Transaction Form</span>
        <div>
            <?php if (!empty($data)) :?>
            <button type="button" id="btn-release" class="btn btn-success ms-2">Release</button>
            <?php endif ?>
        </div>
    </div>

    <form id="header-form">
        <input type="hidden" name="id" value="<?= encrypted($data['id']) ?>">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Transaction Code</label>
                    <input type="text" class="form-control" name="transcode" placeholder="@ex: TRC0001" value="<?= $data['transcode'] ?? '' ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <select class="form-select" id="location" name="location" required>
                        <?php if (!empty($data)) : ?>
                            <option value="<?= $data['id_location'] ?>" selected><?= $data['location'] ?></option>
                        <?php endif ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <?php if (!empty($data)) : ?>
                            <option value="<?= $data['id_category'] ?>" selected><?= $data['category'] ?></option>
                        <?php endif ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea class="form-control" name="note" rows="3"><?= $data['note'] ?? '' ?></textarea>
                </div>
            </div>
        </div>
        <div class="border-top d-flex justify-content-end pt-2 mt-4">
            <button type="button" id="btn-cancel" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<?php if (!empty($data)) :?>

<div class="w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">Transaction Details</span>
        <button type="button" id="btn-add-item" class="btn btn-primary btn-sm">Add Item</button>
    </div>

    <table id="detail-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Product</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Note</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<?php endif ?>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="detail-form">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select class="form-select" id="product" name="product" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="qty" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-detail">Add</button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        generateSelect2('#location', '<?= base_url('/location/select') ?>', 'Select Location', true);
        generateSelect2('#category', '<?= base_url('/category/select') ?>', 'Select Category', true, {
            type: 'storage'
        });
        generateSelect2('#product', '<?= base_url('/product/select') ?>', 'Select Product', true);

        <?php if (!empty($data)) :?>
            const detailTable = $('#detail-table').DataTable({
                ajax: {
                    url: '<?= base_url('/transaction/detailtable') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.transaction = $('input[name="id"]').val();
                    },
                    dataSrc: function (res) {
                        return res.data || []
                    }
                },
                columns: [
                    {data: 'no', width: '5%'},
                    {data: 'product'},
                    {data: 'qty', width: '10%'},
                    {data: 'note'},
                    {data: 'action', width: '10%'}
                ]
            });
        <?php endif ?>

        $('form').submit(function (e) {
            e.preventDefault();
            $('input').prop('readonly')

            $.ajax({
                'url': '<?= base_url('transaction/save')?>',
                'type': 'POST',
                'data': $(this).serialize(),
                'success': function (res) {
                    if (res.success == 0) {
                        if (res.error != undefined) {
                            $(`input[name="${res.error}"]`).focus()
                            $(`select[name="${res.error}"]`).focus()
                        }
                    } else if (res.success == 1) {
                        // window.location.href = res.link;
                        console.log(res);
                        // $('#example').DataTable().ajax.reload(null, true);
                    }
                }
            })
        })

        $('#btn-release').click(function() {
            const id = $('input[name="id"]').val();
            if (!id) return;
            
            $.post('<?= base_url('/transaction/release') ?>', {id}, function(res) {
                if (res.success) {
                    window.location.href = '<?= base_url('/transaction') ?>';
                } else {
                    alert(res.error);
                }
            });
        });
    });
</script>