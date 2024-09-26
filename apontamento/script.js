function fazerConsulta() {
    const codigoBarra = document.getElementById('codigoBarra').value;

    // AJAX para o PHP
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'consulta.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('codigoBarra=' + encodeURIComponent(codigoBarra));
    xhr.onload = function () {
        if (xhr.status === 200) {
            var element = document.getElementById("erro-div");
            const result = JSON.parse(xhr.responseText);
            if (result == "erro") {
                element.style.display = "block";
                setTimeout(mudaErro, 2100);
            }
            else {
                if(element.style.display == "block"){
                    element.style.display = "none";
                }
                document.getElementById('ctrab').textContent = result.ZHF_CTRAB;
                document.getElementById('nome').textContent = result.HB_NOME;
                document.getElementById('cc').textContent = result.ZHF_CC;
                document.getElementById('desc').textContent = result.CTT_DESC01;
                document.getElementById('um').textContent = result.ZCB_UM;
                document.getElementById('quant').textContent = result.ZCB_QUANT;
            }
        }
    };
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

    // Loop para exibir apenas os itens que correspondem ao filtro
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
function mudaErro() {
    var element = document.getElementById("erro-div");
    if (element.style.display == "none" || element.style.display == "") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}
