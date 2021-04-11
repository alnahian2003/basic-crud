document.addEventListener("DOMContentLoaded", function () {
  var deleteLinks = document.querySelectorAll(".delete");

  for (let i = 0; i < deleteLinks.length; i++) {
    deleteLinks[i].addEventListener("click", function (e) {
      if (!confirm("Are You Sure?")) {
        e.preventDefault();
      }
    });
  }
});
