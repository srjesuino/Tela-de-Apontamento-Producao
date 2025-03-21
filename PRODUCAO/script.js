function fazerConsulta() {
    var erro = true;
    const codigoBarra = document.getElementById('codigoBarra').value;
    console.log(codigoBarra);
    const action = 'fazerConsulta'; // Definir o valor do action
    // AJAX para o PHP
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'consulta.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=' + encodeURIComponent(action) + '&codigoBarra=' + encodeURIComponent(codigoBarra));
    xhr.onload = function () {
        if (xhr.status === 200) {
            var element = document.getElementById("erro-div");
            element.textContent = "OP Digitada é invalida!"
            console.log(xhr.responseText);
            const result = JSON.parse(xhr.responseText);
            if (result == "erro") {
                element.style.display = "block";
                document.getElementById('codigoBarra').value = "";
                setTimeout(function () { mudaErro("erro-div"); }, 2100);
                document.getElementById('ctrab').textContent = "*****";
                document.getElementById('nome').textContent = "";
                document.getElementById('qntdeapnt').textContent = "*****";
                document.getElementById('cc').textContent = "*****";
                document.getElementById('desc').textContent = "";
                document.getElementById('um').textContent = "*****";
                document.getElementById('quant').textContent = "*****";
                document.getElementById('codigoBarra').placeholder = "Escaneie o código de barras";
            }
            else {
                if (element.style.display == "block") {
                    element.style.display = "none";
                }
                document.getElementById('codigoBarra').value = "";

                for (let i = 0; i < result.USUARIO.length; i++) {
                    if (result.USUARIO[i].ZHI_CTRAB == result.OP.ZHF_CTRAB) {
                        document.getElementById('ctrab').textContent = result.OP.ZHF_CTRAB;
                        document.getElementById('nome').textContent = result.OP.HB_NOME;
                        document.getElementById('cc').textContent = result.OP.ZHF_CC;
                        document.getElementById('desc').textContent = result.OP.CTT_DESC01;
                        document.getElementById('um').textContent = result.OP.ZCB_UM;

                        if (result.ZHG_QUANT) {
                            document.getElementById('qntdeapnt').textContent = result.ZHG_QUANT;
                            document.getElementById('quant').textContent = (result.OP.ZCB_QUANT - result.ZHG_QUANT);
                        }
                        else {
                            document.getElementById('qntdeapnt').textContent = 0
                            document.getElementById('quant').textContent = result.OP.ZCB_QUANT;
                        }
                        erro = false;
                        document.getElementById('codigoBarra').placeholder = codigoBarra;
                    }
                }
                if (erro) {
                    element.textContent = "Centro de Trabalho Invalido!"
                    element.style.display = "block";
                    document.getElementById('codigoBarra').value = "";
                    setTimeout(function () { mudaErro("erro-div"); }, 2100);
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

function apontar() {
    const codigoBarra = document.getElementById('codigoBarra').placeholder;
    const recurso = document.getElementById('filterInput').value;
    const usuario = document.getElementById('apontar').getAttribute('usuario');
    const xhr = new XMLHttpRequest();
    console.log(usuario);
    xhr.open('POST', 'apontar.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('codigoBarra=' + encodeURIComponent(codigoBarra) +
        '&recurso=' + encodeURIComponent(recurso) +
        '&usuario=' + encodeURIComponent(usuario));
    xhr.onload = function () {
        if (xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);

            if (result.GRVAPONTRESULT.RETORNO == false) {
                console.log(result.GRVAPONTRESULT.RETORNO);
                var element = document.getElementById("erro-div");
                element.textContent = "Erro no Apontamento";
                element.style.display = "block";
                document.getElementById('ctrab').textContent = "*****";
                document.getElementById('nome').textContent = "";
                document.getElementById('qntdeapnt').textContent = "*****";
                document.getElementById('cc').textContent = "*****";
                document.getElementById('desc').textContent = "";
                document.getElementById('um').textContent = "*****";
                document.getElementById('quant').textContent = "*****";
                document.getElementById('codigoBarra').placeholder = "Escaneie o código de barras";
                setTimeout(function () { mudaErro("erro-div"); }, 2100);

            }
            else {
                document.getElementById('sucess-div').style.display = "Block"
                setTimeout(function () { mudaErro("sucess-div"); }, 2100);
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
function sairLista(){
    document.getElementById('container-lista').style.display = "none";
};
function listarApontamentos(event) {
    var action = "listarApontamentos";
    if (event == 'listarapontamentos') {
        // Calcula a data de 30 dias atrás
        let hoje = new Date();
        let dataInicio = new Date(hoje);
        dataInicio.setDate(hoje.getDate() - 2);  // Subtrai 30 dias

        // Formata a data inicial no formato YYYYMMDD
        var dataini = dataInicio.toISOString().slice(0, 10).replace(/-/g, ''); // Ex: '20230930'

        // A data final é o dia atual
        var datafim = hoje.toISOString().slice(0, 10).replace(/-/g, ''); // Ex: '20231030'

        // Exibe as datas no console para verificação
        console.log('Data Inicial:', dataini);
        console.log('Data Final:', datafim);

        var datainiformatada = dataInicio.toISOString().slice(0, 10); // Ex: '2023-09-30'
        var datafimformatada = hoje.toISOString().slice(0, 10);  

        // Mostra o container da lista
        document.getElementById('container-lista').style.display = "flex";
        //atualiza o calendario de pesquisa com o valor dos ultimos 30 dias.
        document.getElementById('inicio').value = datainiformatada;
        document.getElementById('fim').value = datafimformatada;
    }
    else if(event == 'pesquisa'){
        var inicio = document.getElementById('inicio').value;
        var fim = document.getElementById('fim').value;
        inicio = inicio.slice(0,10).replace(/-/g, '');
        fim = fim.slice(0,10).replace(/-/g, '');
        console.log(inicio);
        console.log(fim);
        dataini = inicio;
        datafim = fim;
    }
    // Envia a solicitação AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'consulta.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=' + encodeURIComponent(action) +
        '&dataini=' + encodeURIComponent(dataini) +
        '&datafim=' + encodeURIComponent(datafim)
    );

    xhr.onload = function () {
        if (xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            var tbody = document.querySelector("#lista-apontamentos tbody");
            tbody.innerHTML = ''; // Limpa a tabela antes de preencher
            result.reverse();
            result.forEach(function (item) {
                var row = `<tr>
                    <td data-label="DATA">${item.ZHG_DATA.slice(6, 8)}/${item.ZHG_DATA.slice(4, 6)}/${item.ZHG_DATA.slice(0, 4)}</td>
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
                tbody.innerHTML += row;
            });
        } else {
            console.error("Erro na resposta: " + xhr.status);
        }
    }
}



function toggleDropdown() {
    var dropdown = document.getElementById("dropdownOptions");
    if (dropdown.style.display === "none" || dropdown.style.display === "") {
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
    }
}

function selectOption(element) {
    document.getElementById("filterInput").value = element.getAttribute("data-value");
    document.getElementById("nomeRecurso").textContent = element.getAttribute("data-descri");
    document.getElementById("dropdownOptions").style.display = "none";
}

function filterFunction() {
    var input, filter, select, options, i;
    input = document.getElementById("filterInput");
    filter = input.value.toUpperCase();
    select = document.getElementById("dropdownOptions");
    options = select.getElementsByClassName("option");
    for (i = 0; i < options.length; i++) {
        txtValue = options[i].textContent || options[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}

window.onclick = function (event) {
    if (!event.target.matches('#filterInput')) {
        var dropdown = document.getElementById("dropdownOptions");
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        }
    }
}
function mudaErro(id) {
    var element = document.getElementById(id);
    if (element.style.display == "none" || element.style.display == "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}
window.addEventListener('load', () => {
    // Calcula 1% da altura da viewport
    const vh = window.innerHeight * 0.01;
    // Define o valor para a variável --vh
    document.documentElement.style.setProperty('--vh', `${vh}px`);
});

// Escuta o evento resize
window.addEventListener('resize', () => {
    // Executa o mesmo script de antes
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
});
