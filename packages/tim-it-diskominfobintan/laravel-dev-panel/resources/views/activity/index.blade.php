@extends('dev-panel::layouts.main')

@section('content')
    <div class="container my-5">
        <div class="row mb-3">
            <a href="{{ url('dev-panel') }}" class="text-decoration-none text-muted mb-2"><i class="bi bi-arrow-left me-1"></i>
                Kembali ke Developer Panel</a>
            <h1 class="fw-bold text-body-dark">Activity Log <\>
            </h1>
        </div>
        <div class="row gy-4 mt-2">
            <div class="col-md-6">
                <div class="card" style="min-height: 170px;">
                    <form id="form-filter">
                        <div class="card-header p-3 border-0">
                            <div class="d-flex justify-content-between">
                                <h5 class="fw-bold text-body-dark p-0 m-0">Filter</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="filter-start_datetime">Waktu awal</label>
                                        <input type="datetime-local" class="form-control" name="start_datetime"
                                            id="filter-start_datetime">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="filter-end_datetime">Waktu akhir</label>
                                        <input type="datetime-local" class="form-control" name="end_datetime"
                                            id="filter-end_datetime">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="filter-event">Event</label>
                                        <select name="event" id="filter-event" class="form-select">
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach ($log_events as $d)
                                                <option value="{{ $d }}">{{ $d }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="filter-log_name">Nama log</label>
                                        <input type="text" class="form-control" name="log_name" id="filter-log_name"
                                            placeholder="Cth. master_opd">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-body-dark p-0 m-0">Activities</h5>
                        </div>
                    </div>
                    <div class="card-body p-0 pb-4">
                        <div class="table-responsive">
                            <table class="table table-sm my-0" id="table-activity">
                                <thead>
                                    <tr>
                                        <th class="px-4 small">No</th>
                                        <th class="small text-center">Log <br> name</th>
                                        <th class="small">Description</th>
                                        <th class="small">Event</th>
                                        <th class="small">Subject - id</th>
                                        <th class="small">Causer</th>
                                        <th class="small text-center">Count <br>activity</th>
                                        <th class="small">Timelines</th>
                                        <th class="px-4 small">Created at</th>
                                        {{-- <th class="px-4 small">Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dev-panel::activity.modal')
    @include('dev-panel::components.datatables-spinner')
@endsection

@push('script')
    <script>
        var cronstrue = window.cronstrue;
        const token = localStorage.getItem('token')
        let isFirstLoad = true

        getSanctumCsrfCookie()

        // setInterval(() => {
        // }, 5000);

        function getSanctumCsrfCookie(callback) {
            $.get(`{{ url('/sanctum/csrf-cookie') }}`, function() {
                if (typeof callback === 'function') {
                    callback();
                }
            });
        }

        $('#table-activity tbody').on('click', '.btn-show-properties', function() {
            const batchDetails = $(this).data('batch_details')
            const detail = $(this).data('detail')
            const id = $(this).data('id')

            $('#modal-properties').modal('show')
            $('#section-properties').html('')
            $('#section-batch_uuid').text(detail.batch_uuid)
            $('#section-total_activity').text(batchDetails.length)

            // Mulai row dan kolom kiri
            let parentHtml = `<div class="row">`

            // === KIRI: Detail Log ===
            parentHtml += '<div class="col-md-8">'
            parentHtml += `<div class="accordion" id="accordionPanelsStayOpenExample">`

            batchDetails.forEach((detail, index) => {
                parentHtml += `<div class="accordion-item">`
                parentHtml += `<h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#panel-batch_activity-${index}" aria-expanded="false"
            aria-controls="panel-batch_activity-${index}">
            <div class="d-flex flex-column gap-2">
                <div>
            Activity ID: ${detail.id} - ${dateTimeShortMonthIndo(detail.created_at)}
            </div>
            <div class="small"><code class="small ${generateColorOfEvent(detail.event)}">[${detail.event}]</code><code>[${detail.log_name}]</code> ${detail.description}</div>
            </div>
        </button>
    </h2>`

                const properties = {
                    subject_type: detail.subject_type,
                    ...detail.properties
                }
                if (properties) {
                    let html = '<div class="row">';
                    const showPropertiesItemKeys = ['new', 'old', 'payload', 'changes', 'subject_type']
                    html += `<div class="col-md-12 mb-3">
                </div>`
                    for (const [key, value] of Object.entries(properties).reverse()) {
                        if (showPropertiesItemKeys.includes(key)) {
                            html += `<div class="col-md-6 mb-3">
                    <strong>${key}:</strong>
                    <pre class="bg-light p-2 rounded">${JSON.stringify(value, null, 2)}</pre>
                </div>`;
                        }
                    }
                    html += '</div>';
                    parentHtml += `
            <div id="panel-batch_activity-${index}" class="accordion-collapse collapse">
                <div class="accordion-body">
                    ${html}
                </div>
            </div>
        `
                } else {
                    $('#section-properties').html(
                        '<p class="text-muted">Tidak ada detail yang tersedia.</p>');
                }

                parentHtml += '</div>'; // close accordion-item
            });

            parentHtml += '</div>'; // close accordion
            parentHtml += '</div>'; // close col-md-6 (kiri)

            // === KANAN: User Info ===
            parentHtml += '<div class="col-md-4">'
            const showCauserItemKeys = ['ip', 'user_agent', 'url', 'method', 'referer', 'x_forwarded_for',
                'accept_language', 'user'
            ]
            let causerHtml = '<div class="">';
            causerHtml +=
                `<div class="card"><div class="card-header"><h5 class="m-0 p-0">User</h5></div><div class="card-body">`

            for (const [key, value] of Object.entries(detail.properties).reverse()) {
                if (showCauserItemKeys.includes(key)) {
                    causerHtml += `<div class="col-md-12 mb-3">
                        <strong>${key}:</strong>
                        <pre class="bg-light p-2 rounded" style="font-size:12px;">${JSON.stringify(value, null, 2)}</pre>
                    </div>`;
                }
            }

            causerHtml += `</div></div></div>`; // close card-body, card, wrapper
            parentHtml += causerHtml;
            parentHtml += '</div>'; // close col-md-6 (kanan)

            // Tutup row
            parentHtml += '</div>';

            // Render hasilnya
            $('#section-properties').html(parentHtml);
        });

        const table = $('#table-activity').DataTable({
            serverSide: true,
            columnDefs: [{
                targets: [0],
                className: "text-center",
                responsivePriority: 1,
                width: '10%'
            }],
            ajax: {
                url: "{{ url()->current() }}",
                method: 'GET',
                beforeSend: function() {
                    renderRowSpinner();
                },
                data: function(d) {
                    d.start_datetime = $('#filter-start_datetime').val();
                    d.end_datetime = $('#filter-end_datetime').val();
                    d.log_name = $('#filter-log_name').val();
                    d.event = $('#filter-event').val();
                },
                complete: function(response) {
                    if (!isFirstLoad && (response.status >= 200 && response.status <= 299)) {
                        Toast.fire({
                            icon: "success",
                            title: "Berhasil mendapatkan data activity"
                        });
                    }

                    isFirstLoad = false
                },
                error: function(response) {
                    handleAjaxJqueryError(response)
                },
            },
            lengthMenu: datatablesDefaultConfig().lengthMenu,
            pageLength: datatablesDefaultConfig().pageLength,
            language: datatablesDefaultConfig().language,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'log_name',
                    name: 'log_name',
                    className: "text-center",
                    render: function(data, type, row) {
                        return `<code class="small">${data}</code>`
                    }
                },
                {
                    data: 'description',
                    name: 'description',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<small>${data}</small>`
                    }
                },
                {
                    data: 'event',
                    name: 'event',
                    render: function(data, type, row) {
                        let className = generateColorOfEvent(data)

                        return `<code class="${className}">${data}</code>`
                    }
                },
                {
                    data: 'subject_type',
                    name: 'subject_type',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'causer_type',
                    name: 'causer_type',
                },
                {
                    data: 'log_count',
                    name: 'log_count',
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'properties',
                    name: 'properties',
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data) {
                        return dateTimeShortMonthIndo(data)
                    }
                },
                // {
                //     data: 'actions',
                //     name: 'actions',
                //     orderable: false,
                //     searchable: false
                // },
            ],
            rowCallback: function(row, data) {
                $(row).addClass('small')
            }
        });

        $('#form-filter').submit(function(e) {
            e.preventDefault();

            table.ajax.reload()
        });

        function generateColorOfEvent(data) {
            let className = ''
            if (data === 'created') {
                className = 'text-dark'
            } else if (data === 'updated') {
                className = 'text-warning'
            } else if (data === 'deleted') {
                className = 'text-danger'
            } else if (data === 'viewed') {
                className = 'text-dark'
            } else {
                className = 'text-dark'
            }

            return className
        }
    </script>
@endpush
