// Função para consultar dados com base no código de barras
function fazerConsulta() {
    var erro = true; // Flag para controlar erros
    const codigoBarra = document.getElementById('codigoBarra').value; // Obtém o valor do campo de código de barras
    console.log(codigoBarra); // Exibe o código no console para depuração
    const action = 'fazerConsulta'; // Define a ação a ser enviada ao PHP

    // Configura uma requisição AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'consulta.php', true); // Abre uma requisição POST para consulta.php
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Define o tipo de conteúdo
    xhr.send('action=' + encodeURIComponent(action) + '&codigoBarra=' + encodeURIComponent(codigoBarra)); // Envia os dados

    xhr.onload = function () { // Função executada quando a resposta é recebida
        if (xhr.status === 200) { // Verifica se a requisição foi bem-sucedida
            var element = document.getElementById("erro-div"); // Elemento para exibir mensagens de erro
            element.textContent = "OP Digitada é invalida!" // Define a mensagem padrão de erro
            console.log(xhr.responseText); // Exibe a resposta bruta no console
            const result = JSON.parse(xhr.responseText); // Converte a resposta JSON em objeto

            if (result == "erro") { // Se o resultado for "erro"
                element.style.display = "block"; // Exibe o elemento de erro
                document.getElementById('codigoBarra').value = ""; // Limpa o campo de entrada
                setTimeout(function () { mudaErro("erro-div"); }, 2100); // Oculta o erro após 2,1 segundos
                // Reseta os campos exibidos na interface
                document.getElementById('ctrab').textContent = "*****";
                document.getElementById('nome').textContent = "";
                document.getElementById('qntdeapnt').textContent = "*****";
                document.getElementById('cc').textContent = "*****";
                document.getElementById('desc').textContent = "";
                document.getElementById('um').textContent = "*****";
                document.getElementById('quant').textContent = "*****";
                document.getElementById('codigoBarra').placeholder = "Escaneie o código de barras";
            } else { // Se não houver erro no resultado
                if (element.style.display == "block") { // Oculta o elemento de erro se visível
                    element.style.display = "none";
                }
                document.getElementById('codigoBarra').value = ""; // Limpa o campo de entrada

                // Verifica se o centro de trabalho do usuário corresponde ao da operação
                for (let i = 0; i < result.USUARIO.length; i++) {
                    if (result.USUARIO[i].ZHI_CTRAB == result.OP.ZHF_CTRAB) {
                        // Preenche os campos com os dados retornados
                        document.getElementById('ctrab').textContent = result.OP.ZHF_CTRAB; // Centro de trabalho
                        document.getElementById('nome').textContent = result.OP.HB_NOME;     // Nome do centro
                        document.getElementById('cc').textContent = result.OP.ZHF_CC;       // Centro de custo
                        document.getElementById('desc').textContent = result.OP.CTT_DESC01; // Descrição do centro de custo
                        document.getElementById('um').textContent = result.OP.ZCB_UM;       // Unidade de medida

                        if (result.ZHG_QUANT) { // Se houver quantidade apontada
                            document.getElementById('qntdeapnt').textContent = result.ZHG_QUANT; // Quantidade apontada
                            document.getElementById('quant').textContent = (result.OP.ZCB_QUANT - result.ZHG_QUANT); // Quantidade restante
                        } else { // Se não houver quantidade apontada
                            document.getElementById('qntdeapnt').textContent = 0; // Define como 0
                            document.getElementById('quant').textContent = result.OP.ZCB_QUANT; // Quantidade total
                        }
                        erro = false; // Define que não há erro
                        document.getElementById('codigoBarra').placeholder = codigoBarra; // Atualiza o placeholder
                    }
                }
                if (erro) { // Se o centro de trabalho não for válido para o usuário
                    element.textContent = "Centro de Trabalho Invalido!";
                    element.style.display = "block"; // Exibe mensagem de erro
                    document.getElementById('codigoBarra').value = ""; // Limpa o campo
                    setTimeout(function () { mudaErro("erro-div"); }, 2100); // Oculta após 2,1 segundos
                    // Reseta os campos
                    document.getElementById('ctrab').textContent = "*****";
                    document.getElementById('nome').textContent = "";
                    document.getElementById('qntdeapnt').textContent = "*****";
                    document.getElementById('cc').textContent = "*****";
                    document.getElementById('desc').textContent = "";
                    document.getElementById('um').textContent = "*****";
                    document.getElementById('quant').textContent = "*****";
                    document.getElementById('codigoBarra').placeholder = "Escaneie o código de barras";
                }
            }
        }
    };
}

// Função para realizar o apontamento
function apontar() {
    const codigoBarra = document.getElementById('codigoBarra').placeholder; // Obtém o código do placeholder
    const recurso = document.getElementById('filterInput').value; // Obtém o recurso selecionado
    const usuario = document.getElementById('apontar').getAttribute('usuario'); // Obtém o ID do usuário do botão
    const xhr = new XMLHttpRequest(); // Cria uma nova requisição AJAX
    console.log(usuario); // Exibe o usuário no console para depuração
    xhr.open('POST', 'apontar.php', true); // Abre uma requisição POST para apontar.php
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Define o tipo de conteúdo
    xhr.send('codigoBarra=' + encodeURIComponent(codigoBarra) + // Envia os dados
        '&recurso=' + encodeURIComponent(recurso) +
        '&usuario=' + encodeURIComponent(usuario));
    xhr.onload = function () { // Função executada quando a resposta é recebida
        if (xhr.status === 200) { // Verifica se a requisição foi bem-sucedida
            var result = JSON.parse(xhr.responseText); // Converte a resposta JSON em objeto

            if (result.GRVAPONTRESULT.RETORNO == false) { // Se o apontamento falhar
                console.log(result.GRVAPONTRESULT.RETORNO); // Exibe o resultado no console
                var element = document.getElementById("erro-div"); // Elemento de erro
                element.textContent = "Erro no Apontamento"; // Define a mensagem de erro
                element.style.display = "block"; // Exibe o erro
                // Reseta os campos
                document.getElementById('ctrab').textContent = "*****";
                document.getElementById('nome').textContent = "";
                document.getElementById('qntdeapnt').textContent = "*****";
                document.getElementById('cc').textContent = "*****";
                document.getElementById('desc').textContent = "";
                document.getElementById('um').textContent = "*****";
                document.getElementById('quant').textContent = "*****";
                document.getElementById('codigoBarra').placeholder = "Escaneie o código de barras";
                setTimeout(function () { mudaErro("erro-div"); }, 2100); // Oculta o erro após 2,1 segundos
            } else { // Se o apontamento for bem-sucedido
                document.getElementById('sucess-div').style.display = "Block"; // Exibe mensagem de sucesso (nota: "Block" deveria ser "block")
                setTimeout(function () { mudaErro("sucess-div"); }, 2100); // Oculta após 2,1 segundos
                // Reseta os campos
                document.getElementById('ctrab').textContent = "*****";
                document.getElementById('nome').textContent = "";
                document.getElementById('qntdeapnt').textContent = "*****";
                document.getElementById('cc').textContent = "*****";
                document.getElementById('desc').textContent = "";
                document.getElementById('um').textContent = "*****";
                document.getElementById('quant').textContent = "*****";
                document.getElementById('codigoBarra').placeholder = "Escaneie o código de barras";
            }
        }
    }
}

// Função para ocultar a lista de apontamentos
function sairLista() {
    document.getElementById('container-lista').style.display = "none"; // Define o display como none
};

// Função para listar apontamentos
function listarApontamentos(event) {
    var action = "listarApontamentos"; // Define a ação para o PHP
    if (event == 'listarapontamentos') { // Se o evento for o clique inicial
        // Calcula a data de 2 dias atrás
        let hoje = new Date();
        let dataInicio = new Date(hoje);
        dataInicio.setDate(hoje.getDate() - 2); // Subtrai 2 dias

        // Formata a data inicial no formato YYYYMMDD
        var dataini = dataInicio.toISOString().slice(0, 10).replace(/-/g, ''); // Ex: '20250319'
        // A data final é o dia atual
        var datafim = hoje.toISOString().slice(0, 10).replace(/-/g, ''); // Ex: '20250321'

        // Exibe as datas no console para depuração
        console.log('Data Inicial:', dataini);
        console.log('Data Final:', datafim);

        var datainiformatada = dataInicio.toISOString().slice(0, 10); // Ex: '2025-03-19'
        var datafimformatada = hoje.toISOString().slice(0, 10); // Ex: '2025-03-21'

        // Exibe o container da lista
        document.getElementById('container-lista').style.display = "flex";
        // Atualiza os campos de data no formulário de pesquisa
        document.getElementById('inicio').value = datainiformatada;
        document.getElementById('fim').value = datafimformatada;
    } else if (event == 'pesquisa') { // Se o evento for uma pesquisa manual
        var inicio = document.getElementById('inicio').value; // Obtém a data inicial do campo
        var fim = document.getElementById('fim').value; // Obtém a data final do campo
        inicio = inicio.slice(0, 10).replace(/-/g, ''); // Formata como YYYYMMDD
        fim = fim.slice(0, 10).replace(/-/g, ''); // Formata como YYYYMMDD
        console.log(inicio); // Exibe no console
        console.log(fim); // Exibe no console
        dataini = inicio; // Define a data inicial
        datafim = fim; // Define a data final
    }
    // Configura e envia a requisição AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'consulta.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=' + encodeURIComponent(action) +
        '&dataini=' + encodeURIComponent(dataini) +
        '&datafim=' + encodeURIComponent(datafim)
    );

    xhr.onload = function () { // Função executada quando a resposta é recebida
        if (xhr.status === 200) { // Verifica se a requisição foi bem-sucedida
            var result = JSON.parse(xhr.responseText); // Converte a resposta JSON em objeto
            var tbody = document.querySelector("#lista-apontamentos tbody"); // Seleciona o corpo da tabela
            tbody.innerHTML = ''; // Limpa a tabela antes de preenchê-la
            result.reverse(); // Inverte a ordem dos resultados (mais recente primeiro)
            result.forEach(function (item) { // Itera sobre os itens retornados
                // Cria uma linha da tabela com os dados formatados
                var row = `<tr>
                    <td data-label="DATA">${item.ZHG_DATA.slice(6, 8)}/${item.ZHG_DATA.slice(4, 6)}/${item.ZHG_DATA.slice(0, 4)}</td> <!-- Formata data como DD/MM/YYYY -->
                    <td data-label="HORA">${item.ZHG_HORA}</td>
                    <td data-label="NOME">${item.ZHG_NOME}</td>
                    <td data-label="CENTRO DE TRABALHO">${item.ZHG_NOMECT}</td>
                    <td data-label="RECURSO">${item.ZHG_RECURS}</td>
                    <td data-label="LOTE">${item.ZHG_LOTE}</td>
                    <td data-label="NUMERO">${item.ZHG_NUM}</td>
                    <td data-label="ITEM">${item.ZHG_ITEM}</td>
                    <td data-label="SEQUENCIA">${item.ZHG_SEQUEN}</td>
                    <td data-label="PARTE">${item.ZHG_PARTE}</td>
                </tr>`;
                tbody.innerHTML += row; // Adiciona a linha à tabela
            });
        } else {
            console.error("Erro na resposta: " + xhr.status); // Exibe erro no console se a requisição falhar
        }
    }
}

// Função para alternar a visibilidade do dropdown
function toggleDropdown() {
    var dropdown = document.getElementById("dropdownOptions"); // Seleciona o dropdown
    if (dropdown.style.display === "none" || dropdown.style.display === "") { // Se estiver oculto ou sem estilo
        dropdown.style.display = "block"; // Exibe o dropdown
    } else {
        dropdown.style.display = "none"; // Oculta o dropdown
    }
}

// Função para selecionar uma opção do dropdown
function selectOption(element) {
    document.getElementById("filterInput").value = element.getAttribute("data-value"); // Define o valor no campo de entrada
    document.getElementById("nomeRecurso").textContent = element.getAttribute("data-descri"); // Exibe a descrição
    document.getElementById("dropdownOptions").style.display = "none"; // Oculta o dropdown
}

// Função para filtrar as opções do dropdown
function filterFunction() {
    var input, filter, select, options, i;
    input = document.getElementById("filterInput"); // Campo de entrada
    filter = input.value.toUpperCase(); // Valor digitado em maiúsculas
    select = document.getElementById("dropdownOptions"); // Container do dropdown
    options = select.getElementsByClassName("option"); // Lista de opções
    for (i = 0; i < options.length; i++) { // Itera sobre as opções
        txtValue = options[i].textContent || options[i].innerText; // Obtém o texto da opção
        if (txtValue.toUpperCase().indexOf(filter) > -1) { // Verifica se o filtro está presente
            options[i].style.display = ""; // Exibe a opção
        } else {
            options[i].style.display = "none"; // Oculta a opção
        }
    }
}

// Fecha o dropdown ao clicar fora dele
window.onclick = function (event) {
    if (!event.target.matches('#filterInput')) { // Se o clique não for no campo de entrada
        var dropdown = document.getElementById("dropdownOptions");
        if (dropdown.style.display === "block") { // Se o dropdown estiver visível
            dropdown.style.display = "none"; // Oculta o dropdown
        }
    }
}

// Função para alternar a visibilidade de elementos de erro/sucesso
function mudaErro(id) {
    var element = document.getElementById(id); // Seleciona o elemento pelo ID
    if (element.style.display == "none" || element.style.display == "") { // Se estiver oculto ou sem estilo
        element.style.display = "block"; // Exibe o elemento
    } else {
        element.style.display = "none"; // Oculta o elemento
    }
}

// Define a variável CSS --vh ao carregar a página
window.addEventListener('load', () => {
    const vh = window.innerHeight * 0.01; // Calcula 1% da altura da viewport
    document.documentElement.style.setProperty('--vh', `${vh}px`); // Define a variável CSS
});

// Atualiza a variável --vh ao redimensionar a janela
window.addEventListener('resize', () => {
    let vh = window.innerHeight * 0.01; // Recalcula 1% da altura da viewport
    document.documentElement.style.setProperty('--vh', `${vh}px`); // Atualiza a variável CSS
});