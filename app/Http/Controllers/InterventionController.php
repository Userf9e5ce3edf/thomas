<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterventionController extends Controller
{
    public function index()
    {
        $interventions = Intervention::join('users', 'interventions.user_id', '=', 'users.id')
            ->leftJoin('steps', 'interventions.id', '=', 'steps.intervention_id')
            ->select(
                'interventions.*',
                'users.name as user_name',
                \DB::raw('COUNT(steps.id) as steps_count'),
                \DB::raw('SUM(steps.duration) as total_duration')
            )
            ->groupBy('interventions.id', 'users.name')
            ->get();

        return view('interventions.index', compact('interventions'));
    }

    public function showByCode(Request $request)
    {
        $code = $request->query('code');
        $intervention = Intervention::with('steps')->where('code', $code)->first();

        if (!$intervention) {
            return redirect()->route('interventions.index')->with('error', 'Intervention not found.');
        }

        return view('interventions.show', compact('intervention'));
    }

    public function create()
    {
        return view('interventions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'intervention_date' => 'required|date',
            'steps' => 'nullable|array',
            'steps.*.name' => 'required_with:steps|string|max:255',
            'steps.*.description' => 'required_with:steps|string',
            'steps.*.duration' => 'required_with:steps|integer|min:0', // Validate duration
        ]);

        $intervention = Intervention::create([
            'title' => $request->title,
            'description' => $request->description,
            'intervention_date' => $request->intervention_date,
            'user_id' => Auth::id(), // Set the user_id from the authenticated user
            'code' => $this->generateUniqueCode(),
        ]);

        if ($request->has('steps')) {
            foreach ($request->steps as $step) {
                Log::info('Creating step:', $step);
                $intervention->steps()->create($step);
            }
        }

        return redirect()
            ->route('interventions.index')
            ->with('success', 'Intervention created successfully.');
    }

    private function generateUniqueCode()
    {
        do {
            $code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
        } while (Intervention::where('code', $code)->exists());

        return $code;
    }

    public function show($id)
    {
        $intervention = Intervention::with('steps')->findOrFail($id);
        return response()->json($intervention);
    }

    public function update(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->update($request->all());
        return response()->json($intervention);
    }

    public function destroy($id)
    {
        Intervention::destroy($id);
        return response()->json(null, 204);
    }
}
