<?php

namespace App\Http\Controllers;


use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Task;
use App\Models\Service;
use App\Models\Difficulty;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class DirecteurController extends Controller
{
    public function index(Request $request){

        if(auth()->user() !== null){
            $user = auth()->user();
        }
        
        if($user->role==="directeur"){

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

            $tasks = $tasks->where('status', '=', 'validée')->get();
 
            return view('Directeur.index',compact('tasks', 'months', 'services', 'employees'));
        }
        else{
            abort(403,'You are not allowed to make this operation');
        }
    }

    public function difficulties(){

        $difficulties = Difficulty::with("task")->paginate(10);
        return view("Directeur.difficulties",compact( "difficulties"));
    }

    public function show(Task $task){

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

        return view('Directeur.details', compact('task', 'diff', 'sol', 'difficultiesLength', 'solutionsLength'));
    }

}
