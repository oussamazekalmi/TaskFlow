<x-master title="Ajout de tâches">
    <main class="content px-3 py-4">
        <div class="container">
            <div class="card mb-4 border-0 shadow bg-white">
                <div class="card-header border-0 text-dark bg-white rounded-0 shadow-sm mb-3">
                    <i class="fas fa-table me-1"></i>
                    Liste des tâches Réalisées
                </div>
                <form action="{{ route('list.tasks') }}" method="GET" class="px-3 content-form">
                    <div class="card border-0 px-3">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="month" class="form-label ms-1 fw-bold text-secondary">Mois :</label>
                                <select name="month" id="month" class="form-select bg-light rounded-0">
                                    <option value="">Toutes les mois</option>
                                    @foreach (['1' => 'janvier', '2' => 'février','3' => 'mars','4' => 'avril',] as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="service" class="form-label ms-1 fw-bold text-secondary">Service :</label>
                                <select name="service" id="service" class="form-select bg-light rounded-0">
                                    <option value="">Tous les services</option>
                                    @foreach ($services as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3 me-5">
                                <label for="employee" class="form-label ms-1 ms-1 fw-bold text-secondary">Fonctionnaire :</label>
                                <select name="employee" id="employee" class="form-select bg-light rounded-0">
                                    <option value="">Tous les fonctionnaires</option>
                                    @foreach ($employees as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="filtre d-flex justify-content-end me-2 mt-4">
                        <button type="submit" class="btn border-0 text-secondary btn-transparent fw-bold"><i class="fas fa-filter"></i> Filtrer</button>
                        <a href="{{ route('list.tasks') }}" class="btn border-0  mx-3 btn-transparent text-danger fw-bold"><i class="fas fa-eraser"></i> Clear</a>
                    </div>
                </form>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="dynamic-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID Tâche</th>
                                    <th class="text-truncate">Tâche</th>
                                    <th>Date de création</th>
                                    <th>Responsable</th>
                                    <th>Type</th>
                                    <th>Service</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td class="text-truncate" style="max-width: 150px;">{{ $task->name }}</td>
                                        <td>{{ $task->created_at->format("Y-m-d") }}</td>
                                        <td>{{ $task->utilisateur->name }}</td>
                                        <td>{{ $task->type }}</td>
                                        <td>{{ $task->service->name }}</td>
                                        <td>
                                            <a class="btn bg-light px-2 py-0 text-dark rounded-0" href="{{ route('detail.task', $task->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between border-0 shadow text-center">
                    <button onclick="exportToExcel()" class="btn btn-success rounded-1 px-3 py-1">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button onclick="exportToPDF()" class="btn btn-danger rounded-1 px-3 py-1">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
        </div>
    </main>
</x-master>

<script>
    function exportToExcel() {
        const table = document.getElementById("dynamic-table");
        const rows = table.querySelectorAll("tbody tr");
        const headers = Array.from(table.querySelectorAll("thead th")).map(th => th.textContent);
        headers.pop();
        if (rows.length === 0) {
            alert("No rows to export!");
            return;
        }
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers]);
        rows.forEach(row => {
            const rowData = [];
            row.querySelectorAll("td:not(:last-child)").forEach(cell => { // Exclude the last td element in each row
                rowData.push(cell.textContent);
            });
            XLSX.utils.sheet_add_aoa(ws, [rowData], {origin: -1});
        });
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        const wbout = XLSX.write(wb, {bookType:'xlsx', type:'array'});
        const blob = new Blob([wbout], {type: 'application/octet-stream'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = "exported_data.xlsx";
        link.click();
    }
    function exportToPDF() {
        const table = document.getElementById('dynamic-table').cloneNode(true); // Clone the table
        const actionCells = table.querySelectorAll('td:last-child');
        actionCells.forEach(cell => {
            cell.innerHTML = ''; // Remove content of action cells
        });
        const actionCellss = table.querySelectorAll('th:last-child');
        actionCellss.forEach(cell => {
            cell.innerHTML = ''; // Remove content of action cells
        });
        html2pdf().from(table).save();
    }
</script>
