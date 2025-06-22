<x-master title="La suppression d'utilisateur {{ $user->name.' '.$user->prenom }}">
    <div class="container mt-5 opacity-75" style="width: 50%;">
        <div class="card mb-4 border-0 shadow bg-white mt-5 rounded-top-2">
            <div class="card-header border-0 text-secondary bg-white shadow-sm py-2 rounded-0 mt-2">
                <div>
                    <i class="fas fa-trash me-2"></i> 
                    Supprimer l'utilisateur {{ $user->name.' '.$user->prenom }}
                </div>
            </div>
            <div class="card-body my-2">
                <div class="px-5 py-5 bg-white shadow-sm mx-auto">
                    <h5>Êtes-vous sûr de vouloir supprimer l'utilisateur {{ $user->name.' '.$user->prenom }} ? Cette action est irréversible.</h5>
                    <div class="d-flex justify-content-evenly mt-5">
                        <form action="{{ route('users.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn btn-danger px-4 py-2 me-5">Supprimer</button>
                            <a class="btn btn btn-info text-white px-4 py-2 ms-5" href="javascript:history.back()">
                                Cancel
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master>