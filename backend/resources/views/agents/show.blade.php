<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Agent Details</h2>
                        <div class="space-x-2">
                            <a href="{{ route('agents.edit', $agent) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Agent
                            </a>
                            <a href="{{ route('agents.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Agents
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agent Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->agent_name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Display Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->display_name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Code</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->code }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agent Image</label>
                                    @if($agent->image)
                                        <div class="mt-1">
                                            <img src="{{ asset($agent->image) }}" alt="Agent image" class="h-32 w-32 object-cover rounded-md border">
                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500">No image uploaded</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NRIC</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->nric ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->email }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mobile</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->mobile }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->address ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Professional Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Designation</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->designation }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Upline</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->upline ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sponsor</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->sponsor ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Branch</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->branch }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">REN Code</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->ren_code ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">REN License</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->ren_license ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">REN Expired Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->ren_expired_date ? $agent->ren_expired_date->format('Y-m-d') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Payee NRIC</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->payee_nric }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Payee NRIC Type</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->payee_nric_type }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bank</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->bank }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bank Account No</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->bank_account_no }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status & Dates -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Status & Dates</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Active</label>
                                    <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $agent->active === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $agent->active }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Join Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->join_date->format('Y-m-d') }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Resign Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->resign_date ? $agent->resign_date->format('Y-m-d') : 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Leaderboard</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->leaderboard ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->created_by }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Modified By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->last_modified_by ?: 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Modified Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $agent->last_modified_date ? $agent->last_modified_date->format('Y-m-d H:i:s') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Account Section -->
                    <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">User Account Management</h3>
                        
                        @if($agent->hasUserAccount())
                            <!-- Display password if available in session -->
                            @if(session('new_password') || session('user_credentials'))
                                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 rounded-lg">
                                    <h4 class="font-medium text-yellow-800 mb-2">🔑 Login Credentials</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <label class="font-medium text-yellow-700">Username:</label>
                                            <div class="flex items-center">
                                                <p class="text-yellow-900 font-mono bg-yellow-50 px-2 py-1 rounded flex-1 cursor-pointer" 
                                                   onclick="copyToClipboard('{{ session('user_credentials.username') ?? $agent->user->email }}')"
                                                   title="Click to copy">
                                                    {{ session('user_credentials.username') ?? $agent->user->email }}
                                                </p>
                                                <button onclick="copyToClipboard('{{ session('user_credentials.username') ?? $agent->user->email }}')" 
                                                        class="ml-2 text-yellow-600 hover:text-yellow-800">
                                                    📋
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="font-medium text-yellow-700">Password:</label>
                                            <div class="flex items-center">
                                                <p class="text-yellow-900 font-mono bg-yellow-50 px-2 py-1 rounded flex-1 cursor-pointer" 
                                                   onclick="copyToClipboard('{{ session('new_password') ?? session('user_credentials.password') }}')"
                                                   title="Click to copy">
                                                    {{ session('new_password') ?? session('user_credentials.password') }}
                                                </p>
                                                <button onclick="copyToClipboard('{{ session('new_password') ?? session('user_credentials.password') }}')" 
                                                        class="ml-2 text-yellow-600 hover:text-yellow-800">
                                                    📋
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-yellow-700 mt-2">
                                        ⚠️ Please save these credentials securely. The password will not be shown again after you leave this page.
                                    </p>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Account Status</h4>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $agent->user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $agent->user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <form action="{{ route('agents.toggle-user-status', $agent) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="bg-{{ $agent->user->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $agent->user->is_active ? 'red' : 'green' }}-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                {{ $agent->user->is_active ? 'Deactivate' : 'Activate' }} Account
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('agents.reset-password', $agent) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center"
                                                    onclick="return confirm('Are you sure you want to reset the password? This will generate a new password and show it on the next page.')">
                                                🔑 Reset Password
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Username/Email</label>
                                        <div class="flex items-center">
                                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded flex-1 cursor-pointer copyable-field" 
                                               onclick="copyToClipboard('{{ $agent->user->email }}')"
                                               title="Click to copy">
                                               {{ $agent->user->email }}
                                            </p>
                                            <button onclick="copyToClipboard('{{ $agent->user->email }}')" 
                                                    class="ml-2 text-gray-600 hover:text-gray-800" title="Copy username">
                                                📋
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Role</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $agent->user->role_display }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-xs text-gray-500">
                                    Account created: {{ $agent->user->created_at->format('Y-m-d H:i:s') }}
                                </div>

                                <!-- Quick Actions Info -->
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h5 class="text-sm font-medium text-blue-800 mb-2">Available Actions:</h5>
                                    <ul class="text-xs text-blue-700 space-y-1">
                                        <li>• Click any credential field or 📋 button to copy to clipboard</li>
                                        <li>• Use "Reset Password" to generate a new password (will be displayed after reset)</li>
                                        <li>• Toggle account status to activate/deactivate login access</li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-600 mb-4">This agent does not have a user account.</p>
                                <form action="{{ route('agents.create-user-account', $agent) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Create User Account
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Permissions Management Section -->
                    @if($agent->hasUserAccount())
                        <div class="mt-6 bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Agent Permissions & Navigation Control</h3>
                            
                            <form action="{{ route('agents.update-permissions', $agent) }}" method="POST" id="permissionsForm">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-4">
                                        Select which features and navigation items this agent can access. Super admins always have full access.
                                    </p>
                                </div>

                                @php
                                    $groupedPermissions = \App\Models\Permission::getGroupedPermissions();
                                    $userPermissions = $agent->user->permissions->pluck('id')->toArray();
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($groupedPermissions as $category => $permissions)
                                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                                            <h4 class="font-medium text-gray-900 mb-3 capitalize flex items-center">
                                                @switch($category)
                                                    @case('dashboard')
                                                        📊 Dashboard
                                                        @break
                                                    @case('properties')
                                                        🏠 Properties
                                                        @break
                                                    @case('agents')
                                                        👥 Agents
                                                        @break
                                                    @case('users')
                                                        👤 Users
                                                        @break
                                                    @case('analytics')
                                                        📈 Analytics
                                                        @break
                                                    @case('inquiries')
                                                        💬 Inquiries
                                                        @break
                                                    @case('listings')
                                                        📋 Listings
                                                        @break
                                                    @default
                                                        {{ ucfirst($category) }}
                                                @endswitch
                                            </h4>
                                            
                                            <div class="space-y-2">
                                                @foreach($permissions as $permission)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}"
                                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                               {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}
                                                               @if($category === 'agents') disabled title="Agent management is restricted to super admins only" @endif>
                                                        <span class="ml-2 text-sm text-gray-700 @if($category === 'agents') opacity-50 @endif">
                                                            {{ $permission->display_name }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <button type="button" 
                                                onclick="selectAllPermissions()" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Select All
                                        </button>
                                        <button type="button" 
                                                onclick="clearAllPermissions()" 
                                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            Clear All
                                        </button>
                                    </div>
                                    
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Update Permissions
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Remark -->
                    @if($agent->remark)
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Remark</h3>
                            <p class="text-sm text-gray-900">{{ $agent->remark }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for copy functionality -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show a temporary success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                toast.textContent = 'Copied to clipboard!';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 2000);
            }).catch(function(err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                toast.textContent = 'Copied to clipboard!';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 2000);
            });
        }

        // Add click-to-copy functionality to all copyable fields
        document.addEventListener('DOMContentLoaded', function() {
            // For credential fields in the yellow box
            const credentialFields = document.querySelectorAll('.font-mono.bg-yellow-50');
            credentialFields.forEach(field => {
                field.style.cursor = 'pointer';
                field.title = 'Click to copy';
                field.addEventListener('click', function() {
                    copyToClipboard(this.textContent.trim());
                });
            });

            // For other copyable fields
            const copyableFields = document.querySelectorAll('.copyable-field');
            copyableFields.forEach(field => {
                field.style.cursor = 'pointer';
                field.addEventListener('click', function() {
                    copyToClipboard(this.textContent.trim());
                });
            });
        });

        // Permissions management functions
        function selectAllPermissions() {
            const checkboxes = document.querySelectorAll('#permissionsForm input[type="checkbox"]:not([disabled])');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function clearAllPermissions() {
            const checkboxes = document.querySelectorAll('#permissionsForm input[type="checkbox"]:not([disabled])');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    </script>
</x-admin-layout>
