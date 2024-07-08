function pintarTabla(tableId, columns, data, optionsFn) {
    const table = document.getElementById(tableId);
  
    if (!table) {
      console.error(`Table with id "${tableId}" not found.`);
      return;
    }
  
    // Clear the existing content of the table
    table.innerHTML = '';
  
    // Create the table header
    const thead = table.createTHead();
    const headerRow = thead.insertRow();
    columns.forEach(column => {
      const th = document.createElement('th');
      th.textContent = column.value;
      headerRow.appendChild(th);
    });
  
    // Add an extra header for options if optionsFn is provided
    if (optionsFn) {
      const th = document.createElement('th');
      th.textContent = 'Options';
      headerRow.appendChild(th);
    }
  
    // Create the table body
    const tbody = table.createTBody();
    data.forEach(rowData => {
      const row = tbody.insertRow();
      columns.forEach(column => {
        const cell = row.insertCell();
        cell.textContent = rowData[column.key];
      });
  
      // Add the options cell if optionsFn is provided
      if (optionsFn) {
        const cell = row.insertCell();
        cell.innerHTML = optionsFn(rowData);
      }
    });
  }