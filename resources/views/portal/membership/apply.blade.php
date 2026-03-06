<x-layouts.portal title="Apply for Membership">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Apply for Membership</h1>
        <p class="text-sm text-gray-500">Complete the form below to submit your membership application</p>
    </div>

    <form method="POST" action="{{ route('portal.membership.submit') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl">
        @csrf

        {{-- Step 1: Select Tier --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Select Membership Tier</h3>
            <p class="mb-4 text-sm text-gray-500">Choose the membership tier that best suits your qualifications and career stage.</p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2" x-data="{ selectedTier: '{{ old('membership_tier_id', '') }}' }">
                @foreach($tiers as $tier)
                    <label class="relative cursor-pointer"
                           @click="selectedTier = '{{ $tier->id }}'">
                        <input type="radio" name="membership_tier_id" value="{{ $tier->id }}" class="peer sr-only"
                               x-model="selectedTier" required>
                        <div class="rounded-xl border-2 border-gray-200 p-4 transition peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-gray-300">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $tier->name }}</h4>
                                    @if($tier->description)
                                        <p class="mt-1 text-xs text-gray-500">{{ $tier->description }}</p>
                                    @endif
                                </div>
                                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-500"
                                     :class="selectedTier === '{{ $tier->id }}' ? 'border-primary-500 bg-primary-500' : 'border-gray-300'">
                                    <svg x-show="selectedTier === '{{ $tier->id }}'" class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                </div>
                            </div>
                            <div class="mt-3 flex items-baseline gap-2 border-t border-gray-100 pt-3">
                                <div>
                                    <span class="text-lg font-bold text-gray-900">KES {{ number_format($tier->annual_fee) }}</span>
                                    <span class="text-xs text-gray-500">/year</span>
                                </div>
                                @if($tier->registration_fee > 0)
                                    <span class="text-xs text-gray-500">+ KES {{ number_format($tier->registration_fee) }} registration</span>
                                @endif
                            </div>
                            @if($tier->cpd_points_required)
                                <p class="mt-2 text-xs text-gray-500">Requires {{ $tier->cpd_points_required }} CPD points/year</p>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
            @error('membership_tier_id')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </x-ui.card>

        {{-- Step 2: Professional Details --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Professional Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-form.input name="ppb_registration_no" label="PPB Registration Number" required
                                  placeholder="Enter your PPB registration number"
                                  :value="auth()->user()->ppb_registration_no"
                                  hint="Your Pharmacy and Poisons Board registration number" />
                </div>
            </div>
        </x-ui.card>

        {{-- Step 3: Motivation --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Motivation</h3>
            <x-form.textarea name="motivation" label="Why do you want to join PSK?" rows="4"
                             placeholder="Briefly describe your motivation for joining the Pharmaceutical Society of Kenya..." />
        </x-ui.card>

        {{-- Step 4: Documents --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Supporting Documents</h3>
            <p class="mb-4 text-sm text-gray-500">Upload relevant documents to support your application (e.g., PPB certificate, academic certificates, ID).</p>
            <div class="space-y-4">
                <x-form.file-upload name="documents[]" label="Upload Documents" accept=".pdf,.jpg,.jpeg,.png"
                                    hint="Accepted formats: PDF, JPG, PNG. Maximum 5MB per file." multiple />
            </div>
        </x-ui.card>

        {{-- Terms --}}
        <x-ui.card class="mb-6">
            <x-form.checkbox name="agree_terms" label="I confirm that the information provided is accurate and I agree to abide by the PSK constitution and by-laws."
                             :checked="old('agree_terms')" />
            @error('agree_terms')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('portal.membership') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Submit Application
            </x-ui.button>
        </div>
    </form>
</x-layouts.portal>
