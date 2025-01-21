document.addEventListener("DOMContentLoaded", function () {
    // Actualizar datos dinámicamente
    fetch("get_data.php")
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("data-table");
            tableBody.innerHTML = ""; // Limpiar contenido previo.

            data.forEach(row => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.nombre}</td>
                    <td>${row.rol}</td>
                `;
                tableBody.appendChild(tr);
            });
        })
        .catch(err => console.error("Error al obtener los datos:", err));
});
