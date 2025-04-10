<?= $this->include('template/v_header') ?>

<div id="table-card" class="w-100 bg-white rounded-3 shadow-sm p-2">
    <div class="border-bottom d-flex justify-content-between align-items-center pb-2 mb-4">
        <span class="fw-semibold text-secondary">Stock Transaction List</span>
        <a href="<?= base_url('/transaction/form') ?>" id="btn-create" class="btn btn-primary d-flex">Create New</a>
    </div>
    <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Transaction Code</th>
                <th class="text-center">Location</th>
                <th class="text-center">Category</th>
                <th class="text-center">Date</th>
                <th class="text-center">Items</th>
                <th class="text-center">Status</th>
                <th class="text-center">Created By</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            ajax: {
                url: '<?= base_url('/transaction/table') ?>',
                type: 'POST',
                dataSrc: function (res) {
                    return res.data || []
                }
            },
            columns: [
                {data: 'no', width: '5%'},
                {data: 'transcode'},
                {data: 'location'},
                {data: 'category'},
                {data: 'date'},
                {data: 'items', className: 'text-center'},
                {data: 'status', className: 'text-center'},
                {data: 'createdby'},
                {data: 'action', width: '15%', className: 'text-center'},
            ]
        });
    });
</script>
