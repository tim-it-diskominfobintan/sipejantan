function datatablesDefaultConfig(pageLength = 25) {
    let lengthMenu = [
        [25, 50, 100],
        [25, 50, 100]
    ]

    return {
        lengthMenu: lengthMenu,
        pageLength: pageLength,
        responsive: true,
        serverSide: true,
        ordering: false,
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            infoEmpty: "Data kosong",
            zeroRecords: "Data tidak ada",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "&laquo;",
                last: "&raquo;",
                next: ">",
                previous: "<"
            },
        }
    }
}
