<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use App\Models\Task;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    private function permission($task) {
        if(auth()->check()) {
            $user = auth()->user();
            if (!($user->role === 'admin') && !($user->id === $task->utilisateur_id) || ($user->role === 'fonctionnaire' && ($task->status === 'validée'))) {
                abort(403);
            }
        }
    }
    private function authorization($task) {
        if(auth()->check()) {
            $user = auth()->user();
            if (($user->role === 'fonctionnaire' && !($user->id === $task->utilisateur_id)) || !($user->service_id === $task->service_id) && !($user->role === 'directeur')) {
                abort(403);
            }
        }
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = auth()->user();
        // Récupérer tous les services
        $services = Service::pluck('name', 'id');
    
        // Récupérer tous les employés
        $employees = Utilisateur::whereNot('role', 'directeur')->pluck('name', 'id');
    
        // Récupérer les mois
        $months = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre',
        ];

        // Requête de récupération des tasks
        $tasks = Task::query();
        
        // Filtrer par mois
        if ($request->filled('month')) {
            $tasks->whereMonth('confirmed_at', $request->month);
        }
        
        // Filtrer par service
        if ($request->filled('service')) {
            $tasks->where('service_id', $request->service);
            $employees = Utilisateur::whereNot('role', 'directeur')->where('service_id', $request->service)->pluck('name', 'id');
        }
    
        // Filtrer par fonctionnaire
        if ($request->filled('employee')) {
            $tasks->where('utilisateur_id', $request->employee);
        }

        // Filtrer par status
        if ($request->filled('status')) {
            $tasks->where('status', $request->status);
        }
    
        // Récupérer les tasks selon les filtres
        if ($user->role === 'directeur') {
            $tasks = $tasks->where('status', 'validée')->orderBy('created_at', 'desc')->get();
        } else {
            $tasks = $tasks->where('utilisateur_id', $user->id)->orderBy('created_at', 'desc')->get();
        }
        
        return view('admin.index.task', compact('tasks', 'user', 'months', 'services', 'employees')); 
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = Utilisateur::whereIn('role',['chef', 'fonctionnaire', 'admin'])->where('id', '!=', auth()->id())->get();
        $services = Service::all();
        return view('admin.create.task', compact('users','services'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'name' => 'required|unique:tasks,name',
            'description' => 'required|max:199',
            'resource_types.*' => 'required',
            'resources.*' => 'required',
            'difficulties.*' => 'required|max:199',
            'solutions.*' => 'max:199',
        ], [
            'type.required' => 'Le type de tâche est requis.',
            'name.required' => 'Le nom de tâche est requis.',
            'description.required' => 'La description de tâche est requis.',
            'description.max' => 'La description ne doit pas dépasser 200 caractères.',
            'solutions.*.max' => 'Chaque solution ne doit pas dépasser 200 caractères.',
            'difficulties.*.max' => 'Chaque difficulté ne doit pas dépasser 200 caractères.',
            'name.unique' => 'Le nom de tâche doit être unique.',
        ]);
    
        $task = Task::create([
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'utilisateur_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
        ]);
    
        if (auth()->user()->role === 'chef') {
            $task->status = 'validée';
            $task->confirmed_at = now();
            $task->save();
        }
    
        $this->storeResources($request, $task);
        $this->storeDifficulties($request, $task);
    
        return redirect()->route('tasks.index')->with('success', 'Tâche ajoutée avec succès');
    }
    
    private function storeResources(Request $request, Task $task)
    {
        $resourceTypes = $request->input('resource_types');
        $resourceNames = $request->input('resources');
    
        if (!empty($resourceTypes) && !empty($resourceNames)) {
            foreach ($resourceTypes as $key => $type) {
                $resource = Resource::firstOrCreate(['name' => $resourceNames[$key], 'type' => $type]);
                $task->resources()->attach($resource->id);
            }
        }
    }
    
    private function storeDifficulties(Request $request, Task $task)
    {
        $difficulties = $request->input('difficulties');
        $solutions = $request->input('solutions');
    
        if (!empty($difficulties) && !empty($solutions)) {
            foreach ($difficulties as $key => $difficulty) {
                Difficulty::create([
                    'task_id' => $task->id,
                    'difficulty' => $difficulty,
                    'solution' => $solutions[$key],
                ]);
            }
        }
    }     
    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorization($task);

        $difficulties = $task->difficulties()->get();
        $diff = [];
        $sol = [];
        foreach ($difficulties as $difficulty) {
            $diff[] = $difficulty->difficulty;
        }
        foreach ($difficulties as $difficulty) {
            $sol[] = $difficulty->solution;
        };
        $difficultiesLength = count($diff);
        $solutionsLength = count($sol);
        return view('admin.show.task', compact('task', 'diff', 'sol', 'difficultiesLength', 'solutionsLength'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->permission($task);
        $users = Utilisateur::whereIn('role', ['chef', 'fonctionnaire', 'admin'])
            ->where('id', '!=', auth()->id())
            ->get();
        $services = Service::all();

        $difficulties = $task->difficulties()->get();
        $sol = [];
        foreach ($difficulties as $difficulty) {
            $sol[] = $difficulty->solution;
        };

        $solutionsLength = count($sol);

        return view('admin.edit.task', compact('task', 'users', 'services', 'sol', 'solutionsLength'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'resources.*' => 'nullable|string', // Allow empty resources
            'difficulties.*' => 'nullable|string', // Allow empty difficulties
            'solutions.*' => 'nullable|string', // Allow empty solutions
        ]);

        // Update task
        $task->update([
            'type' => $request->input('type'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        // Update resources
        $task->resources()->delete(); // Remove existing resources
        foreach ($request->input('resources', []) as $index => $resource) {
            $task->resources()->create([
                'name' => $resource,
                'type' => $request->input('resource_types')[$index],
            ]);
        }

        // Update difficulties and solutions
        $task->difficulties()->delete(); // Remove existing difficulties
        foreach ($request->input('difficulties', []) as $index => $difficulty) {
            $task->difficulties()->create([
                'difficulty' => $difficulty,
                'solution' => $request->input('solutions')[$index],
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }

    public function delete(Task $task)
    {
        $this->permission($task);
        return view('admin.delete.task', compact('task'));
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Task $task)
    {
        $this->permission($task);
        if ($task->delete()) {
            return redirect()->route('tasks.index')->with('success', 'Tâche supprimée avec succès');
        }
    }
}
