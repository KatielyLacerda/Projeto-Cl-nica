document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("medico-form");
  const primeiroNomeInput = document.getElementById("primeiroNome");
  const ultimoNomeInput = document.getElementById("ultimoNome");
  const crmInput = document.getElementById("crm");
  const telefoneInput = document.getElementById("telefone");
  const especialidadeInput = document.getElementById("especialidade");

  //função pra mascara XXX-XX
  crmInput.addEventListener("input", function () {
    let value = crmInput.value.replace(/\D/g, "");
    if (value.length > 5) {
      value = value.slice(0, 5);
    }
    crmInput.value = value.replace(/(\d{3})(\d{2})/, "$1-$2");
  });

  //função pra mascara (XX) XXXXX-XXXX
  telefoneInput.addEventListener("input", function () {
    let value = telefoneInput.value.replace(/\D/g, "");
    if (value.length > 11) {
      value = value.slice(0, 11);
    }
    telefoneInput.value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
  });

  // Validar especialidades
  form.addEventListener("submit", function (event) {
    const especialidades = especialidadeInput.value
      .split(",")
      .map((e) => e.trim());

    if (especialidades.length < 2) {
      alert(
        "Por favor, insira pelo menos duas especialidades separadas por vírgula."
      );
      event.preventDefault();
    } else {
      const especialidadesValidas = [
        "ALERGOLOGIA",
        "ANGIOLOGIA",
        "BUCO MAXILO",
        "CARDIOLOGIA CLÍNICA",
        "CARDIOLOGIA INFANTIL",
        "CIRURGIA CABEÇA E PESCOÇO",
        "CIRURGIA CARDÍACA",
        "CIRURGIA DE CABEÇA/PESSOÇO",
        "CIRURGIA DE TORAX",
        "CIRURGIA GERAL",
        "CIRURGIA PEDIÁTRICA",
        "CIRURGIA PLÁSTICA",
        "CIRURGIA TORÁCICA",
        "CIRURGIA VASCULAR",
        "CIRURGIA MÉDICA",
      ];

      const invalidEspecialidades = especialidades.filter(
        (e) => !especialidadesValidas.includes(e.toUpperCase())
      );

      if (invalidEspecialidades.length > 0) {
        alert("Por favor, insira especialidades válidas.");
        event.preventDefault();
      }
    }
  });

  //validação de nome
  form.addEventListener("submit", function (event) {
    const primeiroNomeValue = primeiroNomeInput.value.trim();
    const ultimoNomeValue = ultimoNomeInput.value.trim();
    if (!primeiroNomeValue || !ultimoNomeValue) {
      alert(
        "Por favor, insira tanto o primeiro nome quanto o último sobrenome."
      );
      event.preventDefault();
    }
  });

  // Submissão do formulário e integração com a API
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    // Verificar se há mensagens de alerta pendentes
    if (document.querySelector(".alert")) {
      return;
    }

    const formData = {
      primeiroNome: primeiroNomeInput.value.trim(),
      ultimoNome: ultimoNomeInput.value.trim(),
      crm: crmInput.value.trim(),
      especialidade: especialidadeInput.value.trim(),
      telefone: telefoneInput.value.trim(),
    };

    fetch("api/create_medico.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("BATATA: ", data);
        if (data.message) {
          alert(data.message);
          form.reset(); // Limpa o formulário após o envio bem-sucedido
        } else {
          alert("Erro ao cadastrar médico.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Ocorreu um erro ao tentar cadastrar o médico.");
      });
  });
});
