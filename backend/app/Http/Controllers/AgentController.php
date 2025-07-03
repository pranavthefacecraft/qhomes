<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = Agent::orderBy('created_at', 'desc')->paginate(10);
        return view('agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:agents',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nric' => 'nullable|string|max:255',
            'email' => 'required|email|unique:agents',
            'mobile' => 'required|string|max:255',
            'address' => 'nullable|string',
            'designation' => 'required|string|max:255',
            'upline' => 'nullable|string|max:255',
            'sponsor' => 'nullable|string|max:255',
            'branch' => 'required|string|max:255',
            'payee_nric' => 'nullable|string|max:255',
            'payee_nric_type' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:255',
            'ren_code' => 'nullable|string|max:255',
            'ren_license' => 'nullable|string|max:255',
            'ren_expired_date' => 'nullable|date',
            'join_date' => 'required|date',
            'resign_date' => 'nullable|date',
            'leaderboard' => 'nullable|in:Yes,No',
            'active' => 'required|in:Yes,No',
            'remark' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/agents'), $imageName);
            $validated['image'] = 'uploads/agents/' . $imageName;
        }

        $validated['created_by'] = Auth::user()->name;
        
        Agent::create($validated);

        return redirect()->route('agents.index')
            ->with('success', 'Agent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        return view('agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'agent_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:agents,code,' . $agent->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nric' => 'nullable|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $agent->id,
            'mobile' => 'required|string|max:255',
            'address' => 'nullable|string',
            'designation' => 'required|string|max:255',
            'upline' => 'nullable|string|max:255',
            'sponsor' => 'nullable|string|max:255',
            'branch' => 'required|string|max:255',
            'payee_nric' => 'nullable|string|max:255',
            'payee_nric_type' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:255',
            'ren_code' => 'nullable|string|max:255',
            'ren_license' => 'nullable|string|max:255',
            'ren_expired_date' => 'nullable|date',
            'join_date' => 'required|date',
            'resign_date' => 'nullable|date',
            'leaderboard' => 'nullable|in:Yes,No',
            'active' => 'required|in:Yes,No',
            'remark' => 'nullable|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($agent->image && file_exists(public_path($agent->image))) {
                unlink(public_path($agent->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/agents'), $imageName);
            $validated['image'] = 'uploads/agents/' . $imageName;
        }

        $validated['last_modified_by'] = Auth::user()->name;
        $validated['last_modified_date'] = now();
        
        $agent->update($validated);

        return redirect()->route('agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();

        return redirect()->route('agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
