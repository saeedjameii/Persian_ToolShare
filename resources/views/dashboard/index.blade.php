@extends('layout.master')

@section('title', 'پنل مدیریت — ToolShare')

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

@php
    $user = auth('api')->user();
@endphp

<main class="page-area">
    <div class="main-content">

        <section class="intro">
            <p class="kicker">پنل مدیریت</p>
            <h1 class="display-font">سلام {{ $user->first_name }} 👋</h1>
            <p class="intro-copy">
                نقش فعلی شما:
                @foreach ($user->roles as $role)
                    <span class="tag">{{ $role->name }}</span>
                @endforeach
                — فقط بخش‌هایی که به آن‌ها دسترسی دارید در پایین نمایش داده می‌شود.
            </p>
        </section>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin-top:24px;">

            <div class="form-card">
                <h3 style="margin-top:0;">📦 ابزارهای من</h3>
                <p style="color:var(--muted);">ابزارهایی که خودتان ثبت کرده‌اید را ببینید، ویرایش یا حذف کنید.</p>
                <a href="{{ route('posts.mine') }}" class="button button-primary" style="width:100%; text-align:center;">مشاهده‌ی پست‌های من</a>
            </div>

            @can('permission', 'create-post')
                <div class="form-card">
                    <h3 style="margin-top:0;">➕ ثبت ابزار جدید</h3>
                    <p style="color:var(--muted);">یک ابزار تازه برای اجاره در سایت ثبت کنید.</p>
                    <a href="{{ route('create_post') }}" class="button button-primary" style="width:100%; text-align:center;">ثبت ابزار</a>
                </div>
            @endcan

            @if ($user->hasPermission('create-category') || $user->hasPermission('update-category') || $user->hasPermission('delete-category'))
                <div class="form-card">
                    <h3 style="margin-top:0;">📁 مدیریت دسته‌بندی‌ها</h3>
                    <p style="color:var(--muted);">دسته‌بندی و زیردسته‌بندی ابزارها را بسازید یا ویرایش کنید.</p>

                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary" style="flex:1; text-align:center;">لیست دسته‌بندی‌ها</a>

                        @can('permission', 'create-category')
                            <a href="{{ route('categories.create') }}" class="btn btn-secondary" style="flex:1; text-align:center;">+ جدید</a>
                        @endcan
                    </div>
                </div>
            @endif

            @if ($user->hasPermission('create-role') || $user->hasPermission('update-role') || $user->hasPermission('delete-role'))
                <div class="form-card">
                    <h3 style="margin-top:0;">🛡️ مدیریت نقش‌ها</h3>
                    <p style="color:var(--muted);">نقش‌ها و اختیارات (permission) هر نقش را تعریف کنید.</p>

                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary" style="flex:1; text-align:center;">لیست نقش‌ها</a>

                        @can('permission', 'create-role')
                            <a href="{{ route('roles.create') }}" class="btn btn-secondary" style="flex:1; text-align:center;">+ نقش جدید</a>
                        @endcan
                    </div>
                </div>
            @endif

            @if ($user->hasPermission('assign-role') || $user->hasPermission('manage-users'))
                <div class="form-card">
                    <h3 style="margin-top:0;">👥 مدیریت کاربران</h3>
                    <p style="color:var(--muted);">به کاربران نقش بدهید یا حساب‌های کاربری را مدیریت کنید.</p>
                    <a href="{{ route('users.index') }}" class="button button-primary" style="width:100%; text-align:center;">مدیریت کاربران</a>
                </div>
            @endif

        </div>

    </div>
</main>

@endsection