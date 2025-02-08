            </div>
        </div>
    </div>
</body>
</html>

<script>
    $(document).ready(function () {
        $('#logout-btn').on('click', function () {
            $.ajax({
                'url': '<?= base_url('logout-process')?>',
                'type': 'POST',
                'success': function (res) {
                    if (res.success == 1) {
                        window.location.replace("<?= base_url('/') ?>")
                    }
                }
            })
        })
    })
</script>