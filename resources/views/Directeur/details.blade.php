<x-master title="Le détail de tache">
    <main class="content px-3 py-4">
        <div class="container">
            <div class="card mb-4 border-0 shadow bg-white">
                <div class="card-header border-0 text-dark bg-white rounded-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('list.tasks') }}" class="link-secondary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
                            <i class="fas fa-eye me-1"></i>
                            Détails de la tâche {{ $task->name }}
                        </a>
                        <a href="javascript:history.back()" class="btn text-white me-2 px-2 py-1 rounded-1"  style="background-color: #B46F55;">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </div>
                </div>
                <div id="taskCardBody" style="display:none;">
                    <h3 class="text-center mt-4 mb-5" style="color: #8E5743;">Détails de la tâche {{ $task->name }}</h3>
                    <div class="d-flex justify-content-between mt-5 pt-4">
                        <div>
                            <div class="form-group">
                                <label for="taskname" style="color: #8E5743;font-weight: bold; font-size: x-large;">Tâche {{ $task->type }}</label>
                                <input type="text" style="font-weight: bold; font-size: x-large;" id="taskname" class="form-control bg-light border-0 mt-2" value="{{ $task->name }}" readonly>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="responsible" style="color: #8E5743;font-weight: bold; font-size: x-large;">Responsable </label>
                                <input type="text" style="font-weight: bold; font-size: x-large;" id="responsible" class="form-control bg-light border-0 mt-2" value="{{ $task->utilisateur->name.' '.$task->utilisateur->prenom }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between my-4">
                        <div>
                            <div class="form-group">
                                <label for="confimed" style="color: #8E5743;font-weight: bold; font-size: x-large;">{{ $task->status === 'validée' ? 'Validée en' : 'Rejetée en'}} </label>
                                <input type="text" style="font-weight: bold; font-size: x-large;" id="confimed" class="form-control bg-light border-0 mt-2" value="{{ $task->confirmed_at }}" readonly>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="created" style="color: #8E5743;font-weight: bold; font-size: x-large;">Date de création</label>
                                <input type="text" style="font-weight: bold; font-size: x-large;" id="created" class="form-control bg-light border-0 mt-2" value="{{ $task->created_at->format('Y-m-d') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="my-4">
                            <div class="form-group">
                                <label for="description" style="color: #8E5743;font-weight: bold; font-size: x-large;">Description</label>
                                <p id="description" style="font-weight: bold;">{{ $task->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        @if ($task->resources()->where('type', 'humain')->count() > 0)
                            <div class="card my-4 p-0" style="width: 48%;">
                                <div class="card-header text-dark py-2 bg-light">
                                    <h6 class="m-0" style="color: #8E5743;font-weight: bold; font-size: x-large;">Ressources humaines</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        @foreach ($task->resources as $resource)
                                            @if ($resource->type === 'humain')
                                                <p style="font-weight: bold; font-size: x-large;"> {{ $resource->name }}</p>   
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($task->resources()->where('type', 'materiel')->count() > 0)
                            <div class="card my-4 p-0" style="width: 48%;">
                                <div class="card-header text-dark py-2 bg-light">
                                    <h6 class="m-0" style="color: #8E5743;font-weight: bold; font-size: x-large;">Ressources matérielles</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        @foreach ($task->resources as $resource)
                                            @if ($resource->type === 'materiel')
                                                <p style="font-weight: bold; font-size: x-large;"> {{ $resource->name }}</p>     
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        @if ($difficultiesLength > 0)
                            <div class="card my-4 p-0" style="width: 48%;">
                                <div class="card-header text-dark py-2 bg-light">
                                    <h6 class="m-0">Difficultés rencontrées</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        @foreach ($diff as $index => $difficulty)
                                            <p style="font-weight: bold; font-size: x-large;"><span class="text-danger">Difficulté {{ $index + 1 }} :</span> {{ $difficulty }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($solutionsLength > 0)
                            @php
                                $hasNonNullSolutions = false;
                                foreach ($sol as $s) {
                                    if ($s !== null) {
                                        $hasNonNullSolutions = true;
                                        break;
                                    }
                                }
                            @endphp
                            @if ($hasNonNullSolutions)
                                <div class="card my-4 p-0" style="width: 48%;">
                                    <div class="card-header text-dark py-2 bg-light">
                                        <h6 class="m-0">Solutions proposées</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            @foreach ($sol as $index => $solution)
                                                <p style="font-weight: bold; font-size: x-large;"><span class="text-info">Solution {{ $index + 1 }} :</span> {{ $solution }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="taskname">Tâche {{ $task->type }} </label>
                                <input type="text" id="taskname" style="width: 96%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" value="{{ $task->name }}" readonly>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="responsible">Responsable </label>
                                <a href="{{ route('users.show', $task->utilisateur->id) }}">
                                    <input type="text" id="responsible" style="width: 96%;cursor:pointer;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" style="cursor: pointer;" value="{{ $task->utilisateur->name.' '.$task->utilisateur->prenom }}" readonly>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-4">
                            <div class="form-group">
                                @if ($task->confirmed_at !== NULL)
                                    <label for="confimed">{{ $task->status === 'validée' ? 'Validée en' : 'Rejetée en'}} </label>
                                    <input type="text" id="confimed" style="width: 96%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" value="{{ $task->confirmed_at }}" readonly>
                                @else
                                    <label for="unconfirmed">Statut</label>
                                    <input type="text" id="unconfirmed" style="width: 96%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" value="{{ $task->status }}" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 my-4">
                            <div class="form-group">
                                <label for="created">Date de création</label>
                                <input type="text" id="created" style="width: 96%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" value="{{ $task->created_at->format('Y-m-d') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 my-4">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" style="width: 98%; height: 90px;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" readonly>{{ $task->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-start">
                    @if ($task->resources()->where('type', 'humain')->count() > 0)
                        <div class="card my-4 border-0 shadow me-5 rounded-0 p-0" style="width:46%;">
                            <div class="card-header border-0 text-dark py-2 rounded-0 bg-light">
                                <h6 class="m-0 text-primary"><i class="fas fa-user-tie me-2"></i>Ressources humains</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    @foreach ($task->resources as $index => $resource)
                                        @if ($resource->type === 'humain')
                                            <p class="ms-3"> {{ $resource->name }}</p>   
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($task->resources()->where('type', 'materiel')->count() > 0)
                        <div class="card my-4 border-0 shadow me-4 rounded-0 p-0" style="width:46%;">
                            <div class="card-header border-0 text-dark py-2 rounded-0 bg-light">
                                <h6 class="m-0 text-primary"><i class="fas fa-tools me-2"></i>Ressources matériels</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    @foreach ($task->resources as $index => $resource)
                                        @if ($resource->type === 'materiel')
                                            <p class="ms-3"> {{ $resource->name }}</p>     
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                    <div class="row d-flex justify-content-start">
                    @if ($difficultiesLength > 0)
                        <div class="card my-4 border-0 shadow me-5 rounded-0 p-0" style="width:46%;">
                            <div class="card-header border-0 text-dark py-2 rounded-0 bg-light">
                                <h6 class="m-0"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Difficultés rencontrées</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    @foreach ($diff as $index => $difficulty)
                                        <p class="ms-3"><span class="text-danger">Difficulté {{ $index + 1 }} : </span>&nbsp; {{ $difficulty }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($solutionsLength > 0)
                        @php
                            $hasNonNullSolutions = false;
                            foreach ($sol as $s) {
                                if ($s !== null && strlen($s) > 0) {
                                    $hasNonNullSolutions = true;
                                    break;
                                }
                            }
                        @endphp
                        @if ($hasNonNullSolutions)
                            <div class="card my-4 border-0 shadow me-4 rounded-0 p-0" style="width:46%;">
                                <div class="card-header border-0 text-dark py-2 rounded-0 bg-light">
                                    <h6 class="m-0"><i class="fas fa-shield-alt text-info me-2"></i>Solutions proposées</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        @foreach ($sol as $index => $solution)
                                            @if (strlen($solution) > 0)
                                                <p class="ms-3"><span class="text-info">Solution {{ $index + 1 }} : </span>&nbsp; {{ $solution }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    </div>
                </div>
                <div class="card-header border-0 text-dark bg-light rounded-0 shadow-sm text-end">
                    <button class="btn btn-dark" id="printButton">Imprimer</button>
                </div>
            </div>
        </div>
    </main>
</x-master>

<script>
    document.getElementById('printButton').addEventListener('click', function() {
        // Récupérer la section `card-body`
        var cardBody = document.getElementById('taskCardBody').innerHTML;

        // Créer une nouvelle fenêtre
        var printWindow = window.open('', '_blank');

        // Injecter le contenu de `card-body` dans la nouvelle fenêtre
        printWindow.document.open();
        printWindow.document.write(`
            <html>
            <head>
                <title>Impression</title>
                <!-- Inclure les styles nécessaires -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
            </head>
            <body>
                <div class="container">
                    <div class="card-body p-4 rounded">
                        ${cardBody}
                    </div>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();

        // Lancer l'impression
        printWindow.print();

        // Fermer la fenêtre après l'impression
        printWindow.onafterprint = function() {
            printWindow.close();
        };
    });
</script>
