<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{ __('Profile Information') }}</h5>
        <p class="card-category">{{ __('Update your account\'s profile information and email address.') }}</p>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="updateProfileInformation">
            <!-- Profile Photo -->
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{photoName: null, photoPreview: null}" class="mb-3">
                    <!-- Profile Photo File Input -->
                    <input type="file" id="photo" class="d-none"
                                wire:model.live="photo"
                                x-ref="photo"
                                x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                " />

                    <label for="photo" class="form-label">{{ __('Photo') }}</label>

                    <!-- Current Profile Photo -->
                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <span class="d-block rounded-circle bg-cover bg-no-repeat bg-center"
                              style="width: 80px; height: 80px;"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <button class="btn btn-sm btn-outline-primary mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select A New Photo') }}
                    </button>

                    @if ($this->user->profile_photo_path)
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" wire:click="deleteProfilePhoto">
                            {{ __('Remove Photo') }}
                        </button>
                    @endif

                    @error('photo') <span class="text-danger mt-2">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control" wire:model.defer="state.name" required autocomplete="name">
                @error('name') <span class="text-danger mt-2">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control" wire:model.defer="state.email" required autocomplete="username">
                @error('email') <span class="text-danger mt-2">{{ $message }}</span> @enderror

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2">
                        {{ __('Your email address is unverified.') }}

                        <button type="button" class="btn btn-link text-sm" wire:click.prevent="sendEmailVerification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if ($this->verificationLinkSent)
                        <p class="mt-2 font-medium text-sm text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                @endif
            </div>

            <div class="d-flex justify-content-end">
                <div wire:loading wire:target="updateProfileInformation" class="me-3">
                    {{ __('Saving...') }}
                </div>

                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="photo">
                    {{ __('Save') }}
                </button>
            </div>
             @if (session()->has('saved'))
                <span class="text-success">{{ session('saved') }}</span>
            @endif
        </form>
    </div>
</div>
