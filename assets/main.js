document.addEventListener("DOMContentLoaded", function () {
  // Așteaptă până când toate resursele sunt încărcate
  window.addEventListener("load", function () {
    // Ascunde preloader-ul după încărcarea completă a paginii
    var pageLoader = document.getElementById("page-loader");
    if (pageLoader) {
      pageLoader.style.display = "none";
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  var movieRuntime = document.getElementById("movieRuntime");
  var displayRuntime = document.getElementById("displayRuntime");
  var toggleRuntimeButton = document.getElementById("toggleRuntime");

  toggleRuntimeButton.addEventListener("click", function () {
    var currentRuntime = parseInt(
      movieRuntime.getAttribute("data-runtime"),
      10
    );

    if (displayRuntime.textContent.includes("minute")) {
      // Converteste minutele in ore/minute si actualizeaza textul
      var hours = Math.floor(currentRuntime / 60);
      var minutes = currentRuntime % 60;
      displayRuntime.textContent = hours + "h " + minutes + "m";
      toggleRuntimeButton.textContent = "Convert to Minutes";
    } else {
      // Afiseaza din nou varianta in minute
      displayRuntime.textContent = currentRuntime + " minutes";
      toggleRuntimeButton.textContent = "Convert to Hours";
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var visitDurationSpan = document.getElementById("visitDuration");
  var secondsSpent = 0;

  var visitTimer = window.setInterval(function () {
    secondsSpent++;
    visitDurationSpan.textContent = secondsSpent + " seconds";

    // Verifică dacă au trecut 60 de secunde (1 minut)
    if (secondsSpent === 60) {
      // Afișează un alert la trecerea a 60 de secunde
      alert(
        "Ai petrecut mai mult de 1 minut pe această pagină. Dacă nu găsești informația necesară, te rugăm să ne contactezi."
      );
    }
  }, 1000); // Interval de 1000 ms (1 secundă)
});
