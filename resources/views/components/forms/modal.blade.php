<link rel="stylesheet" href="{{ asset('css/modal.css') }}">
<div class="modal fade" id="{{ $idModal }}" tabindex="-1" role="dialog" aria-labelledby="{{ $idModal }}">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="header">
                    <div>
                        <h1>{{ $title }}</h1>
                        <div>
                            <i class="fa-solid fa-x" data-dismiss="modal"></i>
                        </div>
                    </div>
                    <p>{{ $description }}</p>
                </div>

                <div class="body-modal">
                    <form id="formSubmit{{ $idItem }}" action="{{ $route }}" method="POST">
                        @csrf()
                        <input type="text" name="form" value="formSubmit{{ $idItem }}" hidden>
                        {{ $slot }}
                    </form>
                    @if($titleImage != "")
                        <x-forms.upload-image
                            title="{{ $titleImage }}"
                            width="{{ $width }}"
                            height="{{ $height }}"
                            idItem="{{ $idItem }}"
                        />
                    @endif
                </div>
                <div class="footer">
                    <button type="button" class="secondary-button" data-dismiss="modal"> {{ __('message.cancel') }} </button>
                    <button type="button" class="primary-button submit" onclick="$('#formSubmit{{ $idItem }}').submit()"> {{ $textButtonConfirm }} </button>
                </div>
            </div>
    </div>
</div>
