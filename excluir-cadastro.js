document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("alterar-form");
  const primeiroNomeInput = document.getElementById("primeiroNome");
  const ultimoNomeInput = document.getElementById("ultimoNome");
  const crmInput = document.getElementById("crm");

  // Máscara para CRM
  crmInput.addEventListener("input", function () {
    let value = crmInput.value.replace(/\D/g, "");
    if (value.length > 5) {
      value = value.slice(0, 5);
    }
    crmInput.value = value.replace(/(\d{3})(\d{2})/, "$1-$2");
  });

  // Validação de nome
  form.addEventListener("submit", function (event) {
    const primeiroNomeValue = primeiroNomeInput.value.trim();
    const ultimoNomeValue = ultimoNomeInput.value.trim();

    if (!primeiroNomeValue || !ultimoNomeValue) {
      alert(
        "Por favor, insira tanto o primeiro nome quanto o último sobrenome."
      );
      event.preventDefault();
    } else {
      // Enviar solicitação de exclusão via API
      const formData = {
        primeiroNome: primeiroNomeValue,
        ultimoNome: ultimoNomeValue,
        crm: crmInput.value.replace(/\D/g, ""),
      };

      fetch("http://localhost/api/delete_medico.php", {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.message) {
            alert(data.message);
            form.reset(); // Limpa o formulário após o envio bem-sucedido
          } else {
            alert("Erro ao excluir cadastro.");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Ocorreu um erro ao tentar excluir o cadastro.");
        });

      // Impedir o envio padrão do formulário
      event.preventDefault();
    }
  });
});
