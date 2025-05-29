document.querySelector('#exportarCSV').addEventListener('click', (e) =>{
    sendData(e, document.querySelector('#formExportarCSV'));
});

document.querySelector('#repetirBusqueda').addEventListener('click', (e) =>{
    sendData(e, document.querySelector('#formRepetirBusqueda'), true);
});

function sendData(e, thisForm, repeatSearch = false) {
    e.preventDefault();

    const form = thisForm;

    // Borrar campos previos por si hubieran
    const oldInputs = document.querySelectorAll('#formExportarCSV input[name="selected[]"]');
    oldInputs.forEach(input => input.remove());

    // Recoger los campos y meterlos en un array
    let checkedResults = [];
    let notCheckedResults = [];
    const rows = document.querySelectorAll('table tr');
    rows.forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        const td2 = row.querySelector('td:nth-child(2)');
        const td3 = row.querySelector('td:nth-child(3)');

        if (checkbox && td2 && td3) {
            const val1 = removeNumber(td2.textContent.trim());
            const img = td3.querySelector('img');
            const val2 = img ? img.alt.trim() : '';

            if (repeatSearch) {
                if (checkbox.checked) {
                    checkedResults.push(val1);
                } else {
                    notCheckedResults.push(val1);
                }
            } else {
                if (checkbox.checked) {
                    checkedResults.push([val1, val2]);
                } else {
                    notCheckedResults.push([val1, val2]);
                }
            }
        }
    });

    const firstInput = document.createElement('input');
    firstInput.type = 'hidden';
    firstInput.name = 'markedSearches';
    const secondInput = document.createElement('input');
    secondInput.type = 'hidden';
    secondInput.name = 'notMarkedSearches';

    if(repeatSearch) {
        firstInput.value = transformArrayToString(checkedResults);
        secondInput.value = transformArrayToString(notCheckedResults);
    } else {
        firstInput.value = JSON.stringify(checkedResults);
        secondInput.value = JSON.stringify(notCheckedResults);
    }

    form.appendChild(firstInput);
    form.appendChild(secondInput);

    form.submit();
}

function removeNumber(string) {
    return string.split('. ').slice(1).join('. ');
}

function transformArrayToString(array) {
    let string="";

    array.forEach(s => {
        string += (s + ", ");
    });

    return string.slice(0, -2);
}