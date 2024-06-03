document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("editar-form")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      var formData = new FormData(this);

      var xhr = new XMLHttpRequest();
      xhr.open("POST", "api/update.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            // Manipular a resposta do servidor
            document.getElementById("resultado").innerHTML = xhr.responseText;
          } else {
            // Lidar com erros
            console.error("Erro: " + xhr.status);
          }
        }
      };
      xhr.send(formData);
    });
});
