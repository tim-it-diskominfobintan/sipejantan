<script>
    function renderRowSpinner(colspanValue = 20, selector = '#table > tbody') {
        $(selector).html(`
            <tr class="odd" style="background-color: #F8F9FA">
                <td valign="top" colspan="${colspanValue}" class="dataTables_empty loading-data">
                    <div class="d-flex justify-content-center my-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </td>
            </tr>`
        )
    }
</script>