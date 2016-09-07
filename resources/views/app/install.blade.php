@extends('layouts.app')
@section('title', 'Install app')
@section('content')
    <section class="one-third">
        <div class="card">
            @if(Session::has('shop_success'))
                <div class="alert success">
                    {{Session::get('shop_success')}}
                </div>
            @endif
            <form action="{{url('/install')}}" class="" method="post">
                {{ csrf_field() }}
                <div class="column three">&nbsp;</div>
                <div class="align-center">
                    <div class="input-group column six {{Session::has('shop_error') ? 'error'  : ''}}">
                        <button disabled="disabled">https://</button>
                        <input type="text" name="shop" placeholder="yourshop.myshopify.com" value="{{$shop}}">
                        <button>Install</button>
                    </div>
                </div>
                <div class="column three">&nbsp;</div>
            </form>
        </div>
    </section>
@endsection
