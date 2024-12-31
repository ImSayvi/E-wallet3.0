import Chart from 'chart.js';
console.log("dsjfofj");

// Funkcja do zmiany tytułu modala oraz zapobieganiu usunięciu minusa
let modalTittle = document.getElementById('exampleModalLabel');
let modalamountInput = document.getElementById("amountInput");
let modalSelect = document.getElementById('category');
let initialMinus = false;

function changeModalTittle(action) {
    if (action === 'add') {
        modalTittle.innerText = "Dodawanie pieniędzy do budżetu dziennego";
        modalamountInput.value = "";
        initialMinus = false;
        modalSelect.value = 'other';
        modalSelect.setAttribute("disabled", true);
        document.getElementById("otherCategoryDiv").style.display = "block";
    }
    if (action === 'sub') {
        modalTittle.innerText = "Odejmowanie pieniędzy z budżetu dziennego";
        modalamountInput.value = "-";
        initialMinus = true;
        modalSelect.removeAttribute("disabled");
    }
}

modalamountInput.addEventListener('keydown', function (event) {
    if (initialMinus && (event.key === 'Backspace' || event.key === 'Delete')) {
        if (modalamountInput.value === '-') {
            event.preventDefault();
        }
    }
});

// działanie przycisku 'close' w modalu
document.getElementById("btn-close").addEventListener("click", clearInputs);

// funkcja do ustawiania daty na dzisiejsza
function setTodayDate() {
    let today = new Date().toISOString().split('T')[0];
    return today;
};
window.addEventListener('load', function() {
    document.querySelector(".date").value = setTodayDate();
});

// doprowadzanie modala do pierwotnego stanu
function clearInputs() {
    document.getElementById("amountInput").value = "";
    document.getElementById("category").value = "wybierz kategorie";
    document.getElementById("otherCategory").value = "";
    document.getElementById("date").value = setTodayDate();
    document.getElementById("otherCategoryDiv").style.display = "none";
    document.getElementById("feeAmount").value = "";
    document.getElementById("whatFor").value = "";
    document.getElementById("icon").value = "wybierz ikonke";
}

// wywolanie funkcji do zmiany daty na dzisiejsza
window.onload = function() {
    document.getElementById("date").value = setTodayDate();
};


//do datatables
$(document).ready(function() {
    $('#dataTable').DataTable(); 
});

