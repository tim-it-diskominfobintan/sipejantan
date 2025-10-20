<style>
    .modal-header {
        background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 15px 20px;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }

    .detail-modal .modal-content {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: none;
    }

    .detail-modal .modal-header {
        background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 15px 20px;
    }

    .detail-modal .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }

    .detail-modal .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .detail-modal .btn-close:hover {
        opacity: 1;
    }

    .detail-modal .modal-body {
        padding: 25px;
    }
</style>

<div class="modal modal-blur fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-create">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-createLabel"><i class="bi bi-box-seam me-2"></i> Tambah Data
                        Barang
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create-nama_barang" class="form-label">Nama Barang <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" id="create-nama_barang" name="nama_barang"
                                            class="form-control" placeholder="Masukkan Nama Barang">
                                    </div>
                                    <div id="create-nama_barang-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create-satuan" class="form-label">Satuan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-grid"></i></span>
                                        <select id="create-satuan" name="satuan" class="form-select">
                                            <option value="">Pilih Satuan</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Unit">Unit</option>
                                            <option value="Set">Set</option>
                                            <option value="Pack">Pack</option>
                                            <option value="Box">Box</option>
                                            <option value="Dus">Dus</option>
                                            <option value="Lusin">Lusin</option>
                                            <option value="Meter">Meter</option>
                                            <option value="Roll">Roll</option>
                                        </select>
                                    </div>
                                    <div id="create-satuan-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light px-4" data-bs-dismiss="modal"> <i
                            class="bi bi-x-circle me-2"></i>Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-plus-circle me-2"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-update" tabindex="-1" aria-labelledby="modal-updateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="form-update">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-updateLabel"><i class="bi bi-box-seam me-2"></i> Ubah Data
                        Barang
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="update-nama_barang" class="form-label">Nama Barang <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" id="update-nama_barang" name="nama_barang"
                                            class="form-control" placeholder="Masukkan Nama Barang">
                                    </div>
                                    <div id="update-nama_barang-msg"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="update-satuan" class="form-label">Satuan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-grid"></i></span>
                                        <select id="update-satuan" name="satuan" class="form-select">
                                            <option value="">Pilih Satuan</option>
                                            <option value="Pcs">Pcs</option>
                                            <option value="Unit">Unit</option>
                                            <option value="Set">Set</option>
                                            <option value="Pack">Pack</option>
                                            <option value="Box">Box</option>
                                            <option value="Dus">Dus</option>
                                            <option value="Lusin">Lusin</option>
                                            <option value="Meter">Meter</option>
                                            <option value="Roll">Roll</option>
                                        </select>
                                    </div>
                                    <div id="update-satuan-msg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control" id="update-id_barang" name="id_barang">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-opname" tabindex="-1" aria-labelledby="modal-opnameLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-opname">
                <div class="modal-header">
                    <h1 class="modal-title" id="modal-createLabel"><i class="bi bi-boxes me-2"></i> Tambah Stok
                        Opname</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group mb-3 col-6">
                            <label for="opname-barang" class="form-label">Detail Barang</label>
                            <select id="opname-barang" name="detail_barang_id" class="form-select select2 ">
                                <option value="">Pilih Detail Barang</option>
                            </select>
                            <div id="opname-barang-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-tanggal_opname" class="form-label">Tanggal Opname</label>
                            <input type="date" id="opname-tanggal_opname" name="tanggal_opname"
                                class="form-control">
                            <div id="opname-tanggal_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-jenis_opname" class="form-label">Jenis Opname</label>
                            <select name="jenis_opname" id="opname-jenis_opname" class="form-select">
                                <option value="" disabled selected>Pilih Jenis Opname</option>
                                <option value="masuk">Masuk</option>
                                <option value="keluar">Keluar</option>
                                <option value="rusak">Rusak</option>
                            </select>
                            <div id="opname-jenis_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-jumlah_opname" class="form-label">Jumlah Opname</label>
                            <input type="number" id="opname-jumlah_opname" name="jumlah_opname"
                                class="form-control" min="0">
                            <div id="opname-jumlah_opname-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6" id="container-no_bukti">
                            <label for="opname-no_bukti" class="form-label">No Bukti / ID Perbaikan</label>
                            <!-- Default: text input -->
                            <input type="text" id="opname-no_bukti" name="no_bukti" class="form-control"
                                placeholder="No Bukti">
                            <div id="opname-no_bukti-msg"></div>
                        </div>

                        <div class="form-group mb-3 col-6">
                            <label for="opname-keterangan" class="form-label">Keterangan</label>
                            <input type="text" id="opname-keterangan" name="keterangan" class="form-control">
                            <div id="opname-keterangan-msg"></div>
                        </div>

                        <input type="hidden" id="opname-barang_id" name="barang_id" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary"> <i class="bi bi-floppy me-2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal List Opname -->
<div class="modal fade" id="modal-list-opname" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-list-check me-2"></i> Riwayat Stok Opname</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover" id="table-opname">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Tanggal Opname</th>
                            <th>Jenis Opname</th>
                            <th>Jumlah</th>
                            <th>No Bukti / ID Perbaikan</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi lewat JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generate kode barang otomatis
        function generateKodeBarang() {
            const prefix = 'BRG';
            const timestamp = new Date().getTime().toString().substr(-5);
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            return `${prefix}${timestamp}${random}`;
        }

        // Set kode barang saat modal dibuka
        const modalCreate = document.getElementById('modal-create');
        modalCreate.addEventListener('show.bs.modal', function() {
            document.getElementById('create-kode_barang').value = generateKodeBarang();
        });

        // Generate kode baru saat tombol ditekan
        document.getElementById('generate-kode').addEventListener('click', function() {
            document.getElementById('create-kode_barang').value = generateKodeBarang();
        });

        // Preview foto barang
        document.getElementById('create-foto_barang').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const errorDiv = this.closest('.form-group').querySelector('.error-msg');

            if (errorDiv) {
                errorDiv.remove();
            }

            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024;

                if (!allowedTypes.includes(file.type)) {
                    showError('create-foto_barang', 'Format file harus JPEG, PNG, atau JPG!');
                    this.value = '';
                    document.getElementById('foto-preview').style.display = 'none';
                    return;
                }

                if (file.size > maxSize) {
                    showError('create-foto_barang', 'Ukuran file maksimal 2MB!');
                    this.value = '';
                    document.getElementById('foto-preview').style.display = 'none';
                    return;
                }

                // Jika valid, tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('foto-preview');
                    preview.style.display = 'block';
                    preview.querySelector('img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Hapus foto preview
        document.getElementById('remove-foto').addEventListener('click', function() {
            document.getElementById('create-foto_barang').value = '';
            document.getElementById('foto-preview').style.display = 'none';
        });

        const barcodeModal = document.getElementById('barcodeModal');
        barcodeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kodeBarang = button.getAttribute('data-kode');

            // Set kode barang di modal
            document.getElementById('barcode-kode-text').textContent = kodeBarang;

            // Hapus QRCode lama biar gak numpuk
            document.getElementById("qrcode").innerHTML = "";

            // Generate QRCode baru
            new QRCode(document.getElementById("qrcode"), {
                text: kodeBarang,
                width: 150,
                height: 150,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        // Fungsi cetak barcode
        document.getElementById('print-barcode').addEventListener('click', function() {
            const printContent = `
                    <div style="text-align: center; padding: 20px;" >
                        <h3>Barcode Barang</h3>
                        <div style="display: flex; justify-content: center;">
                            ${document.getElementById('qrcode').innerHTML}
                        </div>
                        <p style="margin-top: 15px; font-weight: bold;">${document.getElementById('barcode-kode-text').textContent}</p>
                        <p>${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                    <html>
                        <head>
                            <title>Cetak Barcode</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                @media print {
                                    @page { margin: 0; }
                                    body { margin: 1.6cm; }
                                }
                            </style>
                        </head>
                        <body onload="window.print(); window.close();">
                            ${printContent}
                        </body>
                    </html>
                `);
            printWindow.document.close();
        });
    });
</script>
