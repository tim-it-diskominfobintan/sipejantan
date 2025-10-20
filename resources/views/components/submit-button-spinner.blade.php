<script>
    function startSpinSubmitBtn(formSelector, label) {
        $(`${formSelector} button[type="submit"]`)
            .html(label ?? renderBtnSpinner())
            .prop('disabled', true)
    }

    function stopSpinSubmitBtn(formSelector, fallbackLabel) {
        $(`${formSelector} button[type="submit"]`)
            .html(fallbackLabel ?? 'Simpan')
            .prop('disabled', false)
    }

    function renderBtnSpinner(text = `{{ __('Memproses') }}`) {
        return `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            ${text}
        `
    }
</script>
