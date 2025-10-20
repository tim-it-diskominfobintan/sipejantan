function showSwal(message, status = 'info', confirmButton = false) {
    let title
    let timer = 2e3
    let confirmButtonClass = 'btn btn-primary-light mt-3 me-3'
    let cancelButton = 'btn btn-secondary-light mt-3'
    let newMessage = message

    function checkStatusType(val) {
        const angka = Number(val)

        if (!isNaN(angka)) {
            return Number.isInteger(angka) ? "integer" : "number";
        } else {
            return "string";
        }
    }

    if (checkStatusType(status) === 'string') {
        switch (status) {
            case 'info':
                title = 'Info'
                timer = 2e3
                break;
            case 'success':
                title = 'Sukses'
                timer = 2e3
                break;
            case 'warning':
                title = 'Oops..'
                timer = false
                break;
            case 'error':
                title = 'Gagal'
                timer = false
                confirmButtonClass = 'btn btn-danger-light mt-3 me-3'
                break;
            default:
                title = 'Lain-lain..'
                break;
        }
    } else {
        if (status >= 100 && status <= 199) {
            title = 'Info'
            status = 'info'
            timer = 2e3
        } else if (status >= 200 && status <= 299) {
            title = 'Sukses'
            status = 'success'
            timer = 2e3
        } else if (status >= 300 && status <= 399) {
            title = 'Info'
            status = 'info'
            timer = 2e3
        } else if (status >= 400 && status <= 499) {
            if (status == 401) {
                title = 'Gagal'
                newMessage = 'Anda tidak terautentikasi.'
            } else if (status == 403) {
                title = 'Gagal'
                newMessage = 'Anda tidak punya otoritas untuk mengakses resource ini.'
            } else if (status == 419) {
                title = 'Gagal'
                newMessage = 'Session expired, halaman akan direfresh.'
            } else if (status == 429) {
                title = 'Gagal'
                newMessage = 'Terlalu banyak permintaan, coba lagi nanti.'
            } else {
                title = 'Gagal'
            }

            status = 'warning'
            timer = false
            confirmButtonClass = 'btn btn-danger-light mt-3 me-3'

        } else if (status >= 500 && status <= 599) {
            title = 'Gagal'
            status = 'error'
            timer = false
            confirmButtonClass = 'btn btn-danger-light mt-3 me-3'
        } else {
            // fallback
            title = 'Gagal'
            status = 'error'
            timer = false
            newMessage = 'Terjadi kesalahan yang tidak terdefinisi!'
        }
    }

    return Swal.fire({
        imageUrl: `${getOriginUrl()}/assets/global/img/icon-${status}.png`,
        imageWidth: 100,
        imageHeight: 100,
        imageAlt: "icon-status",
        // icon: status,
        // title: status[0].toUpperCase() + status.slice(1),
        title: title,
        text: newMessage,
        timer: timer,
        showConfirmButton: status == 'success' ? false : true,
        confirmButtonText: 'Oke, saya mengerti',
        // confirmButtonTextColor: '#4A4A4A',
        // confirmButtonColor: '#8C8C8C',
        // showClass: {
        //     popup: `
        //         animate__animated
        //         animate__fadeInUp
        //         animate__faster
        //     `
        // },
        // hideClass: {
        //     popup: `
        //         animate__animated
        //         animate__fadeOutDown
        //         animate__faster
        //     `
        // },
        customClass: {
            confirmButton: confirmButtonClass,
            cancelButton: cancelButton
        },
    })
}

function showSwalConfirm(title, message, status = 'info', callback) {
    let confirmButtonColor
    let confirmButtonClass = 'btn btn-danger mt-3 me-3'
    let cancelButton = 'btn btn-secondary-light mt-3'

    switch (status) {
        case 'success':
            confirmButtonColor = '#1B8754'
            break;
        case 'info':
            confirmButtonColor = '#1F88EB'
            break;
        case 'warning':
            confirmButtonColor = '#DC3545'
            break;
        case 'error':
            confirmButtonColor = '#DC3545'
            break;
        default:
            break;
    }

    Swal.fire({
        title: title,
        imageUrl: `${getOriginUrl()}/assets/global/img/icon-${status}.png`,
        imageWidth: 100,
        imageHeight: 100,
        imageAlt: "icon-status",
        // icon: status,
        html: message,
        showCancelButton: true,
        // confirmButtonColor: confirmButtonColor,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: confirmButtonClass,
            cancelButton: cancelButton
        },
    }).then((result) => {
        callback(result.isConfirmed)
    })
}

const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
});