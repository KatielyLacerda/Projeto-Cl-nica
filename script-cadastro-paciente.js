document.addEventListener("DOMContentLoaded", function () {
  const cpfInput = document.getElementById("cpf");
  const telefoneInput = document.getElementById("telefone");
  const examesContainer = document.getElementById("exames-container");
  const adicionarExameBtn = document.getElementById("adicionar-exame");
  const form = document.getElementById("paciente-form");

  // Máscara para CPF
  cpfInput.addEventListener("input", function () {
    let value = cpfInput.value.replace(/\D/g, "");
    if (value.length > 11) {
      value = value.slice(0, 11);
    }
    cpfInput.value = value.replace(
      /(\d{3})(\d{3})(\d{3})(\d{2})/,
      "$1.$2.$3-$4"
    );
  });

  // Máscara para telefone
  telefoneInput.addEventListener("input", function () {
    let value = telefoneInput.value.replace(/\D/g, "");
    if (value.length > 11) {
      value = value.slice(0, 11);
    }
    telefoneInput.value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
  });

  // Máscara para data de exame (DD/MM/AAAA)
  function aplicarMascaraData(input) {
    input.addEventListener("input", function () {
      let value = input.value.replace(/\D/g, "");
      if (value.length > 8) {
        value = value.slice(0, 8);
      }
      input.value = value.replace(/(\d{2})(\d{2})(\d{4})/, "$1/$2/$3");
    });
  }
  document.querySelectorAll(".data-exame").forEach(aplicarMascaraData);

  // Preencher automaticamente os campos de endereço usando o CEP
  const cepInput = document.getElementById("cep");
  cepInput.addEventListener("blur", function () {
    const cep = cepInput.value.replace(/\D/g, "");
    if (cep.length !== 8) return;

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then((response) => response.json())
      .then((data) => {
        if (!data.erro) {
          document.getElementById("endereco").value = data.logradouro;
          document.getElementById("cidade").value = data.localidade;
          document.getElementById("estado").value = data.uf;
        } else {
          alert("CEP não encontrado.");
        }
      })
      .catch((error) => console.error("Erro ao buscar CEP:", error));
  });

  // Adicionar campos de exame dinamicamente
  adicionarExameBtn.addEventListener("click", function () {
    const novoExame = document.createElement("div");
    novoExame.classList.add("exame");
    novoExame.innerHTML = `
      <label for="nomeExame">Nome do Exame:</label>
      <input type="text" class="nome-exame" name="nomeExame[]" required />
      <label for="codigoExame">Código do Exame:</label>
      <input type="text" class="codigo-exame" name="codigoExame[]" required />
      <label for="dataExame">Data do Exame:</label>
      <input type="text" class="data-exame" name="dataExame[]" required />
    `;
    examesContainer.appendChild(novoExame);
    aplicarMascaraData(novoExame.querySelector(".data-exame"));
  });

  // Validação do CPF
  function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, "");
    if (cpf.length !== 11) return false;
    let soma = 0;
    let resto;
    for (let i = 1; i <= 9; i++) {
      soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for (let i = 1; i <= 10; i++) {
      soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;
    return true;
  }

  // Validação do formulário
  form.addEventListener("submit", function (event) {
    const cpfValue = cpfInput.value.trim();
    if (!validarCPF(cpfValue)) {
      alert("Por favor, insira um número de CPF válido.");
      event.preventDefault();
    }
  });

  // Submissão do formulário e integração com a API
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    // Coletar dados do formulário
    const formData = {
      primeiroNome: document.getElementById("primeiroNome").value.trim(),
      ultimoNome: document.getElementById("ultimoNome").value.trim(),
      cpf: cpfInput.value.trim(),
      cep: document.getElementById("cep").value.trim(),
      endereco: document.getElementById("endereco").value.trim(),
      numero: document.getElementById("numero").value.trim(),
      cidade: document.getElementById("cidade").value.trim(),
      estado: document.getElementById("estado").value.trim(),
      telefone: telefoneInput.value.trim(),
      exames: [],
    };

    // Coletar dados dos exames
    const exames = document.querySelectorAll(".exame");
    exames.forEach((exame) => {
      const nomeExame = exame.querySelector(".nome-exame").value.trim();
      const codigoExame = exame.querySelector(".codigo-exame").value.trim();
      const dataExame = exame.querySelector(".data-exame").value.trim();
      formData.exames.push({ nomeExame, codigoExame, dataExame });
    });

    // Enviar dados para o servidor via API
    fetch("api//create_paciente.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erro ao cadastrar paciente.");
        }
        return response.json();
      })
      .then((data) => {
        alert("Paciente cadastrado com sucesso!");
        // Limpar o formulário após o cadastro
        form.reset();
        examesContainer.innerHTML = ""; // Limpar os campos de exame
      })
      .catch((error) => {
        console.error("Erro ao cadastrar paciente:", error);
        alert(
          "Erro ao cadastrar paciente. Por favor, tente novamente mais tarde."
        );
      });
  });
});
