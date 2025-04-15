<?= $this->include('template/v_header') ?>

<div class="w-100 mb-4">
    <div class="d-flex justify-content-between gap-3">
        <!-- PRODUCT -->
        <div class="w-100 bg-white rounded-3 shadow-sm py-2 px-3">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <i class="bx bxs-package fs-1 text-muted me-2"></i>
                    <span class="fs-4 fw-semibold text-muted">Product</span>
                </div>
                <span class="fs-4 fw-semibold text-muted"><?= $product ?></span>
            </div>
        </div>
        <!-- STORAGE -->
        <div class="w-100 bg-white rounded-3 shadow-sm py-2 px-3">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <i class="bx bxs-layer fs-1 text-muted me-2"></i>
                    <span class="fs-4 fw-semibold text-muted">Storage</span>
                </div>
                <span class="fs-4 fw-semibold text-muted"><?= $storage ?></span>
            </div>
        </div>
        <!-- LOCATION -->
        <div class="w-100 bg-white rounded-3 shadow-sm py-2 px-3">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <i class="bx bxs-customize fs-1 text-muted me-2"></i>
                    <span class="fs-4 fw-semibold text-muted">Location</span>
                </div>
                <span class="fs-4 fw-semibold text-muted"><?= $location ?></span>
            </div>
        </div>
        <!-- HISTORY -->
        <div class="w-100 bg-white rounded-3 shadow-sm py-2 px-3">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <i class="bx bxs-report fs-1 text-muted me-2"></i>
                    <span class="fs-4 fw-semibold text-muted">History</span>
                </div>
                <span class="fs-4 fw-semibold text-muted"><?= $history ?></span>
            </div>
        </div>
    </div>
</div>

<!-- DATATABLE -->
<div id="table-card" class="w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">Product Stock</span>
    </div>
    <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Product</th>
                <th class="text-center">Description</th>
                <th class="text-center">UOM</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Location</th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {

        generateSelect2('#category', '<?= base_url('/category/select') ?>', 'Select Category', true, {
            type: 'product'
        })
        generateSelect2('#uom', '<?= base_url('/category/select') ?>', 'Select UOM', true, {
            type: 'uom'
        })

        $('#example').DataTable( {
            ajax: {
                url: '<?= base_url('/dashboard/table') ?>',
                type: 'POST',
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '10%', className: 'text-center'},
                {data: 'product'},
                {data: 'description'},
                {data: 'uom'},
                {data: 'qty', width: '12%'},
                {data: 'location'}
            ]
        } );
    })
</script>
