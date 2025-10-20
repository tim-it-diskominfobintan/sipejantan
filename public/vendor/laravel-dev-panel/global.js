function getQueryString($query = null) {
    var url = document.location.href
    var qs = url.substring(url.indexOf('?') + 1).split('&')
    var result
    for (var i = 0, result = {}; i < qs.length; i++) {
        qs[i] = qs[i].split('=')
        result[qs[i][0]] = decodeURIComponent(qs[i][1])
    }
    return result
}

function tanggalIndo(tanggal) {
    const bulan = [
        "",
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    let bulanpecah = tanggal.split("-");
    return (
        bulanpecah[2] + " " + bulan[parseInt(bulanpecah[1])] + " " + bulanpecah[0]
    );
}

function getBulan(month, short = false) {
    const bulan = [
        "",
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    const bulanShort = [
        "",
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des",
    ];

    return short ? bulanShort[month] : bulan[month]
}


function validationMessageRender(response, prefix = '', suffix = '') {
    for (let key in response) {
        let firstError = response[key][0]

        $(`#${prefix != '' ? prefix + '-' : ''}` + key).focus();
        $(`#${prefix != '' ? prefix + '-' : ''}` + key).removeClass('is-valid border-success');
        $(`#${prefix != '' ? prefix + '-' : ''}` + key).addClass('is-invalid border-danger');
        $(`#${prefix != '' ? prefix + '-' : ''}` + key + `${suffix != '	' ? '-' + suffix : ''}`).html(`<small class="text-danger">${firstError}</small>`);
    }
}

function clearValidationMessage(prefix = '', suffix = '') {
    $(`*input[id*=${prefix}]`).each(function () {
        $(this).removeClass('is-invalid')
        $(this).removeClass('is-valid')

        $(this).removeClass('border-danger')
        $(this).removeClass('border-success')
    })

    $(`*select[id*=${prefix}]`).each(function () {
        $(this).removeClass('is-invalid')
        $(this).removeClass('is-valid')

        $(this).removeClass('border-danger')
        $(this).removeClass('border-success')
    })

    $(`*textarea[id*=${prefix}]`).each(function () {
        $(this).removeClass('is-invalid')
        $(this).removeClass('is-valid')

        $(this).removeClass('border-danger')
        $(this).removeClass('border-success')
    })

    $(`*div[id^=${prefix}]`).each(function () {
        $(this).html('')
    })
}

function previewImageFromInput(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()
        reader.onload = function (e) {
            $(previewId).attr('src', e.target.result)
            $(previewId).hide()
            $(previewId).fadeIn(650)
        }
        reader.readAsDataURL(input.files[0])
    }
}

function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr("content")
}

function previewImageFromInput(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(previewId).attr('src', e.target.result);
            $(previewId).hide();
            $(previewId).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// ignore click
// $(".ignore-click").click(function () {
// 	return false;
// })

// String.prototype.trimEllip = function (length, charSubtitution) {
// 	return this.length > length ? this.substring(0, length) + charSubtitution : this;
// }

function trimEllip(string, maxlength, charSubtitution) {
    return string.length > maxlength ? string.substring(0, maxlength) + charSubtitution : string;
}

function getEnv() {
    // cara gemini
    return window.location.host === 'bintankab.go.id' ? 'production' : 'development';

    // cara dimas
    // return getCanonicalHost(window.location.host) == 'bintankab.go.id' ? 'production' : 'development'
}

function getCanonicalHost(hostname) {
    const MAX_TLD_LENGTH = 3;

    function isNotTLD(_) { return _.length > MAX_TLD_LENGTH; };

    hostname = hostname.split('.');
    hostname = hostname.slice(Math.max(0, hostname.findLastIndex(isNotTLD)));
    hostname = hostname.join('.');

    return hostname;
}

function getCurrentUrl() {
    return window.location.protocol + "//" + window.location.host + "/" + window.location.pathname
}

function getOriginUrl() {
    return window.location.protocol + "//" + window.location.host
}

function getUriSegment(segment) {
    // let pathArray = window.location.pathname.split("/");

    // // Hilangkan elemen pertama jika pathname dimulai dengan '/' (yang selalu terjadi)
    // if (pathArray[0] === "") {
    //     pathArray.shift();
    // }

    // let index = segment - 1; // Karena array dimulai dari 0, dan segment biasanya dimulai dari 1

    // if (getEnv() === 'development') {
    //     index++; // Offset untuk lingkungan pengembangan (asumsi ada 1 segmen tambahan)
    // }

    // if (pathArray[index] === undefined || pathArray[index] === '') {
    //     return '';
    // }

    // return pathArray[index];


    let pathArray = window.location.pathname.split("/")

    if (pathArray[segment] == undefined || pathArray[segment] == '') {
        return ''
    }


    if (getEnv() == 'production') {
        return pathArray[segment]
    } else {
        return pathArray[segment]
        // return pathArray[segment + 1]
    }
}

function randomString(length = 8) {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var stringLength = length;
    var randomstring = '';
    for (var i = 0; i < stringLength; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum, rnum + 1);
    }
    return randomstring;
}

function formatTimeHumanRead(tanggalInput) {
    const now = new Date();
    const inputDate = new Date(tanggalInput);
    const selisihMs = inputDate - now;

    const satuan = [
        { nama: "tahun", ms: 1000 * 60 * 60 * 24 * 365 },
        { nama: "bulan", ms: 1000 * 60 * 60 * 24 * 30 },
        { nama: "hari", ms: 1000 * 60 * 60 * 24 },
        { nama: "jam", ms: 1000 * 60 * 60 },
        { nama: "menit", ms: 1000 * 60 },
        { nama: "detik", ms: 1000 }
    ];

    const absSelisih = Math.abs(selisihMs);

    for (const { nama, ms } of satuan) {
        const nilai = Math.floor(absSelisih / ms);
        if (nilai > 0) {
            return selisihMs > 0
                ? `dalam ${nilai} ${nama}`
                : `${nilai} ${nama} yang lalu`;
        }
    }

    return "baru saja";
}

function initializeSelect2(selector, selectorModal = null) {
	if (selectorModal) {
		$(selector).select2({
			width: '100%',
            theme: 'bootstrap-5',
			dropdownParent: $(selectorModal) // Pastikan ini menunjuk ke modal Anda
		});
	} else {
		$(selector).select2({
            theme: 'bootstrap-5',
			width: '100%'
		});
	}
}

function testcallFunctionFromSamePackage() {
    alert('Hello from package laravel-dev-panel')
}