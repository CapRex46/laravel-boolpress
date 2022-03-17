@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex">
            Lista degli utenti
          </div>

          <table class="table">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <td></td>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr class="">
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    <div class="ms-auto">
                      <a class="me-3" href="{{ route('admin.users.show', $user->id) }}" title="Visualizza"><i
                          class="fa-solid fa-eye"></i></a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection