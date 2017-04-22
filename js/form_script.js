$('document').ready(function () {

    // var uploadedFile = $("#file"), buttonClearUploaded = $("#buttonClearUploaded"), buttonUpload = $('#buttonUpload').find('span');






    //                                                                                              DataPickers
    $('[name = "date"]').datepicker({
        dateFormat: 'dd/mm/yy ', showAnim: 'show', defaultDate: '-1y', changeYear: true
    });

    $('[name = "date1"]').datepicker({
        dateFormat: 'dd/mm/yy ', showAnim: 'show', defaultDate: '-0d', changeYear: true
    });

    $('[name = "denial"]').datepicker({
        dateFormat: 'dd/mm/yy ', showAnim: 'show', defaultDate: '+1m', changeYear: true
    });


    //                                                                                     Buttons Clicks
    // $("#right-button").on("click", function () {
    //     $('#page1').fadeToggle(1000);
    //     $('#page2').fadeToggle(2000);
    // });
    //
    // $("#left-button").on("click", function () {
    //
    //     $('#page2').fadeToggle(1000);
    //     $('#page1').fadeToggle(2000);
    //
    // });

    // $("#mail-button").on("click", function () {
    //
    //     $.post("form_submit.php?button=mail", {
    //             firstName: $("#firstName").val(),
    //             lastName: $("#lastName").val(),
    //             sex: $('input:radio[name=sex]:checked').val(),
    //             birthdate: $("#birthdate").val(),
    //             optionallyInformation: $("#optionallyInformation").val(),
    //             emailAddress: $("#emailAddress").val()
    //         },
    //
    //         function (mailResult) {
    //             $('#mailResult').append(mailResult);
    //         });
    //
    //     $('#page2').fadeToggle(1000);
    //     $('#page3').fadeToggle(2000);
    // });

    // $("#blank-button").on("click", function () {
    //
    //     $("#firstName").val("");
    //     $("#lastName").val("");
    //     $('input:radio[name=sex]').prop('checked', false);
    //     $("#birthdate").val("");
    //     $("#optionallyInformation").val("");
    //     $("#emailAddress").val("");
    //     buttonClearUploaded.click();
    //     $('#mailResult').text('');
    //
    //     $('#page3').fadeToggle(1000);
    //     $('#page1').fadeToggle(2000);
    //
    // });

    // $("#close-button").on("click", function () {
    //
    //     location.assign("../../index.html");
    //     buttonClearUploaded.click();
    // });

});





