<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <h6 class="text-primary bold"> Update Profile Information <i class="fa fa-arrow-down" aria-hidden="true"></i></h6>
    </x-slot>

    <x-slot name="description">
     {{-- <p class="card-description">
        Update your account's profile information and email address. <i class="fa fa-arrow-right" aria-hidden="true"></i> </p>  --}}
    </x-slot>
 
    <x-slot name="form">

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="my-class-card col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden" wire:model="photo" accept="image/*" id="img" onchange="validateImage()" x-ref="photo" x-on:change=" photoName = $refs.photo.files[0].name; const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);"/>

                <x-jet-label for="photo"/>

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover" x-on:click.prevent="$refs.photo.click()">
                    <i class="fa fa-camera ml-4 pl-4 text-primary" aria-hidden="true" x-on:click.prevent="$refs.photo.click()"></i>
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                {{--<x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>--}}

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-4 sm:col-span-2">
            <x-jet-label for="name" value="{{ __('Name *') }}" />
            <x-jet-input id="name" type="text" maxlength="30" minlength="3" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32)" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

       
         <!-- Contact -->
        <div class="col-span-4 sm:col-span-2">
            <x-jet-label for="contact" value="{{ __('Contact') }}" />
            <x-jet-input id="contact" type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="6" maxlength="15" class="mt-1 block w-full" wire:model.defer="state.contact" />
            <x-jet-input-error for="contact" class="mt-2" />
        </div>

         <!-- Email -->
        <div class="col-span-6 sm:col-span-2">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" type="email" disabled="disabled" class="mt-1 block w-full" wire:model.defer="state.email" style="background-color: #e2e2e2;"/>
            <x-jet-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3 text-success" on="saved">
            {{ __('Details Updated Successfully.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
    <script type="text/javascript">
         function validateImage() {
            var formData = new FormData();

            var file = document.getElementById("img").files[0];

              formData.append("Filedata", file);
              var t = file.type.split('/').pop().toLowerCase();
              if (t != "jpeg" && t != "jpg" && t != "png") {
                  Swal.fire({
                    icon: 'info',
                    confirmButtonText: 'Okay, got it!',
                    text: 'Please select a valid image file.(JPG/PNG/JPEG)!',
                  });
                  document.getElementById("img").value = '';
                  return false;
              }
              if (file.size > 1024000) {
                  Swal.fire({
                    icon: 'info',
                    confirmButtonText: 'Okay, got it!',
                    text: 'Max Upload size is 10MB only!',
                  });
                  document.getElementById("img").value = '';
                  return false;
              }
              return true;
          }
    </script>
</x-jet-form-section>
