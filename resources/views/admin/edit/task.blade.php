<x-master title="Modification de tâche {{ $task->name }}">
    <main class="content px-3 py-4">
        <div class="container">
            <div class="card mb-4 border-0 shadow bg-white">
                <div class="card-header border-0 text-dark bg-white rounded-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.index') }}" class="link-secondary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
                            <i class="fas fa-edit me-1"></i>
                            Modification du tâche {{ $task->name }}
                        </a>
                        <a href="javascript:history.back()" class="btn text-white me-2 px-2 py-1 rounded-1"  style="background-color: #B46F55;">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="progressbar justify-content-evenly">
                            <div class="progress" id="progress"></div>
                            <div class="progress-step progress-step-active p-4 step0" data-title="tâches"></div>
                            <div class="progress-step p-4 step1" data-title="ressources" ></div>
                            <div class="progress-step p-4 step2" data-title="difficultés"></div>
                        </div>
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="employee-form">                        
                            @csrf
                            @method('PUT')
                            <div class="form-section">   
                                <div class="row ms-5" style="width:96%;">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label m-1">Type </label>
                                            <select style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="type" id="type">
                                                <option value="principal" {{ $task->type == 'principal' ? 'selected' : '' }}>Principal</option>
                                                <option value="supplementaire" {{ $task->type == 'supplementaire' ? 'selected' : '' }}>Supplémentaire</option>
                                            </select>
                                        </div> 
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">                
                                            <label for="name" class="form-label m-1"> Nom</label>                        
                                            <input type="text" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="name" id="name" value="{{ old('name',$task->name) }}">                       
                                        </div>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror 
                                    </div>
                                </div>
                                <div class="row ms-5 my-4" style="width:100%;">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="desc" class="form-label">Description </label>
                                            <textarea style="width: 91%;" class="form-control bg-light rounded-0 border-0 border-bottom  mt-1" id="desc" name='description' rows="2">{{ old('description',$task->description) }}</textarea>
                                        </div>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror 
                                    </div>
                                </div>
                            </div>

                            <!-- Resources Human Section -->
                            <div class="form-section">
                                <div class="row ms-5" style="width:96%;">
                                    <div class="col-md-6">
                                        <div id="resourcesH">
                                            @foreach($task->resources->where('type', 'humain') as $index => $resource)
                                                <div class="resource-row" id="humain_{{ $index }}">
                                                    <input type="hidden" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resource_types[]" id="resource_type_{{ $index }}" value="humain">
                                                    <div class="mb-3">
                                                        <label for="resource_name_{{ $index }}">Ressource humaine :</label>
                                                        <select style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resources[]" id="resource_name_{{ $index }}">
                                                            @foreach ($services as $service)
                                                                <optgroup label="{{ $service->name }}">
                                                                    @foreach ($users->where('service_id', $service->id) as $user)
                                                                        <option value="{{ $user->name.' '.$user->prenom }}" {{ $resource->name === $user->name.' '.$user->prenom ? 'selected' : '' }}>{{ $user->name.' '.$user->prenom }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                        <div class="d-flex justify-content-end" style="width: 90%;">
                                                            <button type="button" class="btn btn-dark btn-sm mt-1" onclick="removeExistResource('humain_{{ $index }}')"><i class="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-light" onclick="addHumanResourceField()"><i class="fas fa-user-tie me-1"></i> Ressource humaine</button>
                                        </div>
                                    </div>

                                    <!-- Resources Material Section -->
                                    <div class="col-md-6">
                                        <div id="resourcesM">
                                            @foreach($task->resources->where('type', 'materiel') as $index => $resource)
                                                <div class="resource-row" id="materiel_{{ $index }}">
                                                    <input type="hidden" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resource_types[]" id="resource_type_{{ $index }}" value="materiel">
                                                    <div class="mb-3">
                                                        <label for="resource_name_{{ $index }}">Ressource matérielle :</label>
                                                        <input type="text" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resources[]" id="resource_name_{{ $index }}" value="{{ $resource->name }}">
                                                        <div class="d-flex justify-content-end" style="width: 90%;">
                                                            <button type="button" class="btn btn-dark btn-sm mt-1" onclick="removeExistResource('materiel_{{ $index }}')"><i class="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-light" onclick="addMaterialResourceField()"><i class="fas fa-tools me-1"></i> Ressource matérielle</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Difficulties Section -->
                            <div class="form-section">
                                <div class="row ms-5" style="width:96%">
                                    <div class="col-md-12 difficulties">
                                        @foreach($task->difficulties as $index => $difficulty)
                                            <div class="row difficulty-row" id="difficulte_{{ $index }}">
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <label for="difficulty_{{ $index }}">Difficulté :</label>
                                                        <textarea class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="difficulties[]" id="difficulty_{{ $index }}" rows="2">{{ $difficulty->difficulty }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center" id="action_${difficultyIndex}"></div>
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <label for="solution_{{ $index }}">Solution :</label>
                                                        <textarea class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="solutions[]" id="solution_{{ $index }}" rows="2">{{ $difficulty->solution }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end" style="width: 92%;">
                                                    <button type="button" class="btn btn-dark btn-sm mt-1" onclick="removeExistDifficulty('difficulte_{{ $index }}')"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-light" onclick="addDifficultyField()"><i class="fas fa-exclamation-triangle me-2"></i> Difficulté</button>
                                </div>
                            </div>
                            
                            <div class="form-navigation mt-3" style="width:94%;">
                                <button type="button" class="previous btn text-white bg-dark fw-bold ms-5" style="float:left;">
                                    <i class="fas fa-chevron-circle-left me-2"></i> Précédent
                                </button>
                                <button type="button" class="next btn text-white bg-dark fw-bold" style="float:right;">
                                    Suivant <i class="fas fa-chevron-circle-right ms-2"></i>
                                </button>
                                <button type="submit" name="task" class="btn text-white bg-dark fw-bold" style="float:right;">
                                    <i class="fas fa-plus-circle me-2"></i> Soumettre
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-master>

<script>
    function addHumanResourceField() {
        const resourcesDiv = document.getElementById('resourcesH');
        const resourceIndex = resourcesDiv.children.length + 1;
        const resourceField = `
            <input type="hidden" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resource_types[]" id="resource_type_human_${resourceIndex}" value="humain">
            <div class="mb-2" id="resource_human_${resourceIndex}">
                <label for="resource_name_human_${resourceIndex}">Ressource humaine :</label>
                <select style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resources[]" id="resource_name_human_${resourceIndex}">
                    @foreach ($services as $service)
                        <optgroup label="{{ $service->name }}">
                            @foreach ($users->where('service_id', $service->id) as $user)
                                <option value="{{ $user->name.' '.$user->prenom }}">{{ $user->name.' '.$user->prenom }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <button type="button" class="btn btn-dark btn-sm mt-1" onclick="removeResource('resource_human_${resourceIndex}')"><i class="fas fa-trash"></i></button>
            </div>
        `;
        resourcesDiv.insertAdjacentHTML('beforeend', resourceField);
    }

    function addMaterialResourceField() {
        const resourcesDiv = document.getElementById('resourcesM');
        const resourceIndex = resourcesDiv.children.length + 1;
        const resourceField = `
            <input type="hidden" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resource_types[]" id="resource_type_material_${resourceIndex}" value="materiel">
            <div class="mb-2" id="resource_material_${resourceIndex}">
                <label for="resource_name_material_${resourceIndex}">Ressource matérielle :</label>
                <input type="text" style="width: 90%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" name="resources[]" id="resource_name_material_${resourceIndex}">
                <div class="d-flex justify-content-end" style="width:90%;">
                    <button type="button" class="btn btn-dark btn-sm mt-1" onclick="removeResource('resource_material_${resourceIndex}')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;
        resourcesDiv.insertAdjacentHTML('beforeend', resourceField);
    }

    function addDifficultyField() {
        const difficultiesDiv = document.querySelector('.difficulties');
        const difficultyIndex = difficultiesDiv.children.length + 1;

        const difficultyField = `
            <div id="difficulty_${difficultyIndex}" class="difficulty-container mb-2">
                <div class="row align-items-center">
                    <!-- Difficulty Field -->
                    <div class="col-md-5">
                        <label for="difficulty_${difficultyIndex}">Difficulté :</label>
                        <textarea style="width: 100%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" 
                                  name="difficulties[]" id="difficulty_${difficultyIndex}" rows="2" oninput="validateDifficultyOrSolution(this)"></textarea>
                        <div id="difficulty_error_${difficultyIndex}" class="text-danger"></div>
                    </div>

                    <!-- Add Solution Button -->
                    <div class="col-md-1 d-flex align-items-center" id="action_${difficultyIndex}">
                        <button type="button" id="add_solution_btn_${difficultyIndex}" class="btn text-white btn-sm ms-3" style="background-color: #B46F55;" 
                                onclick="addSolutionField(${difficultyIndex})" title="Ajouter une solution">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>

                    <!-- Solution Field (Hidden initially) -->
                    <div class="col-md-5" id="solution_container_${difficultyIndex}" style="display: none;">
                        <label for="solution_${difficultyIndex}">Solution :</label>
                        <textarea style="width: 100%;" class="form-control bg-light rounded-0 border-0 border-bottom mt-1" 
                                  name="solutions[]" id="solution_${difficultyIndex}" rows="2" oninput="validateDifficultyOrSolution(this)"></textarea>
                        <div id="solution_error_${difficultyIndex}" class="text-danger"></div>
                    </div>
                </div>

                <!-- Delete Entire Difficulty -->
                <div class="d-flex justify-content-end mt-2" style="width:92%;">
                    <button type="button" class="btn btn-dark btn-sm" onclick="removeDifficulty(${difficultyIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        difficultiesDiv.insertAdjacentHTML('beforeend', difficultyField);
    }

    function addSolutionField(difficultyIndex) {
        const solutionContainer = document.getElementById(`solution_container_${difficultyIndex}`);
        const addSolutionButton = document.getElementById(`add_solution_btn_${difficultyIndex}`);
        const actionDiv = document.getElementById(`action_${difficultyIndex}`);

        if (solutionContainer) {
            solutionContainer.style.display = 'block';  // Show solution field next to the difficulty
        }

        if (addSolutionButton) {
            addSolutionButton.style.display = 'none';  // Hide the "Add Solution" button
            const removeSolutionButtonHTML = `
                <button type="button" class="btn btn-dark btn-sm ms-3" 
                        id="remove_solution_btn_${difficultyIndex}" 
                        onclick="removeSolutionField(${difficultyIndex})" 
                        title="Supprimer la solution">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            actionDiv.insertAdjacentHTML('beforeend', removeSolutionButtonHTML);
        }
    }

    function removeSolutionField(difficultyIndex) {
        const solutionContainer = document.getElementById(`solution_container_${difficultyIndex}`);
        const addSolutionButton = document.getElementById(`add_solution_btn_${difficultyIndex}`);
        const removeSolutionButton = document.getElementById(`remove_solution_btn_${difficultyIndex}`);
        const actionDiv = document.getElementById(`action_${difficultyIndex}`);

        if (solutionContainer) {
            solutionContainer.style.display = 'none';  // Hide the solution field
        }

        if (addSolutionButton) {
            addSolutionButton.style.display = 'block';  // Show the "Add Solution" button again
        }

        if (removeSolutionButton) {
            removeSolutionButton.remove();  // Remove the "Remove Solution" button
        }
    }

    function removeDifficulty(difficultyIndex) {
        const difficultyToRemove = document.getElementById(`difficulty_${difficultyIndex}`);
        if (difficultyToRemove) {
            difficultyToRemove.remove();  // Remove the entire difficulty field
        }
    }

    function validateDifficultyOrSolution(element) {
        const maxLength = 200;
        const value = element.value;
        const errorElementId = element.name === 'difficulties[]' 
            ? `difficulty_error_${element.id.split('_')[1]}`
            : `solution_error_${element.id.split('_')[1]}`;
        const errorElement = document.getElementById(errorElementId);

        if (value.length > maxLength) {
            errorElement.textContent = `La longueur maximale est de ${maxLength} caractères.`;
        } else {
            errorElement.textContent = '';
        }
    }

    function removeResource(id) {
        const resourceToRemove = document.getElementById(id);
        if (resourceToRemove) {
            resourceToRemove.remove();  // Supprime visuellement le champ
            // Supprime également l'input hidden associé au type de ressource
            const resourceTypeInput = document.querySelector(`input#${id.replace('resource_', 'resource_type_')}`);
            if (resourceTypeInput) {
                resourceTypeInput.remove();  // Supprime l'input caché
            }
        }
    }

    function removeExistResource(id) {
        Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer cette ressource ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const resourceToRemove = document.getElementById(id);
                if (resourceToRemove) {
                    resourceToRemove.remove();  // Remove visually
                    // Remove the hidden input associated with this resource
                    const resourceTypeInput = document.querySelector(`input#resource_type_${id.split('_')[1]}`);
                    if (resourceTypeInput) {
                        resourceTypeInput.remove();  // Remove hidden input
                    }
                }
            }
        });
    }

    function removeExistDifficulty(id) {
        Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer cette difficulté ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const difficultyToRemove = document.getElementById(id);
                if (difficultyToRemove) {
                    difficultyToRemove.remove();  // Remove visually
                    // Remove associated solution field
                    const solutionInput = document.querySelector(`textarea#solution_${id.split('_')[1]}`);
                    if (solutionInput) {
                        solutionInput.remove();  // Remove solution field
                    }
                }
            }
        });
    }
    
</script>