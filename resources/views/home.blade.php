@extends('layout.master')

@section('header-actions')
@if (auth('api')->check())
    {{ auth('api')->user()->first_name }}
@else
  <a class="btn btn-secondary" href="{{ route("login") }}">ورود</a>
  <a class="btn btn-primary" href="{{ route('signUp') }}">ثبت‌نام  </a>
@endif
@endsection

@section('content')
<main>
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
  @endif
  <section class="hero">
    <div class="container hero-grid">
      <div>
        <div class="kicker">جامعه‌ای برای به اشتراک گذاشتن ابزارهای کاربردی</div>
        <h1>آنچه نیاز داری قرض بگیر؛ آنچه داری به اشتراک بگذار.</h1>
        <p>
          تول‌شر این امکان را فراهم می‌کند تا ابزارها و تجهیزات مورد نیازت را از افراد اطراف خود قرض بگیری.
           بدون اینکه وسیله‌ای را که فقط یک‌بار به آن نیاز داری خریداری کنی، کار خود را انجام بده.
        </p>
        <div class="hero-buttons">
          <a class="btn btn-primary" href="#">مشاهده ابزار ها</a>
          <a class="btn btn-secondary" href="{{ route('create_post') }}">ثبت ابزار</a>
        </div>
      </div>
      <div class="hero-image">
        <img
          src="{{ asset('images/logo.png') }}"
          alt="Tools"
        />
      </div>
    </div>
  </section>

  <section id="how" class="section sage">
    <div class="container">
      <div class="kicker">چگونه کار می‌کند</div>
      <h2 class="section-title">ساده از شروع تا پایان.</h2>
      <div class="steps">
        <article class="step">
          <span class="step-number">01</span>
          <h3>یک ابزار پیدا کن</h3>
          <p>وسیله مورد نیازت را جستجو کن و ابزارهای موجود در اطراف خودت را پیدا کن.</p>
        </article>
        <article class="step">
          <span class="step-number">02</span>
          <h3>درخواست قرض گرفتن بده</h3>
          <p>تاریخ مورد نظرت را انتخاب کن، درخواست خود را ارسال کن و با صاحب ابزار ارتباط بگیر.</p>
        </article>
        <article class="step">
          <span class="step-number">03</span>
          <h3>به اشتراک بگذار و پس‌انداز کن</h3>
          <p>به جای خرید ابزار، آن را قرض بگیر؛ یا ابزارهای خودت را ثبت کن و از آن‌ها درآمد داشته باش.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="kicker">ابزار های محبوب</div>
          <h2 class="section-title">ابزارهایی که افراد در حال به اشتراک گذاشتن هستند</h2>
        </div>
        <a class="btn btn-secondary" href="#">مشاهده تمام ابزارها</a>
      </div>
      <div class="tools-grid">            
     <article class="tool-card">
        <img
        class="tool-image"
        src="{{ asset('/images/photo-1504148455328-c376907d081c.jpg') }}"
        />
        <div class="tool-body">
        <span class="tag">ابزار های برقی</span>
        <h3>دریل شارژی</h3>
        <p>دریل جمع‌وجور و کاربردی برای تعمیرات منزل و پروژه‌ها</p>
        <div class="card-bottom">
            <span class="price">80,000 تومان / روز</span>
            <a href="details.html">مشاهده ←</a>
        </div>
        </div>
    </article>
          </div>
    </div>
  </section>
</main>

@endsection