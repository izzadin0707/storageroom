<?= $this->include('template/v_header') ?>

<div class="w-100 bg-white rounded-3 p-2">
    <span>Users</span>
    <div>
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Start Date</th>
                    <th>Office</th>
                    <th>Extn</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<?= $this->include('template/v_footer') ?>

<script>
    var data = [
        [
            "Tiger Nixon",
            "System Architect",
            "Edinburgh",
            "5421",
            "2011/04/25",
            "$3,120"
        ],
        [
            "Garrett Winters",
            "Director",
            "Edinburgh",
            "8422",
            "2011/07/25",
            "$5,300"
        ]
    ]

    $(document).ready(function () {
        $('#example').DataTable( {
            data: data
        } );
    })
</script>
