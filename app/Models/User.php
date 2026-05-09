<?php

namespace App\Models;

use App\Models\Tournament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rarq\FilamentQuickNotes\Traits\HasFilamentQuickNotes;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, HasPasskeys, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use Notifiable, InteractsWithPasskeys, HasFilamentQuickNotes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the tournaments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        $panel = $panel->getId();
        if ($panel == 'admin') {
            return str_ends_with($this->email, '@suminshrestha.com.np') || str_ends_with($this->email, '@admin.com');
        } else if ($panel == 'maidan') {
            return true;
        }
        return false;
    }

    public function getActiveMatch()
    {
        $activeMatch = TournamentMatch::whereHas('tournament', function ($query) {
            $query->where('user_id', $this->id)->where('is_active', true);
        })->where('is_active', true)->with(['matchStats', 'tournament', 'tournament.tournamentTeams'])->first();
        if (!$activeMatch) {
            abort(404, 'No active match found for this user.');
        }

        return $activeMatch;
    }
}
