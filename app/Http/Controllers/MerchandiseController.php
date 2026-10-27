<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MerchandiseController extends Controller
{
    /**
     * Display a paginated list of all merchandise.
     */
    public function index(Request $request)
    {
        return view('pages.systems.merchandise_index', [
            'data' => Merchandise::query()
                ->orderBy('created_at', 'desc')
                ->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new merchandise item, or store it.
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'path_image' => ['nullable', 'image'],
                'is_available' => ['nullable', 'boolean'],
            ]);

            if ($request->hasFile('path_image')) {
                $validated['path_image'] = $request->file('path_image')->storePublicly('merchandises', 'public');
            }

            $validated['is_available'] = $request->boolean('is_available');

            Merchandise::create($validated);

            return redirect()->route('merchandise.index')
                ->with('success', 'Merchandise created successfully.');
        }

        return view('pages.systems.merchandise_edit', [
            'data' => new Merchandise,
            'method' => 'post',
        ]);
    }

    /**
     * Show the form for editing an existing merchandise item, or update it.
     */
    public function edit(Request $request, Merchandise $merchandise): mixed
    {
        if ($request->isMethod('put')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'path_image' => ['nullable', 'image'],
                'is_available' => ['nullable', 'boolean'],
            ]);

            if ($request->hasFile('path_image')) {
                if ($merchandise->getRawOriginal('path_image')) {
                    Storage::disk('public')->delete($merchandise->getRawOriginal('path_image'));
                }
                $validated['path_image'] = $request->file('path_image')->storePublicly('merchandises', 'public');
            }

            $validated['is_available'] = $request->boolean('is_available');

            $merchandise->update($validated);

            return redirect()->route('merchandise.edit', $merchandise)
                ->with('success', 'Merchandise updated successfully.');
        }

        return view('pages.systems.merchandise_edit', [
            'data' => $merchandise,
            'method' => 'put',
        ]);
    }

    /**
     * Delete a merchandise item.
     */
    public function delete(Request $request, Merchandise $merchandise): RedirectResponse
    {
        if ($merchandise->getRawOriginal('path_image')) {
            Storage::disk('public')->delete($merchandise->getRawOriginal('path_image'));
        }

        $merchandise->delete();

        return redirect()->route('merchandise.index')
            ->with('success', 'Merchandise deleted successfully.');
    }
}
