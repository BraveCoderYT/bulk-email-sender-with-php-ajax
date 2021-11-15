$(document).ready(function () {
  $("#loader").hide();

  $("#addMore").on("click", function () {
    var html = `<div class="input-box"><input type="email" name="to[]" placeholder="To: (Email)" class="input" required /></div>`;

    $("#multipleFields").append(html);
  });

  $("#form").on("submit", function (e) {
    e.preventDefault();

    $("#loader").show();
    $("#form").hide();

    var formData = new FormData(this);

    $.ajax({
      url: "php/send.php",
      type: "post",
      data: formData,
      processData: false,
      contentType: false,
      success: function (status) {
        $("#form").show();
        $("#loader").hide();
        $(".input").val("");
        $("#alert").append(`<div class="alert">Message has been sent.</div>`);
      },
    });
  });
});
