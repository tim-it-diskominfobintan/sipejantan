@extends('layouts.admin.main')
@section('content')
    <style>
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px auto;
            max-width: 900px;
        }

        .form-header {
            background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .form-section {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e3e6f0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 1.2rem;
            color: #4e73df;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            font-size: 1.4rem;
        }

        .form-label {
            font-weight: 500;
            color: #4e4e4e;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select,
        .form-textarea {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #d1d3e2;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .form-textarea {
            min-height: 400px;
            resize: vertical;
        }

        .input-group-text {
            background-color: #f8f9fc;
            border-radius: 8px 0 0 8px;
        }

        .btn-primary {
            background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background-color: #f8f9fc;
            color: #858796;
            border: 1px solid #d1d3e2;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #eaecf4;
            color: #6c757d;
        }

        .required-field::after {
            content: " *";
            color: #e74a3b;
        }

        .form-note {
            background-color: #f8f9fc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4e73df;
            margin-top: 20px;
        }

        .upload-card {
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            background-color: #f8f9fc;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-card:hover {
            border-color: #4e73df;
            background-color: #eaf0ff;
        }

        #laporan-preview-container .preview-wrapper {
            position: relative;
            width: 280px;
            height: 200px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
        }

        #laporan-preview-container .preview-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #laporan-preview-container .remove-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background: rgba(0, 0, 0, .6);
            color: #fff;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            line-height: 20px;
            font-size: 16px;
        }

        .preview-wrapper {
            max-height: 180px;
            max-width: 900px;
            border-radius: 6px;
            border: 1px solid #1273d3;
        }

        .remove-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            background: rgba(220, 53, 69, 0.9);
            border: none;
            color: #fff;
            border-radius: 50%;
            font-size: 14px;
            width: 22px;
            height: 22px;
            cursor: pointer;
            line-height: 1;
        }

        .upload-box {
            cursor: pointer;
            background: #f8f9fa;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .upload-box img {
            max-height: 100%;
            max-width: 100%;
        }

        .select2-container .select2-selection--single {
            height: calc(2.7rem + 2px) !important;
            /* sesuaikan dengan input */
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
        }
    </style>
    <div class="container mt-4">
        <div class="row mb-4">
            <!-- Data Pelapor -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-person-circle me-1"></i> Data Pelapor
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <!-- Foto -->
                            <div class="me-3">
                                <img src="{{ asset('storage/' . $pelapor->foto_identitas) }}" alt="Foto Identitas"
                                    class="rounded shadow-sm border" style="width:150px; height:120px; object-fit:cover;">
                            </div>
                            <!-- Detail -->
                            <div class="w-100">
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">NIK</div>
                                    <div class="col-9">{{ $pelapor->nik }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">Nama</div>
                                    <div class="col-9">{{ $pelapor->nama }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">Email</div>
                                    <div class="col-9">{{ $pelapor->email }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">No Telp</div>
                                    <div class="col-9">{{ $pelapor->no_hp }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-3 fw-bold text-muted">Alamat</div>
                                    <div class="col-9">
                                        {{ $pelapor->alamat }}, Kel.
                                        {{ $pelapor->kelurahan->nama_kelurahan }}, Kec.
                                        {{ $pelapor->kecamatan->nama_kecamatan }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Laporan -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-file-earmark-text me-1"></i> Data Laporan
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <!-- Foto Laporan -->
                            <div class="me-3 text-center">
                                <img src="{{ asset('assets/global/img/laporan.png') }}" alt="Foto Laporan"
                                    class="rounded shadow-sm border"
                                    style="width:120px; height:100px; object-fit:cover; cursor:pointer;"
                                    data-bs-toggle="modal" data-bs-target="#modalLaporan">

                                <div class="small text-muted mt-1">Lihat file</div>
                            </div>
                            <!-- Detail -->
                            <div class="w-100">
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">No Laporan</div>
                                    <div class="col-9">
                                        <span class="badge bg-blue-lt">{{ $laporan->no_laporan }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">Status</div>
                                    <div class="col-9">
                                        <span
                                            class="badge 
                                @if ($laporan->status_laporan == 'pending') bg-red text-red-fg
                                @elseif($laporan->status_laporan == 'proses') bg-blue text-blue-fg 
                                @elseif($laporan->status_laporan == 'diterima') bg-orange text-orange-fg 
                                @else bg-green text-green-fg @endif">
                                            {{ ucfirst($laporan->status_laporan) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3 fw-bold text-muted">Tanggal</div>
                                    <div class="col-9">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d F Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 fw-bold text-muted">Deskripsi</div>
                                    <div class="col-9 text-secondary">
                                        {{ $laporan->deskripsi_laporan }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ubah Perbaikan</h5>
            </div>
            <div class="card-body">
                <form id="form-update" enctype="multipart/form-data">
                    @csrf
                    <!-- Pilih Laporan -->
                    <div class="form-section">
                        {{-- <div class="section-title"><i class="bi bi-file-earmark-text"></i> Pilih Laporan</div>
                        <div class="mb-3">
                            <label for="laporan_id" class="form-label">No Laporan <span class="text-danger">*</span></label>
                            <select id="update-laporan_id" name="laporan_id" class="form-select">
                                <option value="">-- Pilih Laporan --</option>
                                @foreach ($laporan as $item)
                                    <option value="{{ $item->id_laporan }}">{{ $item->no_laporan }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Detail Laporan -->
                        <div id="laporan-detail" class="card shadow-sm border-0 mt-3" style="display:none;">
                            <div class="card shadow-sm rounded-3 border-0">
                                <div class="card-header bg-light fw-bold text-primary">
                                    <i class="bi bi-info-circle me-2"></i> Detail Laporan
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <!-- Kolom kiri: informasi -->
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-4 fw-bold"><i class="bi bi-hash text-primary me-1"></i> No
                                                    Laporan</div>
                                                <div class="col-8">
                                                    <div id="detail-no"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-4 fw-bold"><i
                                                        class="bi bi-person-fill text-success me-1"></i> Pelapor</div>
                                                <div class="col-8">
                                                    <div id="detail-pelapor"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-4 fw-bold"><i
                                                        class="bi bi-calendar-event text-warning me-1"></i> Tanggal</div>
                                                <div class="col-8">
                                                    <div id="detail-tanggal"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-4 fw-bold"><i class="bi bi-tools text-danger me-1"></i>
                                                    Teknisi</div>
                                                <div class="col-8">
                                                    <div id="detail-teknisi"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-4 fw-bold"><i class="bi bi-geo-alt-fill text-info me-1"></i>
                                                    Asset</div>
                                                <div class="col-6 d-flex">
                                                    <div id="detail-asset"></div>
                                                    <a id="google-map" target="_blank"
                                                        class="btn btn-sm btn-outline-primary ms-2">
                                                        <i class="bi bi-map me-2"></i> Lihat di Map
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-4 fw-bold"><i
                                                        class="bi bi-card-text text-secondary me-1"></i> Deskripsi</div>
                                                <div class="col-8">
                                                    <div id="detail-deskripsi"></div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4 fw-bold"><i class="bi bi-flag-fill text-dark me-1"></i>
                                                    Status</div>
                                                <div class="col-8">
                                                    <div id="detail-status_laporan"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Kolom kanan: foto -->
                                        <div class="col-md-3 text-center">
                                            <div class="mb-3">
                                                <label class="fw-bold d-block mb-1">Foto Laporan</label>
                                                <img id="foto-laporan" class="img-fluid rounded shadow-sm"
                                                    style="max-height:150px;" alt="Foto Laporan">
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div>
                                                <label class="fw-bold d-block mb-1">Foto Identitas</label>
                                                <img id="foto-identitas" class="img-fluid rounded shadow-sm"
                                                    style="max-height:150px;" alt="Foto Identitas">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Perbaikan -->
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-tools"></i> Data Perbaikan</div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="keterangan" class="form-label">Keterangan <span
                                        class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="4">{{ $perbaikan->keterangan }}</textarea>
                                <div id="update-keterangan-msg"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="status_progress" class="form-label">Status Progress <span
                                            class="text-danger">*</span></label>
                                    <select name="status_progress" id="status_progress" class="form-select">
                                        <option value="">Pilih Status</option>
                                        <option value="proses"
                                            {{ $perbaikan->status_progress == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="selesai"
                                            {{ $perbaikan->status_progress == 'selesai' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                    <div id="update-status_progress-msg"></div>
                                </div>

                                <div class="col-md-10">
                                    <label class="form-label">Upload Foto Perbaikan</label>
                                    <input type="file" name="file_perbaikan[]" id="file_perbaikan"
                                        class="form-control" multiple>
                                    <div id="preview-container" class="mt-3 d-flex flex-wrap gap-2"></div>
                                    <div id="update-file_perbaikan-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-lightbulb-fill me-2"></i>Barang
                                    Digunakan</h5>
                                <button type="button" id="add-detail_barang" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i> Tambah Barang Digunakan
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered" id="detail_barang-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50%">Nama Barang</th>
                                    <th style="width:10%">Jumlah</th>
                                    <th style="width:25%">Tanggal Akhir Garansi</th>
                                    <th style="width:10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barang_digunakan as $bd)
                                    <tr>
                                        <td>
                                            <select name="detail_barang_id[]" class="form-select detail_barang-select">
                                                <option value="">Pilih Detail Barang</option>
                                                @foreach ($detailBarang as $b)
                                                    <option value="{{ $b->id_detail_barang }}"
                                                        data-garansi="{{ $b->tanggal_akhir_garansi }}"
                                                        {{ $bd->detailbarang && $bd->detailbarang->id_detail_barang == $b->id_detail_barang ? 'selected' : '' }}>
                                                        {{ $b->kode_barang }} - {{ $b->barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah_detail_barang[]" class="form-control"
                                                min="1" value="{{ $bd->jumlah_opname }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control tanggal-garansi"
                                                value="{{ $bd->detailbarang->tanggal_akhir_garansi ?? '' }}" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger remove-detail_barang">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Jika kosong, tampilkan baris baru --}}
                                    <tr>
                                        <td>
                                            <select name="detail_barang_id[]" class="form-select detail_barang-select">
                                                <option value="">Pilih Detail Barang</option>
                                                @foreach ($detailBarang as $b)
                                                    <option value="{{ $b->id_detail_barang }}"
                                                        data-garansi="{{ $b->tanggal_akhir_garansi }}">
                                                        {{ $b->kode_barang }} - {{ $b->barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah_detail_barang[]" class="form-control"
                                                min="1" value="1">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control tanggal-garansi" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger remove-detail_barang">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold text-danger mb-0">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Barang Rusak (Diganti)
                            </h5>
                            <button type="button" id="add-barang-rusak" class="btn btn-danger">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Barang Rusak
                            </button>
                        </div>

                        <table class="table table-bordered align-middle" id="barang-rusak-table">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width:40%">Nama Barang</th>
                                    <th style="width:10%">Jumlah Barang</th>
                                    <th style="width:10%">Tanggal Akhir Garansi</th>
                                    <th style="width:35%">Keterangan</th>
                                    <th style="width:5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barang_rusak as $br)
                                    <tr>
                                        <td>
                                            <select name="barang_rusak_id[]" class="form-select barang-rusak-select">
                                                <option value="">Pilih Barang Rusak</option>
                                                @foreach ($detailBarang as $b)
                                                    <option value="{{ $b->id_detail_barang }}"
                                                        data-garansi-rusak="{{ $b->tanggal_akhir_garansi }}"
                                                        {{ $br->detailbarang && $br->detailbarang->id_detail_barang == $b->id_detail_barang ? 'selected' : '' }}>
                                                        {{ $b->kode_barang }} - {{ $b->barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah_barang_rusak[]" class="form-control"
                                                min="1" value="{{ $br->jumlah_opname }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control tanggal-garansi-rusak"
                                                value="{{ $br->detailbarang->tanggal_akhir_garansi ?? '' }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan_rusak[]" class="form-control"
                                                value="{{ $br->keterangan ?? '' }}"
                                                placeholder="Contoh: Mati total, pecah, dsb">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger remove-barang-rusak">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <select name="barang_rusak_id[]" class="form-select barang-rusak-select">
                                                <option value="">Pilih Barang Rusak</option>
                                                @foreach ($detailBarang as $b)
                                                    <option value="{{ $b->id_detail_barang }}"
                                                        data-garansi-rusak="{{ $b->tanggal_akhir_garansi }}">
                                                        {{ $b->kode_barang }} - {{ $b->barang->nama_barang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="jumlah_barang_rusak[]" class="form-control"
                                                min="1" value="1">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control tanggal-garansi-rusak" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan_rusak[]" class="form-control"
                                                placeholder="Contoh: Mati total, pecah, dsb">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger remove-barang-rusak">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" id="update-id_pelapor" value="{{ $pelapor->id_pelapor }}">
                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-arrow-repeat me-2"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i> Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.perbaikan.modal')
@endsection
@push('script')
    <script>
        // ========================= BARANG DIGUNAKAN ========================= //

        function initBarangDigunakan() {
            // Inisialisasi Select2
            $('.detail_barang-select').select2({
                placeholder: "Pilih Detail Barang",
                width: '100%'
            });

            // Isi tanggal garansi saat edit pertama kali
            $('.detail_barang-select').each(function() {
                const garansi = $(this).find(':selected').data('garansi') || '';
                $(this).closest('tr').find('.tanggal-garansi').val(garansi);
            });

            // Saat dropdown berubah → update tanggal garansi & filter
            $(document).on('change', '.detail_barang-select', function() {
                const garansi = $(this).find(':selected').data('garansi') || '';
                $(this).closest('tr').find('.tanggal-garansi').val(garansi);
                filterBarangDigunakanOptions();
            });

            // Tombol tambah baris baru
            $('#add-detail_barang').on('click', function() {
                const newRow = `
        <tr>
            <td>
                <select name="detail_barang_id[]" class="form-select detail_barang-select">
                    <option value="">Pilih Detail Barang</option>
                    @foreach ($detailBarang as $b)
                        <option value="{{ $b->id_detail_barang }}" data-garansi="{{ $b->tanggal_akhir_garansi }}">
                            {{ $b->kode_barang }} - {{ $b->barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="jumlah_detail_barang[]" class="form-control" min="1" value="1"></td>
            <td><input type="text" class="form-control tanggal-garansi" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger remove-detail_barang"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;

                $('#detail_barang-table tbody').append(newRow);

                // Re-inisialisasi Select2 untuk elemen baru
                $('.detail_barang-select').select2({
                    placeholder: "Pilih Detail Barang",
                    width: '100%'
                });

                filterBarangDigunakanOptions();
            });

            // Hapus baris barang digunakan
            $(document).on('click', '.remove-detail_barang', function() {
                $(this).closest('tr').remove();
                filterBarangDigunakanOptions();
            });

            // Jalankan filter awal
            filterBarangDigunakanOptions();
        }

        // Fungsi filter: disable barang yang sudah dipilih
        function filterBarangDigunakanOptions() {
            let selected = [];

            $('select[name="detail_barang_id[]"]').each(function() {
                const val = $(this).val();
                if (val) selected.push(val);
            });

            $('select[name="detail_barang_id[]"]').each(function() {
                const current = $(this).val();
                $(this).find('option').each(function() {
                    const val = $(this).val();
                    if (val && val !== current && selected.includes(val)) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });

            // Refresh tampilan Select2
            $('select[name="detail_barang_id[]"]').trigger('change.select2');
        }


        // ========================= BARANG RUSAK ========================= //

        function initBarangRusak() {
            // Inisialisasi Select2
            $('.barang-rusak-select').select2({
                placeholder: "Pilih Barang Rusak",
                width: '100%'
            });

            // Isi tanggal garansi saat edit pertama kali
            $('.barang-rusak-select').each(function() {
                const garansi = $(this).find(':selected').data('garansi-rusak') || '';
                $(this).closest('tr').find('.tanggal-garansi-rusak').val(garansi);
            });

            // Saat dropdown berubah → update tanggal garansi & filter
            $(document).on('change', '.barang-rusak-select', function() {
                const garansi = $(this).find(':selected').data('garansi-rusak') || '';
                $(this).closest('tr').find('.tanggal-garansi-rusak').val(garansi);
                filterBarangRusakOptions();
            });

            // Tombol tambah baris baru
            $('#add-barang-rusak').on('click', function() {
                const newRow = `
        <tr>
            <td>
                <select name="barang_rusak_id[]" class="form-select barang-rusak-select">
                    <option value="">Pilih Barang Rusak</option>
                    @foreach ($detailBarang as $b)
                        <option value="{{ $b->id_detail_barang }}" data-garansi-rusak="{{ $b->tanggal_akhir_garansi }}">
                            {{ $b->kode_barang }} - {{ $b->barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="jumlah_barang_rusak[]" class="form-control" min="1" value="1"></td>
            <td><input type="text" class="form-control tanggal-garansi-rusak" readonly></td>
            <td><input type="text" name="keterangan_rusak[]" class="form-control" placeholder="Contoh: Mati total, pecah, dsb"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger remove-barang-rusak"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;

                $('#barang-rusak-table tbody').append(newRow);

                // Re-inisialisasi Select2
                $('.barang-rusak-select').select2({
                    placeholder: "Pilih Barang Rusak",
                    width: '100%'
                });

                filterBarangRusakOptions();
            });

            // Hapus baris barang rusak
            $(document).on('click', '.remove-barang-rusak', function() {
                $(this).closest('tr').remove();
                filterBarangRusakOptions();
            });

            // Jalankan filter awal
            filterBarangRusakOptions();
        }

        // Fungsi filter: disable barang rusak yang sudah dipilih
        function filterBarangRusakOptions() {
            let selected = [];

            $('select[name="barang_rusak_id[]"]').each(function() {
                const val = $(this).val();
                if (val) selected.push(val);
            });

            $('select[name="barang_rusak_id[]"]').each(function() {
                const current = $(this).val();
                $(this).find('option').each(function() {
                    const val = $(this).val();
                    if (val && val !== current && selected.includes(val)) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });

            $('select[name="barang_rusak_id[]"]').trigger('change.select2');
        }


        // ========================= PANGGIL SAAT SIAP ========================= //
        $(document).ready(function() {
            initBarangDigunakan();
            initBarangRusak();
        });


        // tampilkan detail laporan
        $('#update-laporan_id').on('change', function() {
            let laporanId = $(this).val();
            let detail = document.getElementById('laporan-detail');

            if (!laporanId) {
                detail.style.display = 'none';
                return;
            } else {
                detail.style.display = 'block';
            }

            $.ajax({
                url: 'laporan/' + laporanId, // endpoint detail laporan
                type: 'GET',
                success: function(res) {
                    console.log(res.foto_laporan)
                    $('#detail-no').text(res.no_laporan);
                    $('#detail-pelapor').text(res.pelapor.nik + ' - ' + res.pelapor.nama);
                    $('#detail-deskripsi').text(res.deskripsi_laporan);
                    $('#detail-tanggal').text(tanggalIndo(res.tanggal_laporan));
                    $('#detail-asset').text(res.asset.nama_asset);
                    $('#detail-status_laporan').text(res.status_laporan);
                    $('#detail-teknisi').text(
                        res.teknisi && res.teknisi.nama_teknisi ?
                        res.teknisi.nama_teknisi :
                        'Belum ada teknisi yang mengerjakan'
                    );
                    $('#foto-identitas').attr('src', '/storage/' + res.pelapor.foto_identitas);
                    $('#foto-laporan').attr('src', '/storage/' + res.foto_laporan);
                    $('#google-map').attr('href', 'https://www.google.com/maps?q=' + res.asset
                        .koordinat);
                    $('#laporan-detail').removeClass('d-none');
                }
            });
        });

        // preview multiple file
        $('#file_perbaikan').on('change', function() {
            $('#preview-container').html('');
            for (let file of this.files) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-container').append(`
                            <div class="position-relative">
                                <img src="${e.target.result}" class="rounded border" style="width:150px; height:100px; object-fit:cover;">
                            </div>
                        `);
                };
                reader.readAsDataURL(file);
            }
        });

        $('#form-update').submit(function(e) {
            e.preventDefault()

            formMode = 'update'

            const formData = new FormData(this)
            formData.append('_token', getCsrfToken())
            formData.append('_method', 'post')

            callApi(formData)
        })

        function callApi(formData, url = null) {
            const submitLabel = $(`#form-${formMode} button[type="submit"]`).text()
            var id_pelapor = $('#update-id_pelapor').val()
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
                    showSwal(response.message, response.status)

                    window.location.href = `/admin/perbaikan/${id_pelapor}`;
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
