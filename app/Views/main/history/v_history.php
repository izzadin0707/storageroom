<?= $this->include('template/v_header') ?>

<!-- DATATABLE -->
<div id="table-card" class="w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">History Information</span>
    </div>
    <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Product Name</th>
                <th class="text-center">Location</th>
                <th class="text-center">Type</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Created By</th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        $('#example').DataTable( {
            ajax: {
                url: '<?= base_url('/history/table') ?>',
                type: 'POST',
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '10%', className: 'text-center align-middle'},
                {data: 'product', className: 'align-middle'},
                {data: 'location', className: 'align-middle'},
                {data: 'type', width: '10%', className: 'text-center align-middle'},
                {data: 'qty', width: '10%', className: 'text-start align-middle'},
                {data: 'createdby'},
            ]
        } );
    })
</script>
