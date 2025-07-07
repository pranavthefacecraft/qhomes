<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'email' => 'required|email|unique:agents|unique:users',
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
            'create_user_account' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/agents'), $imageName);
            $validated['image'] = 'uploads/agents/' . $imageName;
        }

        $validated['created_by'] = Auth::user()->name;
        
        // Create the agent
        $agent = Agent::create($validated);

        // Create user account if requested
        if ($request->has('create_user_account') && $request->create_user_account) {
            $username = $this->generateUsername($validated['agent_name'], $validated['code']);
            $password = $this->generatePassword();

            $user = User::create([
                'name' => $validated['display_name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'agent',
                'agent_id' => $agent->id,
                'is_active' => $validated['active'] === 'Yes',
            ]);

            return redirect()->route('agents.show', $agent)
                ->with('success', "Agent and user account created successfully!")
                ->with('user_credentials', [
                    'username' => $user->email,
                    'password' => $password
                ]);
        }

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

    /**
     * Generate a unique username based on agent name and code
     */
    private function generateUsername($agentName, $code)
    {
        // Create a base username from agent name
        $baseUsername = Str::slug(strtolower($agentName), '');
        
        // If it's too long, use the code instead
        if (strlen($baseUsername) > 10) {
            $baseUsername = strtolower($code);
        }
        
        // Ensure uniqueness
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('email', $username . '@qhomes.com')->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username . '@qhomes.com';
    }

    /**
     * Generate a random password
     */
    private function generatePassword($length = 12)
    {
        return Str::random($length);
    }

    /**
     * Create user account for existing agent
     */
    public function createUserAccount(Agent $agent)
    {
        // Check if agent already has a user account
        if ($agent->hasUserAccount()) {
            return redirect()->route('agents.show', $agent)
                ->with('error', 'This agent already has a user account.');
        }

        $username = $this->generateUsername($agent->agent_name, $agent->code);
        $password = $this->generatePassword();

        $user = User::create([
            'name' => $agent->display_name ?: $agent->agent_name,
            'email' => $username,
            'password' => Hash::make($password),
            'role' => 'agent',
            'agent_id' => $agent->id,
            'is_active' => $agent->active === 'Yes',
        ]);

        return redirect()->route('agents.show', $agent)
            ->with('success', "User account created successfully! Username: {$user->email}, Password: {$password}")
            ->with('user_credentials', [
                'username' => $user->email,
                'password' => $password
            ]);
    }

    /**
     * Toggle user account status
     */
    public function toggleUserStatus(Agent $agent)
    {
        if (!$agent->hasUserAccount()) {
            return redirect()->route('agents.show', $agent)
                ->with('error', 'This agent does not have a user account.');
        }

        $user = $agent->user;
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('agents.show', $agent)
            ->with('success', "User account has been {$status}.");
    }

    /**
     * Reset user password for an agent
     */
    public function resetPassword(Agent $agent)
    {
        if (!$agent->hasUserAccount()) {
            return redirect()->route('agents.show', $agent)
                ->with('error', 'This agent does not have a user account.');
        }

        $newPassword = $this->generatePassword();
        
        $user = $agent->user;
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('agents.show', $agent)
            ->with('success', "Password reset successfully! New password: {$newPassword}")
            ->with('new_password', $newPassword);
    }

    /**
     * Update user permissions for an agent
     */
    public function updatePermissions(Request $request, Agent $agent)
    {
        if (!$agent->hasUserAccount()) {
            return redirect()->route('agents.show', $agent)
                ->with('error', 'This agent does not have a user account.');
        }

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        
        // Remove agent-related permissions as they should only be for super admins
        $agentPermissions = \App\Models\Permission::where('category', 'agents')->pluck('id')->toArray();
        $permissionIds = array_diff($permissionIds, $agentPermissions);

        $agent->user->syncPermissions($permissionIds);

        $permissionsCount = count($permissionIds);
        
        return redirect()->route('agents.show', $agent)
            ->with('success', "Agent permissions updated successfully! {$permissionsCount} permission(s) assigned.");
    }
}
