@extends('layout.master')

@section('header-actions')
@if (auth('api')->check())
  <a class="btn btn-secondary" href="{{ route("categories.index") }}">دسته‌بندی‌ها</a>
    {{ auth('api')->user()->first_name }}
@else
  <a class="btn btn-secondary" href="{{ route("login") }}">ورود</a>
  <a class="btn btn-primary" href="{{ route('signUp') }}">ثبت‌نام  </a>
@endif
@endsection

@section('content')

<main class="page-area">
    <div class="container">

        <div class="detail-grid">

            <div>

                @forelse($post->images as $image)

                    @if ($loop->first)
                        <img
                            id="mainImage"
                            class="detail-image"
                            style="width:100%; object-fit:cover;"
                            src="{{ asset('storage/' . $image->path) }}"
                            alt="{{ $post->title }}"
                        >
                    @endif

                @empty

                    <div class="detail-image" style="display:flex; align-items:center; justify-content:center; background:var(--cream); color:var(--muted);">
                        این پست تصویری ندارد.
                    </div>

                @endforelse

                @if (count($post->images) > 1)

                    <div class="thumbs">

                        @foreach($post->images as $image)

                            <img
                                class="thumb {{ $loop->first ? 'active' : '' }}"
                                src="{{ asset('storage/' . $image->path) }}"
                                alt="{{ $post->title }}"
                                onclick="document.getElementById('mainImage').src = this.src; document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active')); this.classList.add('active');"
                            >

                        @endforeach

                    </div>

                @endif

            </div>

            <div>

                <h1 class="detail-title">{{ $post->title }}</h1>

                <div>
                    <h3>توضیحات</h3>

                    <p class="detail-meta">
                        {{ $post->description }}
                    </p>
                </div>

                <div class="detail-section">

                    <div class="spec-row">
                        <strong>دسته‌بندی:</strong>

                        <span class="tag">{{ $post->category->name }}</span>
                    </div>

                    <div class="spec-row">
                        <strong>وضعیت ابزار:</strong>

                        <span>{{ $post->condition }}</span>
                    </div>

                    <div class="spec-row">
                        <strong>موقعیت:</strong>

                        <span>{{ $post->location }}</span>
                    </div>

                    <div class="spec-row">
                        <strong>قابل استفاده از:</strong>

                        <span>{{ $post->available_from }}</span>
                    </div>

                    <div class="spec-row">
                        <strong>قابل استفاده تا:</strong>

                        <span>{{ $post->available_untill }}</span>
                    </div>

                </div>

                <div class="price-box">
                    <div class="detail-price">
                        {{ $post->first_day_price }} تومان
                        <span style="font-size:14px; font-weight:400; color:var(--muted);">/ روز اول</span>
                    </div>

                    <div style="margin-top:6px; color:var(--muted);">
                        {{ $post->extra_day_price }} تومان به‌ازای هر روز اضافه
                    </div>
                </div>

                <div class="owner" style="margin-top:20px;">
                    <strong>صاحب ابزار:</strong>

                    <p style="margin:6px 0 0;">
                        {{ $post->user->first_name }}
                        {{ $post->user->last_name }}
                    </p>
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">ویرایش پست</a>
                    @endcan
                    @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn text-danger">حذف</button>
                    </form>
                    @endcan
                </div>

            </div>

        </div>
    </div>
</main>

@endsection