@extends('admin.layouts.master')

@section('content')
<div class="flex grow">
    @include('layouts.partials._sidebar')
    <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
        <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
            <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
                <main class="grow" role="content">
                    <div class="pb-5">
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">
                                    {{ trans('general.update_user_info') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="kt-container-fixed">
                        <div class="grid gap-5 lg:gap-7.5">
                            <div class="kt-card kt-card-grid">
                                <div class="kt-card-header">
                                    <h4 class="kt-card-title">{{ trans('general.update_user_info') }}</h4>
                                </div>
                                <div class="kt-card-content p-5">
                                    <form method="post" action="{{ route('admin.users.userUpdate') }}" id="form" enctype="multipart/form-data">
                                        @csrf
                                        @method('patch')

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Photo Upload -->
                                            <div class="md:col-span-2 flex flex-col items-center">
                                                <img id="img_data" class="rounded-xl object-cover mb-3" width="165" height="165"
                                                     src="@if(!empty($user->photo)){{$user->photo}}@else {{ asset('public/images/users/user-09-247x247.png') }} @endif"
                                                     alt="User Photo"/>
                                                <input type="hidden" id="data_photo" name="photo" value="{{ $user->photo ?? '' }}"/>
                                                <input type="file" id="photo_upload" name="photo_file" class="kt-input w-auto">
                                                <p class="text-sm text-muted-foreground mt-1">{{ trans('adminUser.photo_upload_tip') }}</p>
                                            </div>

                                            <!-- Name (Read-only) -->
                                            <div>
                                                <label for="name" class="text-sm text-muted-foreground">{{ trans('adminUser.name') }}:</label>
                                                <span class="kt-input w-full border-0 bg-transparent">{{ $user->name ?? '' }}</span>
                                            </div>

                                            <!-- Account (Read-only) -->
                                            <div>
                                                <label for="account" class="text-sm text-muted-foreground">{{ trans('adminUser.account') }}:</label>
                                                <span class="kt-input w-full border-0 bg-transparent">{{ $user->account ?? '' }}</span>
                                            </div>

                                            <!-- Level (Read-only) -->
                                            <div>
                                                <label for="level" class="text-sm text-muted-foreground">{{ trans('adminUser.level') }}:</label>
                                                <span class="kt-input w-full border-0 bg-transparent">{{ $user->levels->level_name ?? '' }}</span>
                                            </div>

                                            <!-- Phone (Editable) -->
                                            <div>
                                                <label for="phone" class="text-sm text-muted-foreground">{{ trans('adminUser.phone') }}:</label>
                                                <input id="phone" name="phone" type="text" class="kt-input w-full" value="{{ old('phone', $user->phone) }}" oninput="value=value.replace(/[^\d]/g,'')" maxlength="15" autocomplete="phone" />
                                                @error('phone')
                                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Remark (Editable) -->
                                            <div class="md:col-span-2">
                                                <label for="remark" class="text-sm text-muted-foreground">{{ trans('adminUser.remark') }}:</label>
                                                <textarea id="remark" name="remark" class="kt-input w-full" maxlength="128">{{ old('remark', $user->remark) }}</textarea>
                                                @error('remark')
                                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Buttons -->
                                            <div class="md:col-span-2 flex justify-end gap-3 mt-5">
                                                <button type="submit" class="kt-btn kt-btn-primary">{{ trans('general.update_user_info') }}</button>
                                                <button type="button" class="kt-btn kt-btn-warning" onclick="history.go(-1);">{{ trans('general.return') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photoUpload = document.getElementById('photo_upload');
        const imgData = document.getElementById('img_data');
        const dataPhoto = document.getElementById('data_photo');

        if (photoUpload) {
            photoUpload.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imgData.src = e.target.result;
                        // You might want to upload the file via AJAX here
                        // and update data_photo with the returned URL
                        // For now, we'll just update the preview
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        const form = document.getElementById('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(form);
                // If you have a file input, append it to FormData
                if (photoUpload && photoUpload.files[0]) {
                    formData.append('photo_file', photoUpload.files[0]);
                }

                // Add CSRF token
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('_method', 'PATCH'); // For @method('patch')

                fetch(form.action, {
                    method: 'POST', // Fetch will use POST for FormData, Laravel will interpret _method as PATCH
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // FormData handles this
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.code !== 0) {
                        alert(result.msg || 'Update failed!');
                    } else {
                        alert(result.msg || 'Update successful!');
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during update.');
                });
            });
        }
    });
</script>
@endpush
