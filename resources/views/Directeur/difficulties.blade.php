<x-master title="list des taches ">
    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="card mb-4 border-0 shadow bg-white">
                <div class="card-header border-0 text-dark bg-white rounded-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('list.tasks') }}" class="link-secondary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Liste des Difficultés
                        </a>
                        <a href="javascript:history.back()" class="btn text-white me-2 px-2 py-1 rounded-1"  style="background-color: #B46F55;">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Description de Difficulté</th>
                                    <th>Tâche lieé</th>
                                    <th class="text-truncate">Date de Création</th>
                                    <th>Solution Proposée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($difficulties as $difficulte)
                                    <tr>
                                        <td>{{$difficulte->difficulty}}</td>
                                        <td>{{$difficulte->task->name}}</td>
                                        <td>{{$difficulte->created_at->format("Y-m-d")}}</td>
                                        <td>{{$difficulte->solution}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{$difficulties->links()}}
                </div>
            </div>
        </div>
    </main>
</x-master>
