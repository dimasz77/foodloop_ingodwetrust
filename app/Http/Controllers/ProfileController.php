<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ImpactCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $column = $user->isProvider() ? 'provider_id' : 'recipient_id';
        $delivered = Report::where($column, $user->id)->where('status', 'delivered')->get();

        return view('profile', [
            'user' => $user,
            'journey' => ImpactCalculator::journey($delivered),
            'portions' => (int) $delivered->sum('portions'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'organization' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'lat' => ['nullable', 'numeric', 'between:-90,90', 'required_with:lng'],
            'lng' => ['nullable', 'numeric', 'between:-180,180', 'required_with:lat'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ], [
            'avatar.image' => 'Foto profil harus berupa gambar.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }
        unset($validated['avatar']);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('ok', 'Profil berhasil diperbarui.');
    }
}
