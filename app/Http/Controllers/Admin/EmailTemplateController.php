<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailTemplate::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $templates = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.email-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.email-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name',
            'slug' => 'nullable|string|max:255|unique:email_templates,slug',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'nullable|string|max:50',
            'variables' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function show(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.show', compact('emailTemplate'));
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $emailTemplate->id,
            'slug' => 'nullable|string|max:255|unique:email_templates,slug,' . $emailTemplate->id,
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'nullable|string|max:50',
            'variables' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $emailTemplate->update($validated);

        return redirect()->route('admin.email-templates.show', $emailTemplate)
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}
