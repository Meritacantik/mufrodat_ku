<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $score = \App\Models\Score::where('user_id', $user->id)->first();
        $totalPoin = $score->total_poin ?? 0;
        $kuisSelesai = $score->total_sesi ?? 0;

        $kataDikuasai = \App\Models\QuizAnswer::whereHas('session', fn($q) =>
            $q->where('user_id', $user->id)
        )->where('status', 'BENAR')->distinct('mufrodat_id')->count('mufrodat_id');

        $levelKelas = [];
        foreach (['VII', 'VIII', 'IX'] as $kelas) {
            $total = \App\Models\Mufrodat::where('kelas', $kelas)->count();
            $dikuasai = \App\Models\QuizAnswer::whereHas('session', fn($q) =>
                $q->where('user_id', $user->id)->where('kelas', $kelas)
            )->where('status', 'BENAR')->distinct('mufrodat_id')->count('mufrodat_id');

            $levelKelas[$kelas] = [
                'dikuasai' => $dikuasai,
                'total' => $total,
                'persen' => $total > 0 ? min(100, round(($dikuasai / $total) * 100)) : 0,
            ];
        }

        return view('profile.edit', [
            'user' => $user,
            'totalPoin' => $totalPoin,
            'kuisSelesai' => $kuisSelesai,
            'kataDikuasai' => $kataDikuasai,
            'levelKelas' => $levelKelas,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
