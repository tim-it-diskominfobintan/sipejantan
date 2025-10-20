@extends('layouts.admin.main')

@section('action-header')
    {{-- UNTUK TOMBOL TAMBAH DATA DLL, KALO GAADA KOSONGIN AJA --}}
    <div class="col-auto ms-auto d-print-none">
        {{-- <div class="btn-list">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </button>
        </div> --}}
        <div class="btn-list justify-content-between">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                <i class="bi bi-plus-lg me-2"></i> Tambah {{ $title }}
            </button>
            <a href="{{ url('admin/asset/create') }}" class="btn btn-success" hidden>
                <i class="bi bi-printer me-2"></i> Cetak {{ $title }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List {{ $title }}</h3>
                    {{-- <button id="reload" class="ms-5">Reload</button> --}}
                </div>
                <div class="card-body py-0 px-0 mx-0">
                    <div class="table-responsive table-full-to-card-body">
                        <table class="table" id="table">
                            <thead>
                                <tr class="bg-body-tertiary">
                                    <th width="20px">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Mulai Garansi</th>
                                    <th>Akhir Garansi</th>
                                    <th>Stok Awal</th>
                                    <th>Stok Sekarang</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
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

    @include('admin.detail_barang.modal')
@endsection

@push('script')
    <script>
        let formMode = 'create'

        const table = $('#table').DataTable({
            serverSide: true,
            columnDefs: [{
                    targets: [-1],
                    className: "text-center",
                    responsivePriority: 1,
                    width: '15%'
                },
                {
                    targets: [0],
                    className: "text-center",
                    responsivePriority: 1,
                    width: '8%'
                },
                {
                    targets: [1],
                    className: "text-center",
                    responsivePriority: 1,
                    width: '15%'
                }
            ],
            ajax: {
                url: "{{ url()->current() }}",
                method: 'GET',
                beforeSend: function() {
                    renderRowSpinner();
                },
                error: function(response) {
                    handleAjaxJqueryError(response)
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
                    data: 'kode',
                    name: 'kode',
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang'
                },
                {
                    data: 'tanggal_garansi',
                    name: 'tanggal_garansi'
                },
                {
                    data: 'tanggal_akhir_garansi',
                    name: 'tanggal_akhir_garansi'
                },
                {
                    data: 'stok_awal',
                    name: 'stok_awal',
                },
                {
                    data: 'stok_sekarang',
                    name: 'stok_sekarang',
                },
                {
                    data: 'foto',
                    name: 'foto',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#reload').click(function() {
            table.ajax.reload()
        })

        $('#form-create').submit(function(e) {
            e.preventDefault()

            formMode = 'create'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData)
        })

        $('#table tbody').on('click', '.btn-detail', function() {
            let detail = $(this).data('detail')
            let stokAwal = $(this).data('stok_awal')
            let stokSekarang = $(this).data('sekarang')
            let status = '';
            // console.log(detail)
            $(`#detail-nama_barang`).text(detail.barang.nama_barang)
            $(`#detail-kode_barang`).text(detail.kode_barang)
            $(`#detail-stok_awal`).text((stokAwal ?? 0) + ' ' + detail.barang.satuan);
            $(`#detail-stok_sekarang`).text(stokSekarang);

            if (stokSekarang <= 0) {
                status = `<span class="badge bg-danger badge-stok text-white">
                                Tidak Tersedia
                            </span>`;
            } else {
                status = `<span class="badge bg-success badge-stok text-white">
                                ` + stokSekarang + ` ` + detail.barang.satuan + ` Tersedia
                            </span>`;
            }
            $(`#detail-tersedia`).html(status)

            let rawDate = detail.tanggal_mulai_garansi;
            if (rawDate) {
                let dateObj = new Date(rawDate);
                // format lokal Indonesia
                let formatted = dateObj.toLocaleDateString('id-ID', {
                    weekday: 'long', // Senin, Selasa, ...
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                $('#detail-tanggal_mulai_garansi').text(formatted);
            } else {
                $('#detail-tanggal_mulai_garansi').text('-');
            }
            $(`#detail-lama_garansi`).html(detail.lama_garansi + ' Bulan')

            let tanggalMulai = detail.tanggal_mulai_garansi; // ex: "2025-01-15"
            let lamaGaransi = parseInt(detail.lama_garansi); // ex: 12 bulan

            if (tanggalMulai && lamaGaransi) {
                let startDate = new Date(tanggalMulai);
                let endDate = new Date(startDate); // copy date
                endDate.setMonth(endDate.getMonth() + lamaGaransi); // tambah bulan sesuai garansi

                let now = new Date();

                let options = {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                };
                let endDateFormatted = endDate.toLocaleDateString('id-ID', options);

                let rentang = "";
                if (now > endDate) {
                    rentang =
                        `<span class="badge bg-danger badge-stok text-white"><i class="bi bi-exclamation-triangle-fill text-white me-1"></i> Garansi Habis (${endDateFormatted} bulan)</span>`;
                } else {
                    let selisihMs = endDate - now;
                    let selisihHari = Math.floor(selisihMs / (1000 * 60 * 60 * 24));
                    rentang =
                        `<span class="badge bg-success badge-stok text-white"><i class="bi bi-exclamation-triangle-fill text-white me-1"></i> ${endDateFormatted} Sisa (${selisihHari} hari)</span>`;
                }

                let forMasuk = detail.tanggal_masuk;
                if (forMasuk) {
                    let dateObj = new Date(forMasuk);
                    // format lokal Indonesia
                    let formatted1 = dateObj.toLocaleDateString('id-ID', {
                        weekday: 'long', // Senin, Selasa, ...
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    $('#detail-tanggal_masuk').text(formatted1);
                } else {
                    $('#detail-tanggal_masuk').text('-');
                }

                $('#detail-rentang_garansi').html(rentang);
            } else {
                $('#detail-rentang_garansi').html(`<span class="badge bg-secondary">-</span>`);
            }

            $(`#detail-foto_detail_barang`).html(`<img src="` + detail.foto_detail_barang_url + `" class="product-image"
                            alt="` + detail.barang.nama_barang + `">`)

            let kodeBarang = detail.kode_barang;

            // Kosongkan QR lama biar tidak dobel
            document.getElementById("detail-qrcode").innerHTML = "";

            // Generate QRCode baru
            new QRCode(document.getElementById("detail-qrcode"), {
                text: kodeBarang,
                width: 120,
                height: 120,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            $(`#detail-kode-text`).text(kodeBarang)
            $('#detailBarangModal').modal('show')

        })

        $('#table tbody').on('click', '.btn-opname', function() {
            let detail = $(this).data('detail')
            $('#opname-barang').val(detail.kode_barang + ' - ' + detail.barang.nama_barang);
            $('#opname-detail_barang_id').val(detail.id_detail_barang);
            $('#modal-opname').modal('show')
        });

        $('#form-opname').submit(function(e) {
            e.preventDefault()

            formMode = 'opname'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData, `{{ url('admin/detail_stok_opname') }}`);
        })

        // riwayat stok opname di modal
        $('#table tbody').on('click', '.btn-list-opname', function() {
            let detail = $(this).data('detail')
            let DetailBarangId = $(this).data('id');
            let BarangId = $(this).data('idbarang');
            // Kosongkan tabel dulu
            $('#table-opname tbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

            // Panggil API untuk ambil riwayat opname
            $.ajax({
                url: `{{ url('admin/detail/stok_opname/list') }}/${DetailBarangId}`,
                type: "GET",
                success: function(res) {
                    let rows = "";
                    if (res.length > 0) {
                        res.forEach(item => {
                            rows += `
                        <tr>
                            <td>${tanggalIndo(item.tanggal_opname)}</td>
                            <td>
                                ${item.jenis_opname === 'masuk' 
                                    ? '<span class="badge bg-green-lt">Masuk</span>' 
                                    : item.jenis_opname === 'keluar' 
                                        ? '<span class="badge bg-yellow-lt">Keluar</span>' 
                                        : '<span class="badge bg-red-lt">Rusak</span>'}
                            </td>
                            <td class="text-center">${item.jumlah_opname} ${item.detailbarang.barang.satuan ?? '-'}</td>
                            <td>${item.no_bukti ?? '-'}</td>
                            <td>${item.keterangan ?? '-'}</td>
                            <td>${item.created_by ?? '-'}</td>
                        </tr>
                    `;
                        });
                    } else {
                        rows =
                            `<tr><td colspan="7" class="text-center">Tidak ada data opname</td></tr>`;
                    }
                    $('#table-opname tbody').html(rows);
                },
                error: function() {
                    $('#table-opname tbody').html(
                        `<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>`
                    );
                }
            });
            $('#modal-list-opname').modal('show')
        });

        $('#table tbody').on('click', '.btn-update', function() {
            let detail = $(this).data('detail')

            $('#modal-update').modal('show')

            $(`#update-id_detail_barang`).val(detail.id_detail_barang)
            $(`#update-tanggal_masuk`).val(detail.tanggal_masuk)
            $(`#update-kode_barang`).val(detail.kode_barang)
            $(`#update-tanggal_mulai_garansi`).val(detail.tanggal_mulai_garansi)
            $(`#update-lama_garansi`).val(detail.lama_garansi)
            $(`#update-keterangan_barang`).val(detail.keterangan_barang)
        })

        $('#form-update').submit(function(e) {
            e.preventDefault()

            formMode = 'update'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'put')

            callApi(formData, `{{ url('admin/detail_barang') }}/${$('#update-id_detail_barang').val()}`)
        })

        $('#table tbody').on('click', '.btn-delete', function() {
            const id = $(this).data('id')
            console.log(id)
            showSwalConfirm('Hapus', 'Semua data yang terkait akan dihapus?', 'warning', function(
                result) {
                if (result) {
                    const formData = new FormData()

                    formData.append('_method', 'delete')
                    formData.append('_token', getCsrfToken())
                    formData.append('id', id)

                    callApi(formData, `{{ url('admin/detail_barang') }}/${id}`)
                }
            })
        })

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

                    window.location.reload()
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

        $('#opname-jenis_opname').on('change', function() {
            const container = $('#container-no_bukti');
            if (this.value === 'keluar' || this.value === 'rusak') {
                $.get('/trans_barang_perbaikan', function(data) {
                    let options = `<option value="" disabled selected>Pilih Transaksi Perbaikan</option>`;
                    data.forEach(item => {
                        options +=
                            `<option value="${item.id_perbaikan}">ID : ${item.id_perbaikan} - No Laporan : ${item.laporan.no_laporan ?? '-'}</option>`;
                    });

                    container.html(`
                        <label for="opname-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                        <select id="opname-no_bukti" name="no_bukti" class="form-select">
                            ${options}
                        </select>
                        <div id="opname-no_bukti-msg"></div>
                    `);
                });
            } else {
                container.html(`
                    <label for="opname-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                    <input type="text" id="opname-no_bukti" name="no_bukti" class="form-control" placeholder="No Bukti">
                    <div id="opname-no_bukti-msg"></div>
                `);
            }
        });
    </script>
@endpush
