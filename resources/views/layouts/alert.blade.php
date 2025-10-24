@if (Session::get('success'))
<script type="text/javascript">
    $(document).ready(function () {
        Swal.fire({
            text: "{{Session::get('success')}}",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    });
</script>
@endif

@if (Session::get('success2'))
<script type="text/javascript">
    $(document).ready(function () {
        Swal.fire({
            html: `<?= Session::get('success2') ?>`,
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    });
</script>
@endif

@if (Session::get('error'))
<script type="text/javascript">
    $(document).ready(function () {
        Swal.fire({
            text: "{{Session::get('error')}}",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    });
</script>
@endif

@if (Session::get('warning'))
<script type="text/javascript">
    $(document).ready(function () {
        Swal.fire({
            text: "{{Session::get('warning')}}",
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    });
</script>
@endif

@if (Session::get('info'))
<script type="text/javascript">
    $(document).ready(function () {
        Swal.fire({
            text: "{{Session::get('info')}}",
            icon: "info",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    });
</script>
@endif