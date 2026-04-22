<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::latest()->get();
        return view('admin.skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.skills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'icon'       => 'nullable|string|max:255',
            'level'      => 'nullable|string|max:255',
            'status'     => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Skill::create($request->all());

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    public function show(Skill $skill)
    {
        return view('admin.skills.show', compact('skill'));
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'icon'       => 'nullable|string|max:255',
            'level'      => 'nullable|string|max:255',
            'status'     => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $skill->update($request->all());

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }
}