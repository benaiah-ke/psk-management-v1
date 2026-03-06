<x-layouts.admin title="Create Budget">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Budget</h1>
        <p class="text-sm text-gray-500">Set up a new budget with line items.</p>
    </div>

    <form method="POST" action="{{ route('admin.budgets.store') }}"
          x-data="{
              lines: [{ category: '', description: '', budgeted_amount: 0 }],
              addLine() {
                  this.lines.push({ category: '', description: '', budgeted_amount: 0 });
              },
              removeLine(index) {
                  if (this.lines.length > 1) {
                      this.lines.splice(index, 1);
                  }
              },
              get totalBudgeted() {
                  return this.lines.reduce((sum, line) => sum + (parseFloat(line.budgeted_amount) || 0), 0);
              },
              formatNumber(num) {
                  return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
              }
          }">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Budget Details --}}
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Budget Details</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.input name="name" label="Budget Name" required placeholder="e.g. Annual Operating Budget 2026" />

                        <x-form.select name="cost_center_id" label="Cost Center" :options="$costCenters->mapWithKeys(fn($cc) => [$cc->id => $cc->code . ' - ' . $cc->name])->all()" required placeholder="Select cost center..." />

                        <x-form.input name="fiscal_year" label="Fiscal Year" type="number" required :value="old('fiscal_year', date('Y'))" min="2020" max="2050" />

                        <x-form.select name="status" label="Status" :options="['draft' => 'Draft', 'pending' => 'Pending Approval', 'approved' => 'Approved', 'active' => 'Active', 'closed' => 'Closed']" :selected="old('status', 'draft')" required />
                    </div>

                    <div class="mt-4">
                        <x-form.textarea name="notes" label="Notes" placeholder="Additional notes about this budget..." :rows="3" />
                    </div>
                </x-ui.card>

                {{-- Budget Lines --}}
                <x-ui.card>
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Budget Lines</h3>
                        <x-ui.button type="button" variant="secondary" size="sm" @click="addLine()">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Line
                        </x-ui.button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(line, index) in lines" :key="index">
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                                    <div class="sm:col-span-3">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="line.category" :name="'lines[' + index + '][category]'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                               placeholder="e.g. Staff Costs" required>
                                    </div>
                                    <div class="sm:col-span-5">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                                        <input type="text" x-model="line.description" :name="'lines[' + index + '][description]'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                               placeholder="Line item description">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Budgeted (KES) <span class="text-red-500">*</span></label>
                                        <input type="number" x-model.number="line.budgeted_amount" :name="'lines[' + index + '][budgeted_amount]'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                               min="0" step="0.01" required>
                                    </div>
                                    <div class="flex items-end sm:col-span-1">
                                        <button type="button" @click="removeLine(index)" x-show="lines.length > 1"
                                                class="rounded-lg p-2.5 text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </x-ui.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Summary --}}
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Budget Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Lines</span>
                            <span class="font-medium text-gray-900" x-text="lines.length"></span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="font-semibold text-gray-900">Total Budget</span>
                            <span class="text-lg font-bold text-gray-900" x-text="'KES ' + formatNumber(totalBudgeted)"></span>
                        </div>
                    </div>
                </x-ui.card>

                {{-- Actions --}}
                <x-ui.card>
                    <div class="space-y-3">
                        <x-ui.button type="submit" class="w-full">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Create Budget
                        </x-ui.button>
                        <x-ui.button href="{{ route('admin.budgets.index') }}" variant="secondary" class="w-full">Cancel</x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </form>
</x-layouts.admin>
