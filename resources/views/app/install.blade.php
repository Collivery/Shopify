@extends('layouts.app')

@section('content')
    <section class="one-third">
        <div class="card">
            <form action="{{url('/install')}}" class="" method="post">
                {{ csrf_field() }}
                <div class="align-center">
                    <div class="input-group column six" style="float: none; margin: auto;">
                        <input type="text" name="shop" placeholder="yourshop.myshopify.com" value="{{$shop}}">
                        <button>Install</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
