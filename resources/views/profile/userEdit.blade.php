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
                                        @method('put')

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Photo Upload -->
                                            <div class="md:col-span-2 flex flex-col items-center">
                                                <img id="img_data" class="rounded-xl object-cover mb-3" width="165" height="165"
                                                     src="@if(!empty($info->photo)){{ $info->photo }}@else {{ asset('public/images/users/user-09-247x247.png') }} @endif"
                                                     alt="User Photo"/>
                                                <input type="hidden" id="data_photo" name="photo" value="{{ $info->photo ?? '' }}"/>
                                                <input type="file" id="photo_upload" name="photo_file" class="kt-input w-auto">
                                                <p class="text-sm text-muted-foreground mt-1">{{ trans('adminUser.photo_upload_tip') }}</p>
                                            </div>

                                            <!-- Name (Read-only) -->
                                            <div>
                                                <label for="name" class="text-sm text-muted-foreground">{{ trans('adminUser.name') }}:</label>
                                                <span class="kt-input w-full border-0 bg-transparent">{{ $info->name ?? '' }}</span>
                                            </div>

                                            <!-- Account (Read-only) -->
                                            <div>
                                                <label for="account" class="text-sm text-muted-foreground">{{ trans('adminUser.account') }}:</label>
                                                <span class="kt-input w-full border-0 bg-transparent">{{ $info->account ?? '' }}</span>
                                            </div>

                                            <!-- Level (Read-only) -->
                                            <div>
                                                <label for="level" class="text-sm text-muted-foreground">{{ trans('adminUser.level') }}:</label>
                                                <span class="kt-input w-full border-0 bg-transparent">{{ optional($info->levels)->level_name ?? '' }}</span>
                                            </div>

                                            <!-- Phone (Editable) -->
                                            <div>
                                                <label for="phone" class="text-sm text-muted-foreground">{{ trans('adminUser.phone') }}:</label>
                                                <input id="phone" name="phone" type="text" class="kt-input w-full"
                                                       value="{{ old('phone', $info->phone ?? '') }}"
                                                       oninput="value=value.replace(/[^\d]/g,'')" maxlength="15" autocomplete="phone" />
                                                @error('phone')
                                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Remark (Editable) -->
                                            <div class="md:col-span-2">
                                                <label for="remark" class="text-sm text-muted-foreground">{{ trans('adminUser.remark') }}:</label>
                                                <textarea id="remark" name="remark" class="kt-input w-full" maxlength="128">{{ old('remark', $info->remark ?? '') }}</textarea>
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
document.addEventListener('DOMContentLoaded', () => {
  // image preview (keep yours)
  const photoUpload = document.getElementById('photo_upload');
  const imgData = document.getElementById('img_data');
  if (photoUpload && imgData) {
    photoUpload.addEventListener('change', (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (ev) => { imgData.src = ev.target.result; };
      reader.readAsDataURL(file);
    });
  }

  const form = document.getElementById('form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();                          // stop normal POST
    clearErrors(form);

    const submitBtn = form.querySelector('[type="submit"]');
    const origText = submitBtn?.textContent;
    if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Saving…'; }

    try {
      const fd = new FormData(form);             // includes _token and _method=PUT
      // If your backend expects "photo" instead of "photo_file", keep both:
      // if (fd.has('photo_file')) fd.set('photo', fd.get('photo_file'));

      const resp = await fetch(form.action, {
        method: 'POST',                           // spoofing PUT via hidden _method
        headers: {
          'X-Requested-With': 'XMLHttpRequest',  // <— makes $request->ajax() true
          'Accept': 'application/json'
        },
        body: fd
      });

      // Laravel validation error
      if (resp.status === 422) {
        const data = await resp.json();
        showValidation(form, data.errors || {});
        throw new Error('Validation failed');
      }

      const data = await resp.json();

      if (data.code === 0) {
        // success
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          toast(data.msg || 'Updated successfully'); // or alert(...)
        }
      } else {
        throw new Error(data.msg || 'Update failed');
      }
    } catch (err) {
      console.error(err);
      alert(err.message || 'Something went wrong.');
    } finally {
      if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = origText; }
    }
  });

  function clearErrors(root) {
    root.querySelectorAll('.text-danger').forEach(el => el.remove());
  }

  function showValidation(root, errors) {
    Object.entries(errors).forEach(([field, messages]) => {
      const input = root.querySelector(`[name="${field}"]`);
      if (!input) return;
      const p = document.createElement('p');
      p.className = 'text-danger text-xs mt-1';
      p.textContent = Array.isArray(messages) ? messages[0] : String(messages);
      input.closest('div')?.appendChild(p);
    });
  }

  function toast(msg) { alert(msg); } // replace with your UI toast if available
});
</script>
@endpush

