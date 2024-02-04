@section('plugins.Select2', true)

<div>
    <x-forms.modal
        title="{{ __('message.edit_users_web') }}"
        description="{{ __('message.description_edit_users_web') }}"
        route="{{ route('user.update', $user->id) }}"
        textButtonConfirm="{{ __('message.edit') }}"
        idModal="editForm{{ $user->id }}"
        idItem="{{ $user->id }}"
    >
        @method('PUT')
        <div>
            <label for="name">{{ __('message.name') }}</label>
            <input id="name{{ $user->id }}" name="name" placeholder="{{ __('message.digit_name_person') }}" class="input-form {{ $errors->has('name') && old('form') == 'formSubmit'.$user->id ? 'errorField' : '' }}" type="text" value="{{ old('form') == 'formSubmit'.$user->id ? old('name') : $user->name }}">
            @if($errors->has('name') && old('form') == 'formSubmit'.$user->id)
                <p class="messageError">{{ $errors->first('name') }}</p>
            @endif
            <input type="hidden" name="created_by" value="{{ $user->created_by }}">
        </div>
        <div>
            <label for="email">{{ __('message.email') }}</label>
            <input id="email{{ $user->id }}" name="email" placeholder="{{ __('message.digit_email') }}" class="input-form {{ $errors->has('email') && old('form') == 'formSubmit'.$user->id ? 'errorField' : '' }}" type="email" value="{{ old('form') == 'formSubmit'.$user->id ? old('email') : $user->email }}">
            @if($errors->has('email') && old('form') == 'formSubmit'.$user->id)
                <p class="messageError">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div>
            <label>{{ __('message.function') }}</label>
            <div class="radios">
                @foreach($roles as $r)
                    <div>
                        <input id="function{{ $r->id.$user->id }}" type="radio" name="role_id" value="{{ $r->id }}">
                        <div class="description-option">
                            <label for="function{{ $r->id.$user->id }}">{{ $r->name }}</label>
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
        $('.search-select{{ $user->id }}').select2({
            dropdownParent: $('#editForm{{ $user->id }}')
        });

        $('#church_id{{ $user->id }}').val('{{ $user->church_id }}').trigger('change');

        document.getElementById('function{{ $user->role_id.$user->id }}').checked = true;

        @if($errors->any() && old('form') == 'formSubmit'.$user->id)
            document.getElementById('editButton{{ $user->id }}').click();

            document.getElementById('function{{ old('role_id').$user->id }}').checked = true;

            $('#church_id{{ $user->id }}').val('{{ old('church_id') }}').trigger('change');

            @if($errors->has('church_id'))
                $('#church_id{{ $user->id }}').data('select2').$container.addClass('errorField');
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
