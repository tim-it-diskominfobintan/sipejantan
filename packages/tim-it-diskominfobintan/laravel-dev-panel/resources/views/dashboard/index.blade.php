<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Dev Panel</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
        rel="stylesheet"crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ route('dev-panel.assets', ['file' => 'global.js']) }}"></script>

    <style>
        * {
            font-family: system-ui, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        .card {
            border-radius: 12px;
        }

        .card .card-header,
        .card .card-footer {
            background: none !important;
        }

        input:read-only {
            filter: brightness(95%) !important;
        }
    </style>
</head>

<body class="">
    <div class="container my-5">
        <div class="row mb-3">
            <a href="{{ url('admin/dashboard') }}" class="text-decoration-none text-muted mb-2"><i
                    class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
            <h1 class="fw-bold text-body-dark">Developer Panel <\>
            </h1>
            @env('local')
                <small class="text-warning">(Production masih tetap bisa diakses)</small>
            @endenv
        </div>
        <div class="row gy-4 mt-2">
            <div class="col-md-3">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-body-dark p-0 m-0">Cron status</h5>
                            <div id="indicator-cron-status_service" class="align-self-center rounded-pill bg-secondary"
                                style="width: 10px; height: 10px;"></div>
                        </div>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div id="label-cron-status_service" class="text-capitalize"></div>
                    </div>
                    <div class="card-footer border-0 py-2">
                        <small id="label-cron-last_heartbeat" class="text-muted"></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <div>
                            <h5 class="fw-bold text-body-dark p-0 m-0">File Logging</h5>
                            <small class="text-muted">by Opcodes (log-viewer)</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="text-capitalize">{{ $count_log_files }} Log ditemukan</div>
                    </div>
                    <div class="card-footer border-0 py-2">
                        <a href="{{ url('dev-panel/log-viewer') }}" class="text-primary">Lihat semua</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-body-dark p-0 m-0">Jobs Queue</h5>
                        </div>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="text-capitalize"><span id="label-queue-count"></span></div>
                    </div>
                    <div class="card-footer border-0 py-2">
                        <small class="text-muted">Driver: <span id="label-job_connection_driver"></span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-body-dark p-0 m-0">Maintenance</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="switch-maintenance-mode">
                                <label class="form-check-label" for="switch-maintenance-mode"></label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class=""><span id="label-down_at"></span></div>
                    </div>
                    <div class="card-footer border-0 py-2">
                        <small class="text-muted">Status: <span id="label-maintenance-mode-status"></span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold text-body-dark p-0 m-0">PHP Info</h5>
                                <small class="text-muted">Laravel
                                    v{{ Illuminate\Foundation\Application::VERSION }}</small>
                            </div>
                            <small class="text-muted">PHP v{{ PHP_VERSION }}</small>
                        </div>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="text-capitalize"><span id=""></span></div>
                    </div>
                    <div class="card-footer border-0 py-2">
                        <a href="{{ url('dev-panel/phpinfo') }}" class="text-primary">Lihat semua</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold text-body-dark p-0 m-0">Activity Log</h5>
                                <small class="text-muted">by Spatie Activity (laravel-activitylog)</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="text-capitalize"></div>
                    </div>
                    <div class="card-footer border-0 py-2">
                        <a href="{{ url('dev-panel/activity-viewer') }}" class="text-primary">Lihat semua</a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-body-dark p-0 m-0">System Cache</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm my-0">
                                <tbody>
                                    @foreach ($artisan_commands as $d)
                                        <tr class="align-middle">
                                            <td class="px-3"><span class="badge text-bg-light">{{ $d->command }}</span></td>
                                            <td class="small" style="font-size: 12px;">{{ $d->description }}</td>
                                            <td class="px-3"><button
                                                    class="btn btn-sm btn-danger btn-run-single-command"
                                                    data-command="{{ $d->command }}"><small>RUN</small></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <div class="d-flex mt-0 py-0 justify-content-between">
                            <div>
                                <small class="text-muted mt-5" id="label-system-cache"></small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-danger btn-run-all-commands"><small>RUN
                                        ALL</small></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card" style="min-height: 170px;">
                    <div class="card-header p-3 border-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold text-body-dark p-0 m-0">Jobs Schedule</h5>
                        </div>
                    </div>
                    <div class="card-body p-0 pb-4">
                        <div class="table-responsive">
                            <table class="table table-sm my-0" id="table-scheduler">
                                <thead>
                                    <tr>
                                        <th class="px-4 small">No</th>
                                        <th class="small">Command</th>
                                        <th class="small">Interval</th>
                                        <th class="small">Description</th>
                                        <th class="small">Next Due</th>
                                        <th class="px-4 small">Aktif?</th>
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

    @include('dev-panel::dashboard.modal')

    <script src="{{ asset('vendor/laravel-dev-panel/global.js') }}"></script>
    <script src="https://unpkg.com/cronstrue@latest/dist/cronstrue.min.js" async></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/cronstrue@latest/dist/cronstrue-i18n.min.js" async></script>

    <script>
        var cronstrue = window.cronstrue;
        const token = localStorage.getItem('token')

        getSanctumCsrfCookie()
        fetchCronHealth()
        fetchJobStatus()
        fetchMaintenanceStatus()
        fetchSchedules()

        setInterval(() => {
            fetchCronHealth()
            fetchJobStatus()
            fetchMaintenanceStatus()
            fetchSchedules()
        }, 5000);

        function getSanctumCsrfCookie(callback) {
            $.get(`{{ url('/sanctum/csrf-cookie') }}`, function() {
                if (typeof callback === 'function') {
                    callback();
                }
            });
        }

        $('#switch-maintenance-mode').click(function() {
            const status = $(this).is(':checked')
            if (confirm(`Ingin ${status ? 'mengaktifkan' : 'menonaktifkan'} maintenance mode?`)) {
                $.ajax({
                    type: "PATCH",
                    url: "{{ url('api/v1/dev/maintenance') }}" + (status ? '/down' : '/up'),
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: $(this).is(':checked')
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#label-maintenance-mode-status').text('Memproses...')
                    },
                    success: function(response) {
                        const data = response.data
                    }
                });

                return;
            }

            return $('#switch-maintenance-mode').prop('checked', !status)
        })

        $('.btn-run-all-commands').click(function() {
            if (confirm('Ingin menjalankan semua command?')) {
                const allCommands = $('.btn-run-single-command').map(function() {
                    return $(this).data('command')
                }).get()

                if (allCommands.length === 0) {
                    alert('Tidak ada command yang dapat dijalankan!')
                    return
                }

                $.ajax({
                    type: "POST",
                    url: "{{ url('api/v1/dev/command') }}",
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        commands: allCommands
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#label-system-cache').text('Memproses...')
                    },
                    complete: function() {
                        // setTimeout(() => {
                        //     $('#label-system-cache').text('')
                        // }, 1000)
                    },
                    success: function(response) {
                        const data = response.data

                        setTimeout(() => {
                            $('#label-system-cache').html(
                                `<span class="text-success">${response.message}</span>`)
                        }, 1000)

                        setTimeout(() => {
                            $('#label-system-cache').html('')
                        }, 3000)
                    },
                    error: function(response) {
                        setTimeout(() => {
                            $('#label-system-cache').text('')
                        }, 1000)

                        alert('terjadi kesalahan!')
                    }
                });

                return
            }

            return
        })

        $('.btn-run-single-command').click(function() {
            const command = $(this).data('command')

            if (confirm(`Ingin menjalankan command "php artisan ${command}" ?`)) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('api/v1/dev/command') }}",
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        commands: [command]
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#label-system-cache').text('Memproses...')
                    },
                    complete: function() {
                        // setTimeout(() => {
                        //     $('#label-system-cache').text('')
                        // }, 1000)
                    },
                    success: function(response) {
                        const data = response.data

                        setTimeout(() => {
                            $('#label-system-cache').html(
                                `<span class="text-success">${response.message}</span>`)
                        }, 1000)

                        setTimeout(() => {
                            $('#label-system-cache').html('')
                        }, 3000)
                    },
                    error: function(response) {
                        setTimeout(() => {
                            $('#label-system-cache').text('')
                        }, 1000)

                        alert('terjadi kesalahan!')
                    }
                });

                return
            }

            return
        })

        $('#update_cron_expression-template_expression_laravel').change(function(e) {
            const value = $(this).val()

            if (value != '') {
                $('#update_cron_expression-cron').attr('readonly', true)
                $('#update_cron_expression-cron').val(value).trigger('change')
            } else {
                $('#update_cron_expression-cron').attr('readonly', false)
                $('#update_cron_expression-cron').val('').trigger('change')
            }
        });

        $('#form-update_cron_expression').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "PUT",
                url: "{{ url('api/v1/dev/schedule') }}" + '/' + $('#update_cron_expression-key').val(),
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cron: $('#update_cron_expression-cron').val()
                },
                dataType: "json",
                success: function (response) { 
                    $('#modal-update_cron_expression').modal('hide')
                 },
                error: function(response) {
                    if (response.status === 422) {
                        alert('isi semua form')
                    }
                }
            });
        });

        function fetchSchedules() {
            $.ajax({
                type: "GET",
                url: "{{ url('api/v1/dev/schedule') }}",
                xhrFields: {
                    withCredentials: true
                },
                dataType: "json",
                success: function(response) {
                    const data = response.data

                    $('#table-scheduler tbody').empty()

                    let number = 1
                    data.forEach(item => {
                        $('#table-scheduler tbody').append(`
                            <tr>
                                <td class="px-4 small"  style="max-width: 10px;">${number}</td>
                                <td class="" style="max-width: 150px; font-size: 12px;">${item.command}</td>
                                <td class="small">
                                    ${item.cron} 
                                    <br/>
                                    <small class="text-muted">(${cronstrue.toString(item.cron, { locale: "id" })})</small>
                                    <br/>
                                    <button class="btn btn-sm btn-outline-danger btn-update-cron_expression my-2 d-none" data-detail="${btoa(JSON.stringify(item))}">Ubah</button>
                                </td>
                                <td class="small" style="max-width: 80px;">${item.description}</td>
                                <td class="small">
                                    ${item.next_due}
                                    <br/>
                                    <small class="text-muted">${item.next_due != '-' ? '(akan jalan ' + formatTimeHumanRead(item.next_due) + ')' : ''}</small>
                                </td>
                                <td class="px-4 small">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input switch-schedule-toggle" type="checkbox" role="switch" id="switch-schedule-enabled_${item.number}" data-command="${item.command}" data-artisan_command="${item.artisan_command}" data-key="${item.key}" ${item.enabled ? 'checked' : ''}>
                                        <label class="form-check-label" for="switch-schedule-enabled_${item.number}" data-command="${item.command}"></label>
                                    </div>
                                </td>
                            </tr>
                        `);

                        number++
                    });

                    $('#table-scheduler').on('change', '.switch-schedule-toggle', function() {
                        const key = $(this).data('key')
                        if ($(this).is(':checked')) {
                            toggleSchedule(true, key);
                        } else {
                            toggleSchedule(false, key);
                        }
                    });

                    $('#table-scheduler').on('click', '.btn-update-cron_expression', function() {
                        const detail = JSON.parse(atob($(this).data('detail')))

                        $('#modal-update_cron_expression').modal('show')

                        let found = false;
                        $("#update_cron_expression-template_expression_laravel option").each(function(
                            index, option) {
                            if (option.value == detail.cron) {
                                $("#update_cron_expression-template_expression_laravel").val(
                                    detail.cron).trigger('change');
                                found = true;
                                return false;
                            }
                        });

                        if (!found) {
                            $('#update_cron_expression-template_expression_laravel').val('').trigger(
                                'change');
                        }

                        $('#update_cron_expression-key').val(detail.key)
                        $('#update_cron_expression-cron').val(detail.cron)
                        $('#update_cron_expression-command').val(detail.command)
                        $('#update_cron_expression-artisan_command').val(detail.artisan_command)
                    })
                },
                error: function(response) {
                    $('#table-scheduler tbody').empty()

                    $('#table-scheduler tbody').html(`<tr>
                        <td colspan="6" class="text-center py-3"><small class="text-muted">${response.responseJSON.message}</small></td>
                    </tr>`)
                }
            });
        }

        function toggleSchedule(isEnabled, key) {
            $.ajax({
                type: "PATCH",
                url: "{{ url('api/v1/dev/schedule') }}" + '/' + key + '/enabled',
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    key: key,
                    enabled: isEnabled
                },
                dataType: "json",
                success: function(response) {
                    fetchSchedules();
                },
                error: function(response) {
                    alert(response.responseJSON.message)
                }
            });
        }

        function fetchCronHealth() {
            $.ajax({
                type: "GET",
                url: "{{ url('api/v1/dev/cron/health') }}",
                xhrFields: {
                    withCredentials: true
                },
                dataType: "json",
                success: function(response) {
                    const data = response.data
                    $('#label-cron-status_service').text(data.status_service)
                    if (data.last_heartbeat !== null) {
                        $('#label-cron-last_heartbeat').html(
                            `Last heartbeat: <br> ${data.last_heartbeat} (${formatTimeHumanRead(data.last_heartbeat)})`
                        )
                    } else {
                        $('#label-cron-last_heartbeat').html(
                            `Last heartbeat: Belum pernah dijalankan`
                        )
                    }

                    if (data.status_service === 'active') {
                        $('#indicator-cron-status_service').removeClass('bg-secondary')
                        $('#indicator-cron-status_service').removeClass('bg-danger')
                        $('#indicator-cron-status_service').addClass('bg-success')
                    } else {
                        $('#indicator-cron-status_service').removeClass('bg-secondary')
                        $('#indicator-cron-status_service').removeClass('bg-success')
                        $('#indicator-cron-status_service').addClass('bg-danger')
                    }

                }
            });
        }

        function fetchJobStatus() {
            $.ajax({
                type: "GET",
                url: "{{ url('api/v1/dev/job') }}",
                xhrFields: {
                    withCredentials: true
                },
                dataType: "json",
                success: function(response) {
                    const data = response.data
                    $('#label-job_connection_driver').text(data.job_connection.default)
                    $('#label-queue-count').text(
                        `${data.queue.jobs.length} antrian job, ${data.queue.failed_jobs.length} gagal.`)
                }
            });
        }

        function fetchMaintenanceStatus() {
            $.ajax({
                type: "GET",
                url: "{{ url('api/v1/dev/maintenance') }}",
                xhrFields: {
                    withCredentials: true
                },
                dataType: "json",
                success: function(response) {
                    const data = response.data
                    $('#switch-maintenance-mode').prop('checked', data.status_maintenance)
                    $('#label-maintenance-mode-status').text(data.status_maintenance ? 'On' : 'Off')

                    $('#label-down_at').text(data.down_at ? 'Maintenance dari: ' + data.down_at :
                        'Aplikasi aktif')

                    $('#label-down_at').html(
                        `${data.down_at ? `<span>
                                                                                                        Maintenance dari: ${data.down_at} 
                                                                                                        <br/>
                                                                                                        <small class="text-muted">(${formatTimeHumanRead(data.down_at)})    </small>
                                                                                                    </span>` : 'Aplikasi aktif'}`
                        )
                }
            });
        }
    </script>
</body>

</html>
