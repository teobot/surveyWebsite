function convertToCSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;

    console.log(array);
    
    var str = '';
    
    var line = '';
    for (var i = 0; i < array.length; i++) 
    {
        
        line += array[i]["title"] + ",";
    }

    str += line + "\n";

    
    for (var i = 0; i < array[0]["responses"].length; i++) 
    {
        
        var line = '';

        for (var b = 0; b < array.length; b++) 
        {
            line += array[b]["responses"][i] + ",";
        }

        str += line + "\n";

    }

    return str;
}

function exportCSVFile(items) {

    var jsonObject = JSON.stringify(items);

    var csv = this.convertToCSV(jsonObject);

    var exportedFilename = 'ExportedResponses.csv';

    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, exportedFilename);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", exportedFilename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

}