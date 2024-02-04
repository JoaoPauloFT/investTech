@section('plugins.Select2', true)

<div>
    <x-forms.modal
        title="{{ __('message.add_users_web') }}"
        description="{{ __('message.description_users_web') }}"
        route="{{ route('user.store') }}"
        textButtonConfirm="{{ __('message.register') }}"
    >
        <div>
            <label for="name">{{ __('message.name') }}</label>
            <input id="name" name="name" placeholder="{{ __('message.digit_name_person') }}" class="input-form {{ $errors->has('name') && old('form') == 'formSubmit' ? 'errorField' : '' }}" type="text" value="{{ old('form') == 'formSubmit' ? old('name') : '' }}">
            @if($errors->has('name') && old('form') == 'formSubmit')
                <p class="messageError">{{ $errors->first('name') }}</p>
            @endif
        </div>
        <div>
            <label for="email">{{ __('message.email') }}</label>
            <input id="email" name="email" placeholder="{{ __('message.digit_email') }}" class="input-form {{ $errors->has('email') && old('form') == 'formSubmit' ? 'errorField' : '' }}" type="email" value="{{ old('form') == 'formSubmit' ? old('email') : '' }}">
            @if($errors->has('email') && old('form') == 'formSubmit')
                <p class="messageError">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div>
            <label for="password">{{ __('message.password') }}</label>
            <div class="input-password">
                <input id="password" name="password" type="password" placeholder="{{ __('message.digit_password') }}" class="input-form {{ $errors->has('password') && old('form') == 'formSubmit' ? 'errorField' : '' }}" value="{{ old('form') == 'formSubmit' ? old('password') : '' }}">
                <div id="btnTogglePassword">
                    <i class="far fa-eye" id="togglePassword"></i>
                </div>
            </div>
            @if($errors->has('password') && old('form') == 'formSubmit')
                <p class="messageError">{{ $errors->first('password') }}</p>
            @endif
        </div>
        <div>
            <label>{{ __('message.function') }}</label>
            <div class="radios">
                @foreach($roles as $r)
                    <div>
                        <input id="function{{ $r->id }}" type="radio" name="role_id" value="{{ $r->id }}">
                        <div class="description-option">
                            <label for="function{{ $r->id }}">{{ $r->name }}</label>
                            <span>({{ $r->description }})</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-forms.modal>
</div>

<script>
    window.addEventListener('load', function () {
        $('.search-select').select2({
            dropdownParent: $('#modalForm')
        });

        const btnTogglePassword = document.querySelector('#btnTogglePassword');

        btnTogglePassword.addEventListener('click', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

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

        document.getElementById('function{{ $roles[0]->id }}').checked = true;

        @if($errors->any() && old('form') == 'formSubmit')
            document.getElementById('createButton').click();

            @if($errors->has('church_id'))
                $('#church_id').data('select2').$container.addClass('errorField');
            @endif

            $('.input-form').on('select2:select', function () {
                $(this).data('select2').$container.removeClass('errorField');
                $(this).parent().find('.messageError').remove();
            });

            $('.input-form').on('keypress', function () {
                $(this).removeClass('errorField');
                $(this).parent().find('.messageError').remove();
            });
        @endif
    });
</script>
