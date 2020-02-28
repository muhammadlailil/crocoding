$(function () {
    $('.sumo_search').SumoSelect({search: true, searchText: 'Search....'});
    $('.sumo').SumoSelect();
})

$("table #checkall").click(function () {
    var is_checked = $(this).is(":checked");
    $("table .checkbox").prop("checked", !is_checked).trigger("click");
});
function hapusData(url){
    swal({
        title: 'Delete !',
        text: 'Apakah anda yakin akan menghapus data ini?',
        showCancelButton:true,
        allowOutsideClick:true,
        closeOnConfirm: false,
        confirmButtonColor: '#009be0',
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Batal',
        type: '',
        html: true
    }, function(){

        location.href=url;

    });
}


$(function () {
    $('.preload').fadeOut(300);
    $(".fadeModal").modal({
        fadeDuration: 100
    });
    $('.image-popup').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });
});
// function deleteSelected() {
//     swal({
//         title: 'Konfirmasi',
//         text: 'Apakah anda yakin untuk Hapus terpilih ?',
//         showCancelButton:true,
//         allowOutsideClick:true,
//         closeOnConfirm: false,
//         confirmButtonColor: '#009be0',
//         confirmButtonText: 'Yakin',
//         cancelButtonText: 'Batal',
//         type: '',
//         html: true
//     }, function(){
//         $('.formtable').submit();
//     });
// }


function Logout(url) {
    swal({
        title: 'Logout',
        text: 'Do you want to logout ?',
        showCancelButton:true,
        allowOutsideClick:true,
        closeOnConfirm: false,
        confirmButtonColor: '#009be0',
        confirmButtonText: 'Yes!',
        cancelButtonText: 'No',
        type: '',
        html: true
    }, function(){
        window.location.href = url
    });
}



jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('.treeview').each(function() {
    var active = $(this).find('.active').length;
    if(active) {
        $(this).addClass('active');
    }
})


$('input[type=text]').first().not(".notfocus").focus();

if($(".datepicker").length > 0) {
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: '1900-01-01',
        format:'YYYY-MM-DD'
    })
}

if($(".datetimepicker").length > 0) {
    $(".datetimepicker").daterangepicker({
        minDate: '1900-01-01',
        singleDatePicker: true,
        showDropdowns: true,
        timePicker:true,
        timePicker12Hour: false,
        timePickerIncrement: 5,
        timePickerSeconds: true,
        autoApply: true,
        format:'YYYY-MM-DD HH:mm:ss'
    })
}

//Timepicker
if($(".timepicker").length > 0) {
    $(".timepicker").timepicker({
        showInputs: true,
        showSeconds: true,
        showMeridian:false
    });
}
