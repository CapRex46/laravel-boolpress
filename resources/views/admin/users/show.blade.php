@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex">
            <a href="{{ route('admin.users.index') }}" class="me-2">
              < </a>

                {{ $user->name }}

                <a class="ms-auto" href="{{ route('admin.users.edit', $user->id) }}">Modifica</a>
          </div>

          <div class="card-body">

            <p class="lead">
              {{ $user->email }}
            </p>



          </div>
        </div>
      </div>
    </div>
  </div>
@endsection