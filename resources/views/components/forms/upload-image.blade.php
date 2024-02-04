<script>
    window.addEventListener('load', function () {
        const dropArea{{ $idItem }} = document.querySelector(".drag-area{{ $idItem }}"),
            input{{ $idItem }} = document.querySelector(".input-drag{{ $idItem }}");

        dropArea{{ $idItem }}.addEventListener("click", () => {
            input{{ $idItem }}.click();
        });

        input{{ $idItem }}.addEventListener("change", function () {
            thumbmailView(input{{ $idItem }}.files[0]);
        });

        dropArea{{ $idItem }}.addEventListener("dragover", (event) => {
            event.preventDefault();
            dropArea{{ $idItem }}.classList.add("active");
        });

        dropArea{{ $idItem }}.addEventListener("dragleave", (event) => {
            event.preventDefault();
            dropArea{{ $idItem }}.classList.remove("active");
        });

        dropArea{{ $idItem }}.addEventListener("drop", (event) => {
            event.preventDefault();
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(event.dataTransfer.files[0]);
            if (dataTransfer.files[0].type === 'image/png' || dataTransfer.files[0].type === 'image/jpeg') {
                input{{ $idItem }}.files = dataTransfer.files;
                thumbmailView(event.dataTransfer.files[0]);
            } else {
                setError('{{ __('message.format_incorrect_response', ['format' => 'png ou jpg/jpeg']) }}')
            }

            dropArea{{ $idItem }}.classList.remove("active");
        });

        function uploadImage() {
            var file = input{{ $idItem }}.files[0];
            var formData = new FormData();
            formData.append('file', file);
            formData.append('width', {{ $width }});
            formData.append('height', {{ $height }});

            $.ajax('{{ route('FileUpload') }}', {
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    document.querySelectorAll('.submit').forEach(function (e) {
                        e.disabled = true;
                    });
                },
                success: function (response) {
                    document.querySelector('#image{{ $idItem }}').value = response.data['nameImage'];
                    document.querySelectorAll('.submit').forEach(function (e) {
                        e.disabled = false;
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    document.querySelectorAll('.submit').forEach(function (e) {
                        e.disabled = false;
                    });
                }
            });
        }

        function thumbmailView(file) {
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (readEvent) {
                const img = new Image();
                img.src = readEvent.target.result;
                img.onload = function () {
                    var result = validSize(img, {{ $width }}, {{ $height }});
                    if (result) {
                        dropArea{{ $idItem }}.innerHTML = "<img class='preview-img' src='" + readEvent.target.result + "' />";
                        document.querySelector('.drag-area{{ $idItem }}').style.cssText = 'border: 1px dashed #E7E7E7 !important';
                        document.querySelector('.subdescription{{ $idItem }}').style.cssText = 'color: #CECBD0 !important';
                        document.querySelector('.subdescription{{ $idItem }}').innerText = '{{ __('message.image_size', ['size' => $width.'x'.$height]) }}';

                        uploadImage();
                    } else {
                        setError('{{ __('message.size_incorrect_response', ['size' => $width.'x'.$height]) }}');
                    }
                }
            }
        }

        function validSize(file, width, height) {
            return file.width === width && file.height === height;
        }

        function setError(messageError) {
            input{{ $idItem }}.value = '';
            dropArea{{ $idItem }}.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                    <path d="M22.5 40H37.5V25H47.5L30 7.5L12.5 25H22.5V40ZM12.5 45H47.5V50H12.5V45Z" fill="#CECBD0"/>
                </svg>
                <span>{{ __('message.upload_image_description') }}</span>
            `;

            document.querySelector('.drag-area{{ $idItem }}').style.cssText = 'border: 1px dashed #E04B59 !important';
            document.querySelector('.subdescription{{ $idItem }}').style.cssText = 'color: #E04B59 !important';
            document.querySelector('.subdescription{{ $idItem }}').innerText = messageError;
        }
    });
</script>

<div class="mt-3">
    <label>{{ $title }}</label>
    <div>
        <div class="drag-area{{ $idItem }} divImage">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                <path d="M22.5 40H37.5V25H47.5L30 7.5L12.5 25H22.5V40ZM12.5 45H47.5V50H12.5V45Z" fill="#CECBD0"/>
            </svg>
            <span>{{ __('message.upload_image_description') }}</span>
        </div>
        <input class="input-drag{{ $idItem }}" type="file" accept="image/png, image/jpeg" hidden>
    </div>
    <p class="subdesc subdescription{{ $idItem }}">{{ __('message.image_size', ['size' => $width.'x'.$height]) }}</p>
</div>
