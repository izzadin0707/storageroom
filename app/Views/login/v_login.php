<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StorageRoom</title>
    <?= $this->include('template/v_import') ?>
</head>
<style>
    ::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .container-fluid {
        background: linear-gradient(217deg, rgba(182,235,255,.8), rgba(182,235,255,0) 70.71%),
                            linear-gradient(127deg, rgba(137,142,255,.8), rgba(137,142,255,0) 70.71%),
                            linear-gradient(336deg, rgba(216,175,255,.8), rgba(216,175,255,0) 70.71%);
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
    }

    @media screen and (max-width: 720px) {
        #login-container {
            width: 100vw !important;
        }
    }
</style>
<body>
    <div class="container-fluid p-0 vw-100 vh-100 d-flex align-items-center justify-content-center">
        <div id="login-container" class="d-flex flex-column justify-content-between align-items-center m-0 shadow rounded-4 bg-white" style="width: 27rem; aspect-ratio: 1/1;">
            <div class="d-flex gap-2 align-items-center flex-fill" style="color: #676df0;">
                <i class="bx bxs-package" style="font-size: 2.5rem; transform: translateY(1px);"></i>
                <span class="fs-3"><span class="fw-semibold">Storage</span>Room</span>
            </div>
            <div class="w-100 px-4 flex-fill">
                <form id="login-form" class="w-100">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Username" autocomplete="true" required>
                        <label for="floatingUsername">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" autocomplete="true" required>
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div id="submit">
                        <button type="submit" class="btn btn-primary w-100 py-2" style="transition: .1s ease-in-out;">LOGIN</button>
                    </div>
                </form>
            </div>
            <div class="pb-4">
                <span class="text-secondary" style="font-size: .75rem;">Copyright &#169; 2025 Secondnyot</span>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    $(document).ready(function () {
        Cookies.set('sidebar-status', 'open')
        $('#login-form').submit(function (e) {
            e.preventDefault();
            $('input').prop('readonly')

            $.ajax({
                'url': '<?= base_url('login-process')?>',
                'type': 'POST',
                'data': $(this).serialize(),
                'success': function (res) {
                    console.log(res.success)
                    if (res.success == 1) {
                        window.location.replace("<?= base_url('/') ?>")
                    } else {
                        $('#submit').prepend(`
                        <div id="error-message" class="mb-3">
                            <div class="bg-danger text-center text-white py-2 rounded-3 position-relative">
                                <div style="transform: translateY(-2px); font-size: .8rem;"><span>Username or Password Wrong!</span></div>
                                <div class="position-absolute top-50 start-0 ms-2 fs-5" style="transform: translateY(-50%); cursor: pointer;" onclick="$('#error-message').remove()"><i class="bx bx-x text-white"></i></div>
                            </div>
                        </div>
                        `)
                    }
                }
            })
        })
    })
</script>