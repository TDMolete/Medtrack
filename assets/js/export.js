function exportTableToPDF(tableId, filename) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.autoTable({ html: '#' + tableId });
    doc.save(filename + '.pdf');
}

function exportTableToCSV(tableId, filename) {
    let csv = [];
    let rows = document.querySelectorAll('#' + tableId + ' tr');
    rows.forEach(row => {
        let cols = row.querySelectorAll('td, th');
        let rowData = Array.from(cols).map(col => col.innerText);
        csv.push(rowData.join(','));
    });
    let csvContent = csv.join('\n');
    let blob = new Blob([csvContent], { type: 'text/csv' });
    let url = window.URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = filename + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}