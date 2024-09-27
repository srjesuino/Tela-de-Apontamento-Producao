var previousText ; 
function expandeAba(id1, id2) {
    var aba = document.getElementById(id1);
    var selecao = document.getElementById(id2);
    var option = document.getElementsByClassName('option');
    if(selecao.textContent != "VOLTAR"){
        previousText = selecao.innerText;}
    if (aba.style.display === "none" || aba.style.display === "") {
        for (var i = 0;i < option.length; i++){
            if(option[i].id != id2){
                option[i].style.display = "none"
            }
        }
        selecao.style.borderBottom = "3px white solid";
        selecao.style.borderTop = "3px white solid";
        selecao.textContent = "VOLTAR";
        aba.style.display = "block";
    } else {
        aba.style.display = "none";
        for (var i = 0;i < option.length; i++){
            if(option[i].id != id2){
                option[i].style.display = "block"
            }
        }
        selecao.textContent = previousText;
        selecao.style.border = "none"
    }
}
