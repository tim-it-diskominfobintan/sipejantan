@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#modal-update_photo">
                <i class="bi bi-pen me-2"></i> Ganti Foto {{ $title }}
            </button>
        </div>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#modal-update_profil">
                <i class="bi bi-pen me-2"></i> Edit {{ $title }}
            </button>
        </div>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-1" data-bs-toggle="modal" data-bs-target="#modal-update_password">
                <i class="bi bi-lock force-bi-bold-05 me-2"></i> Ganti password
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
    @include('admin.profile.modal')
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Tambahkan progress bar di HTML atau create dynamically
            function createPasswordStrengthMeter() {
                $('#update_password-new_password').after(`
                    <div class="password-strength-meter mt-2">
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="password-requirements"></div>
                    </div>
                `);
            }

            createPasswordStrengthMeter();

            function checkPasswordStrength(password) {
                let score = 0;
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    symbol: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                };

                // Hitung score
                Object.values(requirements).forEach(met => {
                    if (met) score += 20;
                });

                return {
                    score,
                    requirements
                };
            }

            function displayPasswordStrength(password) {
                const {
                    score,
                    requirements
                } = checkPasswordStrength(password);
                const progressBar = $('.password-strength-meter .progress-bar');
                const requirementsDiv = $('.password-strength-meter .password-requirements');

                if (password === '') {
                    progressBar.css('width', '0%').removeClass('bg-danger bg-warning bg-success');
                    requirementsDiv.html('');
                    return;
                }

                // Update progress bar
                progressBar.css('width', score + '%');

                if (score < 40) {
                    progressBar.removeClass('bg-warning bg-success').addClass('bg-danger');
                } else if (score < 80) {
                    progressBar.removeClass('bg-danger bg-success').addClass('bg-warning');
                } else {
                    progressBar.removeClass('bg-danger bg-warning').addClass('bg-success');
                }

                // Update requirements list
                const requirementsHtml = `
                    <div class="small">
                        <div class="${requirements.length ? 'text-success' : 'text-danger'}"><i class="bi bi-${requirements.length ? 'check' : 'x'}-circle me-1"></i>Minimal 8 karakter</div>
                        <div class="${requirements.uppercase ? 'text-success' : 'text-danger'}"><i class="bi bi-${requirements.uppercase ? 'check' : 'x'}-circle me-1"></i>Minimal 1 huruf kapital</div>
                        <div class="${requirements.lowercase ? 'text-success' : 'text-danger'}"><i class="bi bi-${requirements.lowercase ? 'check' : 'x'}-circle me-1"></i>Minimal 1 huruf kecil</div>
                        <div class="${requirements.number ? 'text-success' : 'text-danger'}"><i class="bi bi-${requirements.number ? 'check' : 'x'}-circle me-1"></i>Minimal 1 angka</div>
                        <div class="${requirements.symbol ? 'text-success' : 'text-danger'}"><i class="bi bi-${requirements.symbol ? 'check' : 'x'}-circle me-1"></i>Minimal 1 simbol</div>
                    </div>
                `;
                requirementsDiv.html(requirementsHtml);
            }

            // Function untuk mengecek match password (sama seperti sebelumnya)
            function checkPasswordMatch() {
                const newPassword = $('#update_password-new_password').val();
                const confirmPassword = $('#update_password-confirm_new_password').val();
                const confirmMsg = $('#update_password-confirm_new_password-msg');

                if (newPassword === '' && confirmPassword === '') {
                    confirmMsg.html('').removeClass('text-success text-danger');
                    return true;
                }

                if (newPassword === '' || confirmPassword === '') {
                    confirmMsg.html('').removeClass('text-success text-danger');
                    return false;
                }

                if (newPassword === confirmPassword) {
                    confirmMsg.html(
                        '<small class="text-success"><i class="bi bi-check-circle me-1"></i>Password cocok</small>'
                    ).removeClass('text-danger').addClass('text-success');
                    return true;
                } else {
                    confirmMsg.html(
                        '<small class="text-danger"><i class="bi bi-x-circle me-1"></i>Password tidak cocok</small>'
                    ).removeClass('text-success').addClass('text-danger');
                    return false;
                }
            }

            // Event listeners
            $('#update_password-new_password').on('input', function() {
                displayPasswordStrength($(this).val());
                checkPasswordMatch();
            });

            $('#update_password-confirm_new_password').on('input', checkPasswordMatch);

            $('#form-update_password').on('submit', function(e) {
                const newPassword = $('#update_password-new_password').val();
                const {
                    score,
                    requirements
                } = checkPasswordStrength(newPassword);
                const isMatch = checkPasswordMatch();

                if (score < 100 || !isMatch) {
                    e.preventDefault();
                    if (score < 100) {
                        alert('Password tidak memenuhi semua requirements!');
                    } else if (!isMatch) {
                        alert('Password tidak cocok!');
                    }
                }
            });

            // Toggle show/hide password (untuk semua field)
            $('.toggle-password').on('click', function(e) {
                e.preventDefault();

                const $toggle = $(this);
                const $inputGroup = $toggle.closest('.input-group');
                const $passwordInput = $inputGroup.find('input');
                const $icon = $toggle.find('i');

                const isPassword = $passwordInput.attr('type') === 'password';

                // Toggle type
                $passwordInput.attr('type', isPassword ? 'text' : 'password');

                // Toggle icon
                $icon.toggleClass('bi-eye bi-eye-slash');

                // Toggle aria-label dan tooltip
                const newLabel = isPassword ? 'Sembunyikan password' : 'Tampilkan password';
                $toggle.attr('aria-label', newLabel).attr('data-bs-original-title', newLabel);

                // Update tooltip jika Bootstrap tooltip aktif
                const tooltip = bootstrap.Tooltip.getInstance($toggle[0]);
                if (tooltip) {
                    tooltip.setContent({
                        '.tooltip-inner': newLabel
                    });
                }
            });
        });

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

        $('#update-photo_profile').change(function() {
            previewImageFromInput(this, '#update-photo_profile-preview');
        });

        $('#form-update_photo').submit(function(e) {
            e.preventDefault();

            formMode = 'update_photo'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'patch')

            callApi(formData, "{{ url('admin/profile/update_photo') }}") // URL langsung
        });

        $('#form-update_password').submit(function(e) {
            e.preventDefault();

            formMode = 'update_password'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'patch')

            callApi(formData, "{{ url()->current() }}" + "/password")
        });

        $('#form-update_profil').submit(function(e) {
            e.preventDefault();

            formMode = 'update_profil'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'patch')

            callApi(formData, "{{ url('admin/profile/update_profil') }}") // URL langsung
        });

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
