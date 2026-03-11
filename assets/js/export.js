/**
 * Export Functions (PDF / CSV)
 * 
 * Dependencies: jsPDF, jspdf-autotable (loaded in header)
 */

// ---------- PDF Export ----------
window.exportTableToPDF = function(tableId, filename = 'export') {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Use autoTable plugin
    doc.autoTable({ html: '#' + tableId });
    
    doc.save(filename + '.pdf');
};

// ---------- CSV Export ----------
window.exportTableToCSV = function(tableId, filename = 'export') {
    const table = document.getElementById(tableId);
    if (!table) return;

    const rows = table.querySelectorAll('tr');
    const csv = [];

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = Array.from(cols).map(col => {
            // Escape double quotes and wrap in quotes if contains comma or newline
            let text = col.innerText.replace(/"/g, '""');
            if (text.includes(',') || text.includes('\n') || text.includes('"')) {
                text = `"${text}"`;
            }
            return text;
        });
        csv.push(rowData.join(','));
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', filename + '.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
};