<?php

namespace App\Livewire\Settings\TwoFactor;

use App\Models\User;
use Exception;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RecoveryCodes extends Component
{
    /**
     * @var array<int, string>
     */
    #[Locked]
    public array $recoveryCodes = [];

    /**
     * Get the authenticated user.
     */
    private function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $generateNewRecoveryCodes($this->user());

        $this->loadRecoveryCodes();
    }

    /**
     * Load the recovery codes for the user.
     */
    private function loadRecoveryCodes(): void
    {
        $user = $this->user();
        $recoveryCodes = $user->two_factor_recovery_codes;

        if ($user->hasEnabledTwoFactorAuthentication() && $recoveryCodes) {
            try {
                $decrypted = decrypt($recoveryCodes);

                if (is_string($decrypted)) {
                    /** @var array<int, string>|null $decoded */
                    $decoded = json_decode($decrypted, true);

                    if (is_array($decoded)) {
                        $this->recoveryCodes = $decoded;
                    }
                }
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Failed to load recovery codes');

                $this->recoveryCodes = [];
            }
        }
    }
}
