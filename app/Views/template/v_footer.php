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
        
        $('#mobile-logout-btn').on('click', function() {
            $('#logout-btn').trigger('click');
        });
        
        // Fix for navbar toggle
        $('.navbar-toggler').on('click', function() {
            if ($('#mobileNavContent').hasClass('show')) {
                $('#mobileNavContent').removeClass('show');
            } else {
                $('#mobileNavContent').addClass('show');
            }
        });
        
        // Close navbar when clicking on a menu item
        $('.mobile-navbar .nav-link').on('click', function() {
            if ($('#mobileNavContent').hasClass('show')) {
                $('#mobileNavContent').removeClass('show');
            }
        });
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

    // Generate Select2
    function generateSelect2(element, url, placeholder = null, required = false, data = {}) {
        $(element).select2({
            theme: 'bootstrap-5',
            placeholder: placeholder,
            allowClear: !required,
            ajax: {
                url: url,
                type: 'POST', // Menggunakan POST sesuai controller Anda
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term || '',
                        page: params.page || 1,
                        ...data
                    };
                },
                processResults: function(response, params) {
                    return {
                        // Menyesuaikan dengan format response controller Anda
                        results: response.data.map(function(item) {
                            return {
                                id: item.value,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            templateResult: formatResult,
            templateSelection: formatSelection
        });
    }

    // Format hasil pencarian
    function formatResult(item) {
        if (item.loading) {
            return item.text;
        }
        
        if (!item.id) {
            return item.text;
        }
        
        var $container = $(
            '<div class="select2-result-item">' +
                '<div class="select2-result-item__title">' + item.text + '</div>' +
            '</div>'
        );
        
        return $container;
    }

    // Format item yang terpilih
    function formatSelection(item) {
        return item.text || item.id;
    }
</script>