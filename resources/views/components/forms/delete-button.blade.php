<form id="formDelete{{ $id }}" action="{{route($route, $id)}}" method="POST">
    @csrf()
    @method('DELETE')
    <button id="submitDelete{{ $id }}" type="button" class="btnAction">
        <i class="fa-solid fa-x"></i>
    </button>
</form>

<script>
    document.getElementById('submitDelete{{ $id }}').addEventListener('click', function () {
        Swal.fire({
            title: '{{ $title }}',
            text: '{{ __('message.dont_revert_operation') }}',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#042A56",
            cancelButtonColor: "#d33",
            confirmButtonText: '{{ __('message.yes') }}',
            cancelButtonText: '{{ __('message.cancel') }}',
            customClass: {
                confirmButton: 'primary-button',
                cancelButton: 'cancel-button'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formDelete{{ $id }}').submit();
            }
        });
    })
</script>
