@extends('layout.master')

@section('title', 'Admin — ToolShare')

@section('header-actions')
@if (auth('api')->check())
  <a class="btn btn-secondary" href="{{ route('categories.index') }}">دسته‌بندی‌ها</a>
    {{ auth('api')->user()->first_name }}
@else
  <a class="btn btn-secondary" href="{{ route('login') }}">ورود</a>
  <a class="btn btn-primary" href="{{ route('signUp') }}">ثبت‌نام  </a>
@endif
@endsection

@section('content')

<main class="page-area">
    <div class="main-content">

        <section class="intro">
            <p class="kicker">مدیریت سیستم</p>
            <h1 class="display-font">تخصیص نقش کاربران</h1>
            <p class="intro-copy">نقش هر کاربر را مشخص کنید — نقش تعیین می‌کند کاربر به چه بخش‌هایی دسترسی دارد.</p>
        </section>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="form-card" style="margin-top:24px;">

            @forelse ($users as $user)

                <div class="spec-row">

                    <div style="display:flex; align-items:center; gap:14px;">
                        <span class="step-number" style="background:#eef4eb; color:var(--forest); font-size:16px;">
                            {{ mb_substr($user->first_name, 0, 1) }}
                        </span>

                        <div>
                            <div style="font-weight:700;">{{ $user->first_name }} {{ $user->last_name }}</div>
                            <small style="color:var(--muted);">{{ $user->email }}</small>
                        </div>

                        @foreach ($user->roles as $role)
                            <span class="tag">{{ $role->name }}</span>
                        @endforeach
                    </div>
                    @if(!$user->hasRole('creator'))
                    <form action="{{ route('users.role.update', $user) }}" method="POST" style="display:flex; align-items:center; gap:8px;">
                        @csrf
                        @method('PUT')

                        <select class="form-control" name="role_ids[]" multiple style="width:auto; min-width:180px;">
                            @foreach ($roles as $role)
                                @if ($role->name !== 'creator')                                   
                                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-secondary" style="padding:8px 14px; font-size:13px;">
                            ذخیره
                        </button>
                    </form>
                    @else
                        <span>Creator</span>
                    @endif
                </div>
                @can('permission', 'manage-users')
                    @if (!$user->hasRole('creator'))
                        @if ($user->trashed())
                            <form action="{{ route('users.restore', $user->id) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('آیا می‌خواهید این کاربر را بازیابی کنید؟');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary">
                                    بازیابی کاربر
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.destroy', $user) }}"
                                method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('آیا از حذف این کاربر مطمئن هستید؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    حذف کاربر
                                </button>
                            </form>
                        @endif
                    @endif
                @endcan
            @empty

                <p style="color:var(--muted); text-align:center; padding:30px 0;">هیچ کاربری ثبت‌نام نکرده است.</p>

            @endforelse

        </div>

    </div>
</main>

@endsection