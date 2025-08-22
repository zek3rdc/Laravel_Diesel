<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title">{{ __('Delete Account') }}</h5>
        <p class="card-category">{{ __('Permanently delete your account.') }}</p>
    </div>
    <div class="card-body">
        <div class="text-sm">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        <div class="mt-3">
            <button class="btn btn-danger" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Delete Account') }}
            </button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <div class="modal fade" id="confirmingUserDeletionModal" tabindex="-1" aria-labelledby="confirmingUserDeletionModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmingUserDeletionModalLabel">{{ __('Delete Account') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                            <input type="password" class="form-control mt-1 w-75"
                                        autocomplete="current-password"
                                        placeholder="{{ __('Password') }}"
                                        x-ref="password"
                                        wire:model.defer="password"
                                        wire:keydown.enter="deleteUser" />

                            @error('password') <span class="text-danger mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </button>

                        <button type="button" class="btn btn-danger ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
