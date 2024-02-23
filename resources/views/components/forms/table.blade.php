@section('plugins.Datatables', true)
@section('plugins.Select2', true)

<link rel="stylesheet" href="{{ asset('css/datatables.css') }}">

<div>
    <table id="myTable" class="display">
        {{ $slot }}
    </table>
</div>

@section('js')
    <script>
        $(document).ready(function () {
            let columns = [];
            for (let i = 0; i < $('thead tr th').length; i++) {
                columns.push(i);
            }
            
            $.fn.dataTable.moment( 'DD/MM/YYYY - HH:mm' );

            let table = new DataTable('#myTable', {
                responsive: true,
                lengthMenu: [ [10, 25, 50, -1], ["{{ __('message.show') }} 10", "{{ __('message.show') }} 25", "{{ __('message.show') }} 50", "Todos"] ],
                language: {
                    search: "",
                    searchPlaceholder: "{{ __('message.search_list') }}",
                    info: "{{ __('message.info_table') }}",
                    paginate: {
                        next: "{{ __('message.next') }}",
                        previous: "{{ __('message.previous') }}"
                    },
                    emptyTable: "{{ __('message.emptyTable') }}",
                    infoEmpty: "{{ __('message.infoEmpty') }}",
                    infoFiltered: "{{ __('message.infoFiltered') }}",
                    zeroRecords: "{{ __('message.zeroRecords') }}",
                    lengthMenu: "_MENU_",
                    decimal: ",",
                    thousands: "."
                },
                dom: '<"header"fBr><"selects">"t<"bottom"<"lbottom"li>p>',
                buttons: [
                    {
                        extend: 'collection',
                        text: '<i class="fa-regular fa-file-lines"></i><span> {{ __('message.export') }} </span><i class="fa-solid fa-angle-down ml-2"></i>',
                        buttons: [
                            {
                                extend: 'excel',
                                text: '{{ __('message.download') }} Excel',
                                exportOptions: {
                                    columns: columns
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '{{ __('message.download') }} PDF',
                                exportOptions: {
                                    columns: columns
                                }
                            }
                        ]
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fa-regular fa-eye"></i><span> {{ __('message.config_columns') }} </span>',
                        columnText: function ( dt, idx, title ) {
                            return '<span class="checkbox"><i class="fa-solid fa-check"></i></span>' + title;
                        }
                    }
                ],
                initComplete: function () {
                    this.api()
                        .columns({{ Illuminate\Support\Js::from($dropdownFilter) }})
                        .every(function () {
                            let column = this;

                            // Create select element
                            let select = document.createElement('select');
                            select.add(new Option(''));
                            document.getElementsByClassName("selects")[0].appendChild(select);

                            $(select).addClass('selectFilter');
                            $(select).select2({
                                placeholder: $(this.header()).text(),
                            });

                            // Apply listener for user change in value
                            $(select).on('change', function () {
                                var val = DataTable.util.escapeRegex(select.value);

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();

                                if(val && $('.btnLimpar').length == 0) {
                                    var btnLimpar = "<button type='button' class='btnLimpar'>{{ __('message.clear') }}</button>";
                                    $(".selects").append(btnLimpar);

                                    $('.btnLimpar').on('click', function () {
                                        $('.selectFilter').val('').trigger('change');
                                        $(this).remove();
                                    });
                                }
                            });


                            // Add list of options
                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function (d, j) {
                                    select.add(new Option(d));
                                });
                        });
                }
            });

            const elements = document.querySelectorAll('.dt-button-down-arrow');

            elements.forEach(elements => {
                elements.remove();
            });
        });
    </script>
@stop
