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
<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modal-detailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="header-content">
                    <div class="road-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-road">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 19l4 -14" />
                            <path d="M16 5l4 14" />
                            <path d="M12 8v-2" />
                            <path d="M12 13v-2" />
                            <path d="M12 18v-2" />
                        </svg>
                    </div>
                    <div class="header-text">
                        <h5 class="modal-title mb-1" id="modal-detailLabel">Jalan Raya Utama</h5>
                        <p class="mb-0">Detail Informasi Jalan</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <div class="detail-item">
                            <h6>Panjang Jalan</h6>
                            <div id="detail-panjang"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-item">
                            <h6>Status Jalan</h6>
                            <div id="detail-penanggung_jawab_id"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-item">
                            <h6>Kelurahan</h6>
                            <div id="detail-kelurahan"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-item">
                            <h6>Kecamatan</h6>
                            <div id="detail-kecamatan"></div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Peta Jalan</h5>
                <div class="position-relative">
                    <div id="map" class="map-container"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                        class="bi bi-x-circle me-2"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>
