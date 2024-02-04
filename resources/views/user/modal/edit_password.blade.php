<div>
    <x-forms.change-password
        title="{{ __('message.edit_users_totem') }}"
        description="{{ __('message.description_edit_users_totem') }}"
        route="{{ route('user.update_password', $user->id) }}"
        idItem="{{ $user->id }}"
    />
</div>
