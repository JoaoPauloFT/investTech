<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<div class="modal fade" id="changePasswordModal{{ $idItem }}" tabindex="-1" role="dialog" aria-labelledby="changePasswordModal{{ $idItem }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="header">
                <div>
                    <h1>{{ __('message.edit_password') }}</h1>
                    <div>
                        <i class="fa-solid fa-x" data-dismiss="modal"></i>
                    </div>
                </div>
                <p>{{ __('message.edit_description_password') }}</p>
            </div>

            <div class="body-modal">
                <form id="formChangePassword{{ $idItem }}" action="{{ $route }}" method="POST">
                    @csrf()
                    <input type="text" name="form" value="formChangePassword{{ $idItem }}" hidden>
                    @method('PUT')
                    <div>
                        <label for="password">{{ __('message.password_new') }}</label>
                        <div class="input-password">
                            <input id="password{{ $idItem }}" name="password" type="password" placeholder="{{ __('message.digit_password') }}" class="input-form {{ $errors->has('password') && old('form') == 'formChangePassword'.$idItem ? 'errorField' : '' }}">
                            <div id="btnTogglePassword{{ $idItem }}">
                                <i class="far fa-eye" id="togglePassword{{ $idItem }}"></i>
                            </div>
                        </div>
                        @if($errors->has('password') && old('form') == 'formChangePassword'.$idItem)
                            <p class="messageError">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                </form>
            </div>
            <div class="footer">
                <button type="button" class="secondary-button" data-dismiss="modal"> {{ __('message.cancel') }} </button>
                <button type="button" class="primary-button submit" onclick="$('#formChangePassword{{ $idItem }}').submit()">{{ __('message.edit') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function () {

        const btnTogglePassword{{ $idItem }} = document.querySelector('#btnTogglePassword{{ $idItem }}');

        btnTogglePassword{{ $idItem }}.addEventListener('click', function () {
            const togglePassword = document.querySelector('#togglePassword{{ $idItem }}');
            const password = document.querySelector('#password{{ $idItem }}');

            if(password.getAttribute('type') === 'password') {
                password.setAttribute('type', 'text');
                togglePassword.classList.remove('fa-eye');
                togglePassword.classList.add('fa-eye-slash');
            } else {
                password.setAttribute('type', 'password');
                togglePassword.classList.remove('fa-eye-slash');
                togglePassword.classList.add('fa-eye');
            }
        });

        @if($errors->any() && old('form') == 'formChangePassword'.$idItem)
            document.getElementById('changePassword{{ $idItem }}').click();

            $('#password{{ $idItem }}').on('keypress', function () {
                $(this).removeClass('errorField');
                $(this).parent().parent().find('.messageError').remove();
            });
        @endif
    });
</script>
