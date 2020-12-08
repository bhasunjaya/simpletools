@extends('layouts.app')

@section('content')
    
  <section class="section">
    <div class="container">
      <h1 class="title">Facebook Tools</h1>
      <p class="subtitle">My first website with <strong>Bulma</strong>!</p>
      <a class="button is-primary is-large" href="{{url('connecting/facebook')}}">Login With Facebook</a>
    </div>
  </section>
@endsection