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
    
    function generateSelect(element, url, placeholder = null, required = false, data = {}) {
        $(element).html('');
        if (placeholder != null) $(element).append(`<option selected ${required ? 'disabled' : ''}>${placeholder}</option>`)
        $.ajax({
            'url' : url,
            'type': 'POST',
            'data': data,
            'success': function (res) {
                $.each(res.data, function (index, row) {
                    $(element).append(`<option value="${row.value}">${row.text}</option>`)
                })
            }
        })
    }
</script>