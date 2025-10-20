@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-warning-light" data-bs-toggle="modal" data-bs-target="#modal-create">
                <i class="bi bi-pen me-2"></i> Edit {{ $title }}
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="row gy-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ auth()->user()->photo_profile_url }}" alt="profile-pict"
                                                class="rounded-pill" style="width: 50px;" />
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3 class="mb-0 mt-3">{{ auth()->user()->name }}</h3>
                                            <p class="text-muted">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                    <ul>
                                        @foreach (auth()->user()->assignedOpd as $opd)
                                            <li>{{ $opd->opd->nama }} - {{ $opd->status }}</li>
                                        @endforeach
                                    </ul>

                                </div>
                                <div class="card-footer">
                                    <p class="text-muted m-0">Bergabung pada
                                        {{ dateTimeIndo(date('Y-m-d', strtotime(auth()->user()->joined_at))) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <span class="card-title">
                                        <h3 class="fw-bold m-0">Browser session</h3>
                                        <small class="text-muted m-0 fs-5">Atur dan log out sesi aktif anda pada browser
                                            lain.</small>
                                    </span>
                                </div>
                                <ul class="list-group list-group-flush" id="list-session">
                                </ul>
                                <div class="card-footer text-end">
                                    <button class="btn btn-outline-danger" id="btn-clear-all-session">
                                        Logout semua sesi lain
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row gy-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="fw-bold m-0">Aktifitas saya</h3>
                                </div>
                                <div class="card-body py-0 px-0 mx-0">
                                    <div class="table-responsive table-full-to-card-body py-3">
                                        <table class="table" id="table">
                                            <thead>
                                                <tr class="bg-body-tertiary">
                                                    <th width="20px">No</th>
                                                    <th>Aktifitas</th>
                                                    <th>Dilakukan pada</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let formMode = 'create'

        getBrowserSessions()

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                targets: [0, -1],
                className: "text-center",
                responsivePriority: 1,
            }],
            ajax: {
                url: "{{ url()->current() }}" + '/activity',
                method: 'GET',
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        formPrefix: formMode,
                        msgSuffix: 'msg'
                    })
                }
            },
            lengthMenu: datatablesDefaultConfig().lengthMenu,
            pageLength: datatablesDefaultConfig().pageLength,
            language: datatablesDefaultConfig().language,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'description',
                    name: 'description',
                    orderable: false,
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
            ]
        });

        $('#btn-clear-all-session').click(function() {
            logoutOtherSessions()
        })

        function getBrowserSessions() {
            $.ajax({
                url: "{{ url('/api/v1/sessions') }}",
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {
                    const sessions = response.data
                    const $list = $('#list-session');
                    $list.empty();

                    function getDeviceType(userAgent) {
                        if (userAgent.is_desktop) {
                            return `<i class="bi bi-pc-display" style="font-size: 24px;"></i>`
                        } else if (userAgent.is_mobile) {
                            return `<i class="bi bi-pc-display" style="font-size: 24px;"></i>`
                        } else if (userAgent.is_tablet) {
                            return `<i class="bi bi-tablet" style="font-size: 24px;"></i>`
                        }
                    }

                    sessions.forEach(session => {
                        const isCurrent = session.is_current_device ? `
                            <span class="ms-2 badge badge-light-success">Device ini</span>
                        ` : `
                            <button class="btn btn-outline-danger float-end" onclick="logoutSession('${session.id}', '${session.user_agent.platform} ${session.user_agent.platform_version} - ${session.user_agent.browser}')">
                                Logout
                            </button>
                        `;

                        const html = `
                    <li class="list-group-item">
                        <div class="d-flex gap-3">
                            <div>
                                ${getDeviceType(session.user_agent)}
                            </div>
                            <div class="w-100">
                                <strong>
                                    ${session.user_agent.platform} ${session.user_agent.platform_version} - ${session.user_agent.browser}
                                </strong>
                                <br>
                                IP: ${session.ip_address}
                                <br>
                                ${session.is_current_device ? `Terakhir login: ${dateTimeShortMonthIndo(session.logged_at)}` : `Aktif: ${session.last_active}`}
                            </div>
                            <div>
                                ${isCurrent}
                            </div>
                        </div>
                    </li>
                `;

                        $list.append(html);
                    });
                },
                error: function(err) {
                    console.error(err);
                }
            });

        }

        function logoutSession(sessionId, sessionLabel) {
            if (!confirm(`Yakin ingin mengeluarkan sesi pada device '${sessionLabel}'?`)) return;

            $.ajax({
                url: "{{ url('/api/v1/sessions') }}/" + sessionId,
                method: 'DELETE',
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    getBrowserSessions(); // Refresh list
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        function logoutOtherSessions() {
            if (!confirm(`Yakin ingin logout semua sesi selain sesi pada 'device ini'?`)) return;

            $.ajax({
                url: "{{ url('/api/v1/sessions') }}",
                method: 'DELETE',
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    getBrowserSessions(); // refresh
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        function callApi(formData, url = null) {
            const submitLabel = $(`#form-${formMode} button[type="submit"]`).text()

            startSpinSubmitBtn(`#form-${formMode}`)

            $.ajax({
                url: url || "{{ url()->current() }}",
                method: "POST",
                data: formData,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    clearValidationMessage(formMode, 'msg')
                },
                success: function(response) {
                    $(`#form-${formMode}`).trigger('reset')
                    $(`#modal-${formMode}`).modal('hide')
                    showSwal(response.message, response.status)

                    table.ajax.reload()
                },
                error: function(response) {
                    handleAjaxJqueryError(response, {
                        formPrefix: formMode,
                        msgSuffix: 'msg'
                    })
                },
                complete: function() {
                    stopSpinSubmitBtn(`#form-${formMode}`, submitLabel)
                }
            });
        }
    </script>
@endpush
