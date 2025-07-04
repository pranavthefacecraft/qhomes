<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Edit Agent</h2>
                        <a href="{{ route('agents.show', $agent) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Agent
                        </a>
                    </div>

                    <form action="{{ route('agents.update', $agent) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Agent Name -->
                            <div>
                                <label for="agent_name" class="block text-sm font-medium text-gray-700">Agent Name *</label>
                                <input type="text" name="agent_name" id="agent_name" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('agent_name', $agent->agent_name) }}" required>
                                @error('agent_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Display Name -->
                            <div>
                                <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name *</label>
                                <input type="text" name="display_name" id="display_name" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('display_name', $agent->display_name) }}" required>
                                @error('display_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Code *</label>
                                <input type="text" name="code" id="code" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('code', $agent->code) }}" required>
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Agent Image</label>
                                @if($agent->image)
                                    <div class="mb-2">
                                        <img src="{{ asset($agent->image) }}" alt="Current image" class="h-20 w-20 object-cover rounded-md">
                                        <p class="text-sm text-gray-500 mt-1">Current image</p>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" accept="image/*"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-sm text-gray-500">Upload new agent photo (JPEG, PNG, JPG, GIF - Max: 2MB) - Leave empty to keep current image</p>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NRIC -->
                            <div>
                                <label for="nric" class="block text-sm font-medium text-gray-700">NRIC</label>
                                <input type="text" name="nric" id="nric" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('nric', $agent->nric) }}">
                                @error('nric')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('email', $agent->email) }}" required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mobile -->
                            <div>
                                <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile *</label>
                                <input type="text" name="mobile" id="mobile" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('mobile', $agent->mobile) }}" required>
                                @error('mobile')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Designation -->
                            <div>
                                <label for="designation" class="block text-sm font-medium text-gray-700">Designation *</label>
                                <input type="text" name="designation" id="designation" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('designation', $agent->designation) }}" required>
                                @error('designation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upline -->
                            <div>
                                <label for="upline" class="block text-sm font-medium text-gray-700">Upline</label>
                                <input type="text" name="upline" id="upline" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('upline', $agent->upline) }}">
                                @error('upline')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sponsor -->
                            <div>
                                <label for="sponsor" class="block text-sm font-medium text-gray-700">Sponsor</label>
                                <input type="text" name="sponsor" id="sponsor" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('sponsor', $agent->sponsor) }}">
                                @error('sponsor')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Branch -->
                            <div>
                                <label for="branch" class="block text-sm font-medium text-gray-700">Branch *</label>
                                <select name="branch" id="branch" 
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                    <option value="">Select Branch</option>
                                    <option value="HQ" {{ old('branch', $agent->branch) == 'HQ' ? 'selected' : '' }}>HQ</option>
                                </select>
                                @error('branch')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payee NRIC -->
                            <div>
                                <label for="payee_nric" class="block text-sm font-medium text-gray-700">Payee NRIC</label>
                                <input type="text" name="payee_nric" id="payee_nric" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('payee_nric', $agent->payee_nric) }}">
                                @error('payee_nric')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payee NRIC Type -->
                            <div>
                                <label for="payee_nric_type" class="block text-sm font-medium text-gray-700">Payee NRIC Type</label>
                                <input type="text" name="payee_nric_type" id="payee_nric_type" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('payee_nric_type', $agent->payee_nric_type) }}">
                                @error('payee_nric_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bank -->
                            <div>
                                <label for="bank" class="block text-sm font-medium text-gray-700">Bank</label>
                                <input type="text" name="bank" id="bank" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('bank', $agent->bank) }}">
                                @error('bank')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bank Account No -->
                            <div>
                                <label for="bank_account_no" class="block text-sm font-medium text-gray-700">Bank Account No</label>
                                <input type="text" name="bank_account_no" id="bank_account_no" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('bank_account_no', $agent->bank_account_no) }}">
                                @error('bank_account_no')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- REN Code -->
                            <div>
                                <label for="ren_code" class="block text-sm font-medium text-gray-700">REN Code</label>
                                <input type="text" name="ren_code" id="ren_code" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('ren_code', $agent->ren_code) }}">
                                @error('ren_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- REN License -->
                            <div>
                                <label for="ren_license" class="block text-sm font-medium text-gray-700">REN License</label>
                                <input type="text" name="ren_license" id="ren_license" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('ren_license', $agent->ren_license) }}">
                                @error('ren_license')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- REN Expired Date -->
                            <div>
                                <label for="ren_expired_date" class="block text-sm font-medium text-gray-700">REN Expired Date</label>
                                <input type="date" name="ren_expired_date" id="ren_expired_date" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('ren_expired_date', $agent->ren_expired_date ? $agent->ren_expired_date->format('Y-m-d') : '') }}">
                                @error('ren_expired_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Join Date -->
                            <div>
                                <label for="join_date" class="block text-sm font-medium text-gray-700">Join Date *</label>
                                <input type="date" name="join_date" id="join_date" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('join_date', $agent->join_date->format('Y-m-d')) }}" required>
                                @error('join_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Resign Date -->
                            <div>
                                <label for="resign_date" class="block text-sm font-medium text-gray-700">Resign Date</label>
                                <input type="date" name="resign_date" id="resign_date" 
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('resign_date', $agent->resign_date ? $agent->resign_date->format('Y-m-d') : '') }}">
                                @error('resign_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Leaderboard -->
                            <div>
                                <label for="leaderboard" class="block text-sm font-medium text-gray-700">Leaderboard</label>
                                <select name="leaderboard" id="leaderboard" 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Option</option>
                                    <option value="Yes" {{ old('leaderboard', $agent->leaderboard) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('leaderboard', $agent->leaderboard) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('leaderboard')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active -->
                            <div>
                                <label for="active" class="block text-sm font-medium text-gray-700">Active *</label>
                                <select name="active" id="active" 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Select Status</option>
                                    <option value="Yes" {{ old('active', $agent->active) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('active', $agent->active) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('active')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('address', $agent->address) }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remark -->
                        <div>
                            <label for="remark" class="block text-sm font-medium text-gray-700">Remark</label>
                            <textarea name="remark" id="remark" rows="3" 
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('remark', $agent->remark) }}</textarea>
                            @error('remark')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Read-only fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created By</label>
                                <input type="text" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50" 
                                       value="{{ $agent->created_by }}" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created Date</label>
                                <input type="text" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50" 
                                       value="{{ $agent->created_at->format('Y-m-d H:i:s') }}" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Modified By</label>
                                <input type="text" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50" 
                                       value="{{ $agent->last_modified_by ?: 'N/A' }}" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Modified Date</label>
                                <input type="text" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50" 
                                       value="{{ $agent->last_modified_date ? $agent->last_modified_date->format('Y-m-d H:i:s') : 'N/A' }}" readonly>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
