<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Guide - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <div class="mx-auto max-w-4xl px-4 py-12">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-primary-700 text-lg font-bold text-white">PSK</div>
            <h1 class="text-2xl font-bold text-gray-900">PSK Management Platform</h1>
            <p class="mt-1 text-gray-500">Demo Credentials & System Guide</p>
        </div>

        {{-- Login Credentials --}}
        <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Demo Login Credentials</h2>
            <p class="mb-4 text-sm text-gray-500">All accounts use the password: <code class="rounded bg-gray-100 px-2 py-0.5 font-mono text-sm font-semibold text-primary-700">password</code></p>

            <h3 class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-400">Admin Accounts</h3>
            <div class="mb-6 overflow-hidden rounded-lg border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5">Role</th>
                            <th class="px-4 py-2.5">Name</th>
                            <th class="px-4 py-2.5">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Super Admin</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Super Admin</td>
                            <td class="px-4 py-2.5 font-mono text-xs">admin@psk.or.ke</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">Finance</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Grace Wanjiku</td>
                            <td class="px-4 py-2.5 font-mono text-xs">finance@psk.or.ke</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">Admin</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">James Ochieng</td>
                            <td class="px-4 py-2.5 font-mono text-xs">secretary@psk.or.ke</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-400">Member Accounts (sample)</h3>
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5">Tier</th>
                            <th class="px-4 py-2.5">Name</th>
                            <th class="px-4 py-2.5">Email</th>
                            <th class="px-4 py-2.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Pharmacist</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Wanjiku Kamau</td>
                            <td class="px-4 py-2.5 font-mono text-xs">wanjiku.kamau@email.com</td>
                            <td class="px-4 py-2.5"><span class="text-xs text-green-600">Active</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Fellow</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Atieno Oduor</td>
                            <td class="px-4 py-2.5 font-mono text-xs">atieno.oduor@email.com</td>
                            <td class="px-4 py-2.5"><span class="text-xs text-green-600">Active</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-cyan-100 px-2 py-0.5 text-xs font-medium text-cyan-700">Intern</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Chebet Kiplagat</td>
                            <td class="px-4 py-2.5 font-mono text-xs">chebet.kiplagat@email.com</td>
                            <td class="px-4 py-2.5"><span class="text-xs text-green-600">Active</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Corporate</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Nyambura Wainaina</td>
                            <td class="px-4 py-2.5 font-mono text-xs">nyambura.wainaina@email.com</td>
                            <td class="px-4 py-2.5"><span class="text-xs text-green-600">Active</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-pink-100 px-2 py-0.5 text-xs font-medium text-pink-700">Student</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">George Kamau</td>
                            <td class="px-4 py-2.5 font-mono text-xs">george.kamau@email.com</td>
                            <td class="px-4 py-2.5"><span class="text-xs text-orange-600">Expired</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5"><span class="rounded-full bg-pink-100 px-2 py-0.5 text-xs font-medium text-pink-700">Student</span></td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Brian Kiprotich</td>
                            <td class="px-4 py-2.5 font-mono text-xs">brian.kiprotich@email.com</td>
                            <td class="px-4 py-2.5"><span class="text-xs text-yellow-600">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- System Guide --}}
        <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">System Guide</h2>

            <div class="space-y-6">
                {{-- Membership --}}
                <div>
                    <h3 class="mb-2 flex items-center gap-2 font-semibold text-gray-800">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">1</span>
                        Membership Management
                    </h3>
                    <div class="ml-8 space-y-1 text-sm text-gray-600">
                        <p><strong>Application Flow:</strong> Member registers &rarr; Submits application with documents &rarr; Admin reviews &rarr; Approves/Rejects &rarr; Invoice generated on approval</p>
                        <p><strong>Renewal:</strong> Members renew annually. System sends reminders 30 days before expiry. Expired memberships are auto-marked.</p>
                        <p><strong>Tiers:</strong> Student, Intern, Pharmacist, Fellow, Corporate, Honorary &mdash; each with different annual fees.</p>
                    </div>
                </div>

                {{-- Finance --}}
                <div>
                    <h3 class="mb-2 flex items-center gap-2 font-semibold text-gray-800">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">2</span>
                        Finance & Payments
                    </h3>
                    <div class="ml-8 space-y-1 text-sm text-gray-600">
                        <p><strong>Invoices:</strong> Auto-generated for membership applications, renewals, and event registrations. Supports partial payments.</p>
                        <p><strong>Payments:</strong> Record payments against invoices (M-Pesa, bank transfer, cash, cheque, card). Receipts auto-generated.</p>
                        <p><strong>Budgets:</strong> Create budgets tied to cost centers with line items for tracking expenditure.</p>
                    </div>
                </div>

                {{-- Events --}}
                <div>
                    <h3 class="mb-2 flex items-center gap-2 font-semibold text-gray-800">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">3</span>
                        Events & CPD
                    </h3>
                    <div class="ml-8 space-y-1 text-sm text-gray-600">
                        <p><strong>Events:</strong> Create conferences, workshops, seminars, and webinars with multiple ticket types. Capacity-limited with QR code check-in.</p>
                        <p><strong>CPD:</strong> Track Continuing Professional Development points. Members log activities; admins approve. Annual target: 30 points.</p>
                        <p><strong>Categories:</strong> Conference Attendance, Workshop, Research Publication, Self-Study, Community Service, Online Course.</p>
                    </div>
                </div>

                {{-- Communications --}}
                <div>
                    <h3 class="mb-2 flex items-center gap-2 font-semibold text-gray-800">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">4</span>
                        Communications
                    </h3>
                    <div class="ml-8 space-y-1 text-sm text-gray-600">
                        <p><strong>Bulk Email:</strong> Send newsletters, notices, and alerts filtered by membership tier, branch, or role.</p>
                        <p><strong>Community:</strong> Members can create posts and comment in the community forum.</p>
                    </div>
                </div>

                {{-- Support --}}
                <div>
                    <h3 class="mb-2 flex items-center gap-2 font-semibold text-gray-800">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">5</span>
                        Support Tickets
                    </h3>
                    <div class="ml-8 space-y-1 text-sm text-gray-600">
                        <p><strong>Tickets:</strong> Members submit support requests (general inquiry, technical, billing, complaint). Admins assign, respond, and resolve.</p>
                        <p><strong>Priority:</strong> Low, Medium, High, Urgent &mdash; with threaded responses.</p>
                    </div>
                </div>

                {{-- Branches & Committees --}}
                <div>
                    <h3 class="mb-2 flex items-center gap-2 font-semibold text-gray-800">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">6</span>
                        Branches & Committees
                    </h3>
                    <div class="ml-8 space-y-1 text-sm text-gray-600">
                        <p><strong>Branches:</strong> Regional branches (Nairobi, Mombasa, Kisumu, Nakuru) with their own members and cost centers.</p>
                        <p><strong>Committees:</strong> Governance committees (Ethics, Education, Finance) with chair and member roles.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Roles & Permissions --}}
        <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Roles & Access</h2>
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-2.5">Role</th>
                            <th class="px-4 py-2.5">Access</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Super Admin</td>
                            <td class="px-4 py-2.5 text-gray-600">Full system access. Manage all modules, settings, roles, and users.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Admin</td>
                            <td class="px-4 py-2.5 text-gray-600">Manage members, events, communications, and tickets. No settings access.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Finance</td>
                            <td class="px-4 py-2.5 text-gray-600">Manage invoices, payments, receipts, budgets, and cost centers.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Branch Admin</td>
                            <td class="px-4 py-2.5 text-gray-600">Manage their branch members, events, and branch-level reports.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Committee Admin</td>
                            <td class="px-4 py-2.5 text-gray-600">Manage committee members, meetings, and committee activities.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-gray-900">Member</td>
                            <td class="px-4 py-2.5 text-gray-600">Portal access: view membership, register for events, log CPD, submit tickets, community.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-800">
                Sign In &rarr;
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Register New Account
            </a>
        </div>
    </div>
</body>
</html>
