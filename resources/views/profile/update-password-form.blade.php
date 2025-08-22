<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title">{{ __('Update Password') }}</h5>
        <p class="card-category">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="updatePassword">
            <div class="mb-3">
                <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                <input id="current_password" type="password" class="form-control" wire:model.defer="state.current_password" autocomplete="current-password">
                @error('current_password') <span class="text-danger mt-2">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('New Password') }}</label>
                <input id="password" type="password" class="form-control" wire:model.defer="state.password" autocomplete="new-password">
                @error('password') <span class="text-danger mt-2">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" class="form-control" wire:model.defer="state.password_confirmation" autocomplete="new-password">
                @error('password_confirmation') <span class="text-danger mt-2">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-end">
                 <div wire:loading wire:target="updatePassword" class="me-3">
                    {{ __('Saving...') }}
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('Save') }}
                </button>
            </div>
             @if (session()->has('saved'))
                <span class="text-success">{{ session('saved') }}</span>
            @endif
        </form>
    </div>
</div>
