function handleAjaxJqueryError(response, options = {}) {
    const {
        formPrefix = null,                 // Mode form untuk validasi
        msgSuffix = 'msg',              // ID prefix untuk tampilkan error field
        onValidation = null,           // Callback khusus validasi error
        onUnauthorized = null,        // Callback khusus unauthorized
        redirectToLogin = `/login` // Bisa override jika route beda
    } = options;

    const status = response.status;
    const data = response.responseJSON || {};

    // 422 Unprocessable Entity - Validasi Form
    if (status === 422) {
        const errors = data.errors ?? {};

        // if (typeof onValidation === 'function') {
        //     onValidation(errors);
        // } else if (formPrefix) {
        clearValidationMessage(formPrefix, msgSuffix);
        validationMessageRender(errors, formPrefix, msgSuffix);
        // }

        return;
    }

    // 401 Unauthorized atau 403 Forbidden
    if (status === 401 || status === 403) {
        showSwal(data.message || 'Akses ditolak.', status);

        if (status === 401) {
            if (typeof onUnauthorized === 'function') {
                onUnauthorized();
            } else {
                setTimeout(() => {
                    window.location.replace(redirectToLogin);
                }, 1500);
            }
        }

        return;
    }

    // 419 Page Expired - Session Expired
    if (status === 419) {
        showSwal(data.message || 'Session kadaluarsa. Silakan login ulang.', status);

        setTimeout(() => {
            window.location.reload();
        }, 1500);

        return;
    }

    // Default: Error Umum (500, 404, dll)
    showSwal(data.message || 'Terjadi kesalahan.', status);
}
