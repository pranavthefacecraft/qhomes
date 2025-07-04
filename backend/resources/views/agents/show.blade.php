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
</x-admin-layout>
