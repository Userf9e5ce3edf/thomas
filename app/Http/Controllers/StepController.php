<?php

namespace App\Http\Controllers;

use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StepController extends Controller
{
    public function store(Request $request, $interventionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:0',
        ]);

        $step = new Step($request->all());
        $step->intervention_id = $interventionId;
        $step->save();

        return response()->json($step, 201);
    }

    public function updateStatus(Request $request, Step $step)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|integer|in:0,1,2',
            ]);

            $step->status = $validated['status'];
            $step->save();

            return response()->json(['success' => true]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'duration' => 'sometimes|required|integer|min:0',
        ]);

        $step = Step::findOrFail($id);
        $step->update($request->all());

        return response()->json($step);
    }

    public function destroy($id)
    {
        Step::destroy($id);
        return response()->json(null, 204);
    }
}
